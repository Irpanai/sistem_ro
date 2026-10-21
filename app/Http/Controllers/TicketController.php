<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialOutbound;
use App\Models\Ticket;
use App\Models\TopologyNode;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $query = Ticket::with(['workOrder', 'user']);

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

        return redirect()->route('tiket.index')
            ->with('success', "Keluhan pelanggan baru berhasil didaftarkan dengan ID: {$ticket->ticket_number}.");
    }

    public function dispatchSpk(Request $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'classification_type' => 'required|in:ISOLIR,OUTAGE',
            'technician_id' => 'required|exists:users,id',
            'material_ids' => 'nullable|array',
            'material_ids.*' => 'exists:materials,id',
            'material_quantities' => 'nullable|array',
        ]);

        $technician = User::findOrFail($validated['technician_id']);

        DB::transaction(function () use ($ticket, $validated, $technician) {
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
                'technician_id' => $technician->id,
                'technician_name' => $technician->name,
                'technician_phone' => $technician->phone,
                'status' => 'In Progress',
                'dispatched_at' => now(),
            ]);

            // 3. Process Material Handover
            if (! empty($validated['material_ids'])) {
                foreach ($validated['material_ids'] as $index => $matId) {
                    $qty = isset($validated['material_quantities'][$index]) ? (int) $validated['material_quantities'][$index] : 1;
                    if ($qty <= 0) {
                        continue;
                    }

                    $material = Material::find($matId);
                    if ($material) {
                        // Deduct stock
                        $actualQty = min($material->stock, $qty);
                        $material->decrement('stock', $actualQty);

                        // Record Outbound
                        $repCount = MaterialOutbound::count() + 1;
                        MaterialOutbound::create([
                            'outbound_code' => 'REP-'.str_pad((string) $repCount, 3, '0', STR_PAD_LEFT),
                            'work_order_id' => $workOrder->id,
                            'ticket_id' => $ticket->id,
                            'material_id' => $material->id,
                            'spk_number' => $spkNumber,
                            'ticket_number' => $ticket->ticket_number,
                            'customer_name' => $ticket->customer_name,
                            'technician_name' => $technician->name,
                            'material_name' => $material->name,
                            'quantity' => $actualQty,
                            'unit' => $material->unit,
                            'approved_by' => auth()->user()?->name ?? 'Admin NOC',
                            'status' => 'In Field Handover',
                        ]);
                    }
                }
            }

            // 4. Update technician status
            $technician->update(['technician_status' => 'On Field']);
        });

        return redirect()->route('tiket.index')
            ->with('success', "Surat Perintah Kerja (SPK) berhasil diterbitkan dan ditugaskan ke {$technician->name}.");
    }
}
