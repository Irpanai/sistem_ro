<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketLog;
use App\Models\TopologyNode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientPortalController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Get tickets for this client or matching name
        $tickets = Ticket::with(['workOrder.technicians', 'ticketLogs'])
            ->where(function ($q) use ($user) {
                if ($user) {
                    $q->where('user_id', $user->id)
                        ->orWhere('customer_name', $user->name);
                }
            })
            ->orWhere('customer_name', 'Haji Sulaiman')
            ->orderByDesc('created_at')
            ->get();

        $activeTicketsCount = $tickets->where('status', '!=', 'Solved')->count();
        $solvedTicketsCount = $tickets->where('status', 'Solved')->count();

        $topologyNodes = TopologyNode::where('status', 'Online')->orderBy('name')->get();
        $allNodes = TopologyNode::all();

        return view('klien.portal', compact('tickets', 'activeTicketsCount', 'solvedTicketsCount', 'topologyNodes', 'allNodes'));
    }

    public function history(): View
    {
        $user = auth()->user();

        $tickets = Ticket::with(['workOrder.technicians', 'ticketLogs', 'workOrder.materialsOutbound'])
            ->where(function ($q) use ($user) {
                if ($user) {
                    $q->where('user_id', $user->id)
                        ->orWhere('customer_name', $user->name);
                }
            })
            ->orWhere('customer_name', 'Haji Sulaiman')
            ->orderByDesc('created_at')
            ->get();

        $totalTicketsCount = $tickets->count();
        $solvedTicketsCount = $tickets->where('status', 'Solved')->count();
        $activeTicketsCount = $tickets->where('status', '!=', 'Solved')->count();

        $allNodes = TopologyNode::all();

        return view('klien.riwayat', compact('tickets', 'totalTicketsCount', 'solvedTicketsCount', 'activeTicketsCount', 'allNodes'));
    }

    public function storeComplaint(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'issue_category' => 'required|string',
            'detail' => 'nullable|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'odp_code' => 'required|string',
        ]);

        $user = auth()->user();
        $count = Ticket::count() + 1;
        $ticketNumber = 'TKT-2026-'.str_pad((string) $count, 3, '0', STR_PAD_LEFT);

        $issueText = $validated['issue_category'].': '.($validated['detail'] ?: 'Koneksi terputus dan lampu indikator modem bermasalah.');

        $odp = TopologyNode::where('name', $validated['odp_code'])->first();
        $isOdpOffline = $odp && $odp->status === 'Offline';

        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'user_id' => $user?->id,
            'customer_name' => $user?->name ?? 'Pelanggan',
            'customer_phone' => $validated['phone'],
            'address' => $validated['address'],
            'area' => 'Banjarmasin',
            'odp_code' => $validated['odp_code'],
            'issue' => $issueText,
            'status' => 'Pending',
            'type' => $isOdpOffline ? 'OUTAGE' : 'ISOLIR',
            'priority' => 'High',
            'analysis_details' => 'Laporan masuk dari Portal Mandiri Klien. Menunggu verifikasi tim NOC.',
            'reported_at' => now(),
        ]);

        // Record initial TicketLog
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user?->id,
            'actor_name' => $ticket->customer_name,
            'actor_role' => 'klien',
            'action' => 'TICKET_CREATED',
            'title' => 'Laporan Dibuat oleh Pelanggan',
            'description' => "Pelanggan ({$ticket->customer_name}) melaporkan gangguan melalui portal mandiri. Gejala: {$ticket->issue}. Titik ODP: {$ticket->odp_code}.",
            'notes' => 'Nomor WhatsApp Pelanggan: '.$ticket->customer_phone,
        ]);

        return redirect()->route('klien.portal')
            ->with('success', "Keluhan Anda berhasil dikirim dengan Nomor Tiket: {$ticketNumber}. Tim NOC sedang menganalisis jaringan Anda.");
    }
}
