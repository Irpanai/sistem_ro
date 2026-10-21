<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
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
        $tickets = Ticket::with('workOrder')
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

        Ticket::create([
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
            'analysis_details' => 'Laporan masuk dari Portal Klien. Menunggu analisis NOC.',
            'reported_at' => now(),
        ]);

        return redirect()->route('klien.portal')
            ->with('success', "Keluhan Anda berhasil dikirim dengan Nomor Tiket: {$ticketNumber}. Tim NOC sedang menganalisis jaringan Anda.");
    }
}
