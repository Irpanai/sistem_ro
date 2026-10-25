<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialOutbound;
use App\Models\Ticket;
use App\Models\TicketLog;
use App\Models\TopologyNode;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderTechnician;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $query = Ticket::with(['workOrder.technicians', 'workOrder.materialsOutbound', 'user', 'ticketLogs']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('issue', 'like', "%{$search}%")
                    ->orWhere('odp_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('type') && $request->type !== 'Semua') {
            $query->where('type', $request->type);
        }

        $tickets = $query->orderByDesc('created_at')->paginate(12)->withQueryString();

        $topologyNodes = TopologyNode::where('status', 'Online')->orderBy('name')->get();
        $allNodes = TopologyNode::all();
        $technicians = User::where('role', 'teknisi')->orderBy('name')->get();
        $materials = Material::where('stock', '>', 0)->orderBy('name')->get();

        return view('tiket.index', compact('tickets', 'topologyNodes', 'allNodes', 'technicians', 'materials'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'nullable|string|max:30',
            'address' => 'required|string|max:255',
            'area' => 'required|string|max:100',
            'odp_code' => 'required|string|max:50',
            'issue' => 'required|string',
            'priority' => 'required|in:Low,Medium,High,Critical',
        ]);

        $odp = TopologyNode::where('name', $validated['odp_code'])->first();
        $isOdpOffline = $odp && $odp->status === 'Offline';

        $count = Ticket::count() + 1;
        $validated['ticket_number'] = 'TKT-2026-'.str_pad((string) $count, 3, '0', STR_PAD_LEFT);
        $validated['type'] = $isOdpOffline ? 'OUTAGE' : 'ISOLIR';
        $validated['status'] = 'Pending';
        $validated['analysis_details'] = $isOdpOffline
            ? "Peringatan: Simpul {$validated['odp_code']} berstatus Offline. Indikasi gangguan massal pada kabel feeder."
            : "ODP {$validated['odp_code']} berstatus Online normal. Gangguan lokal pada jalur dropcore pelanggan.";
        $validated['reported_at'] = now();

        $ticket = Ticket::create($validated);

        // Record initial TicketLog
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'actor_name' => auth()->user()?->name ?? 'Admin NOC',
            'actor_role' => auth()->user()?->role ?? 'admin',
            'action' => 'TICKET_CREATED',
            'title' => 'Laporan Tiket Didaftarkan',
            'description' => "Pengaduan masuk untuk pelanggan {$ticket->customer_name} pada ODP {$ticket->odp_code} ({$ticket->area}). Gejala: {$ticket->issue}. Status awal: Pending verifikasi tim NOC.",
        ]);

        return redirect()->route('tiket.index')
            ->with('success', "Keluhan pelanggan baru berhasil didaftarkan dengan ID: {$ticket->ticket_number}.");
    }

    public function dispatchSpk(Request $request, Ticket $ticket): RedirectResponse
    {
        // Backward-compatibility support for single technician_id
        if (! $request->has('technician_ids') && $request->has('technician_id')) {
            $request->merge(['technician_ids' => [$request->input('technician_id')]]);
        }

        $validated = $request->validate([
            'classification_type' => 'required|in:ISOLIR,OUTAGE',
            'technician_ids' => 'required|array|min:1',
            'technician_ids.*' => 'exists:users,id',
            'lead_technician_id' => 'nullable|exists:users,id',
            'material_ids' => 'nullable|array',
            'material_ids.*' => 'exists:materials,id',
            'material_quantities' => 'nullable|array',
            'admin_dispatch_notes' => 'nullable|string',
        ]);

        $technicians = User::whereIn('id', $validated['technician_ids'])->get();
        $leadTech = $technicians->firstWhere('id', $validated['lead_technician_id'] ?? null) ?? $technicians->first();
        $allTechNames = $technicians->pluck('name')->join(', ');

        DB::transaction(function () use ($ticket, $validated, $technicians, $leadTech, $allTechNames, $request) {
            $prefix = $validated['classification_type'] === 'OUTAGE' ? 'OUT' : 'ISL';
            $spkNumber = 'SPK-2026-'.$prefix.'-'.rand(100, 999);

            // 1. Update Ticket
            $ticket->update([
                'status' => 'In Progress',
                'type' => $validated['classification_type'],
            ]);

            // 2. Create WorkOrder
            $workOrder = WorkOrder::create([
                'spk_number' => $spkNumber,
                'ticket_id' => $ticket->id,
                'technician_id' => $leadTech->id,
                'technician_name' => $allTechNames,
                'technician_phone' => $leadTech->phone,
                'status' => 'In Progress',
                'dispatched_at' => now(),
            ]);

            // 3. Attach all technicians in team
            foreach ($technicians as $tech) {
                WorkOrderTechnician::create([
                    'work_order_id' => $workOrder->id,
                    'user_id' => $tech->id,
                    'technician_name' => $tech->name,
                    'technician_phone' => $tech->phone,
                    'role_in_team' => $tech->id === $leadTech->id ? 'Team Lead' : 'Teknisi Pendamping',
                ]);
                $tech->update(['technician_status' => 'On Field']);
            }

            // 4. Process Material Handover
            $matNotes = [];
            if (! empty($validated['material_ids'])) {
                foreach ($validated['material_ids'] as $index => $matId) {
                    $qty = isset($validated['material_quantities'][$index]) ? (int) $validated['material_quantities'][$index] : 1;
                    if ($qty <= 0) {
                        continue;
                    }

                    $material = Material::find($matId);
                    if ($material) {
                        $actualQty = min($material->stock, $qty);
                        $material->decrement('stock', $actualQty);

                        $repCount = MaterialOutbound::count() + 1;
                        MaterialOutbound::create([
                            'outbound_code' => 'REP-'.str_pad((string) $repCount, 3, '0', STR_PAD_LEFT),
                            'work_order_id' => $workOrder->id,
                            'ticket_id' => $ticket->id,
                            'material_id' => $material->id,
                            'spk_number' => $spkNumber,
                            'ticket_number' => $ticket->ticket_number,
                            'customer_name' => $ticket->customer_name,
                            'technician_name' => $allTechNames,
                            'material_name' => $material->name,
                            'quantity' => $actualQty,
                            'unit' => $material->unit,
                            'approved_by' => auth()->user()?->name ?? 'Admin NOC Pusat',
                            'status' => 'In Field Handover',
                        ]);

                        $matNotes[] = "{$actualQty} {$material->unit} {$material->name}";
                    }
                }
            }

            // 5. Create rich TicketLog
            $matSummary = ! empty($matNotes) ? implode(', ', $matNotes) : 'Tanpa pengeluaran material gudang.';
            $adminNotesText = $request->filled('admin_dispatch_notes') ? ' | Catatan NOC: '.$request->admin_dispatch_notes : '';

            TicketLog::create([
                'ticket_id' => $ticket->id,
                'work_order_id' => $workOrder->id,
                'user_id' => auth()->id(),
                'actor_name' => auth()->user()?->name ?? 'Admin NOC Pusat',
                'actor_role' => 'admin',
                'action' => 'SPK_DISPATCHED',
                'title' => 'SPK Diterbitkan & Tim Teknisi Ditugaskan',
                'description' => "Admin NOC menerbitkan {$spkNumber} dan menugaskan Tim Teknisi ({$allTechNames}). Status tim: Sedang bertugas melakukan perbaikan lapangan pada {$ticket->odp_code} ({$ticket->address}).",
                'notes' => "Logistik Diserahkan: {$matSummary}{$adminNotesText}",
            ]);
        });

        return redirect()->route('tiket.index')
            ->with('success', "Surat Perintah Kerja (SPK) berhasil diterbitkan dan ditugaskan ke Tim Teknisi ({$allTechNames}).");
    }

    public function printDocument(Ticket $ticket): View
    {
        $ticket->load(['workOrder.technicians', 'workOrder.materialsOutbound', 'user', 'ticketLogs']);

        return view('tiket.print', compact('ticket'));
    }
}
