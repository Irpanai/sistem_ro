<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TopologyNode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Calculations for Summary Cards
        $totalActiveTickets = Ticket::whereIn('status', ['Pending', 'In Progress'])->count();
        $pendingTicketsCount = Ticket::where('status', 'Pending')->count();
        $inProgressTicketsCount = Ticket::where('status', 'In Progress')->count();

        $outageIncidents = Ticket::where('type', 'OUTAGE')->whereIn('status', ['Pending', 'In Progress'])->count();
        $isolirIncidents = Ticket::where('type', 'ISOLIR')->whereIn('status', ['Pending', 'In Progress'])->count();
        $solvedTickets = Ticket::where('status', 'Solved')->count();

        // 2. Topology Diagnostic Query with filter
        $query = TopologyNode::query();
        if ($request->filled('area') && $request->area !== 'Semua') {
            $query->where('area', $request->area);
        }
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }
        $topologyNodes = $query->orderBy('type')->orderBy('name')->get();

        $totalNodesCount = TopologyNode::count();
        $onlineNodesCount = TopologyNode::where('status', 'Online')->count();
        $offlineNodesCount = TopologyNode::where('status', 'Offline')->count();

        // 3. Outage vs Isolir Statistics (All Time)
        $totalOutage = Ticket::where('type', 'OUTAGE')->count();
        $totalIsolir = Ticket::where('type', 'ISOLIR')->count();
        $totalTickets = $totalOutage + $totalIsolir;

        $outagePercent = $totalTickets > 0 ? round(($totalOutage / $totalTickets) * 100, 1) : 0;
        $isolirPercent = $totalTickets > 0 ? round(($totalIsolir / $totalTickets) * 100, 1) : 0;

        // 4. Technician Performance Leaderboard
        $technicians = User::where('role', 'teknisi')
            ->withCount(['workOrders as solved_count' => function ($q) {
                $q->where('status', 'Solved');
            }])
            ->orderByDesc('solved_count')
            ->get();

        return view('dashboard.index', compact(
            'totalActiveTickets',
            'pendingTicketsCount',
            'inProgressTicketsCount',
            'outageIncidents',
            'isolirIncidents',
            'solvedTickets',
            'topologyNodes',
            'totalNodesCount',
            'onlineNodesCount',
            'offlineNodesCount',
            'totalOutage',
            'totalIsolir',
            'outagePercent',
            'isolirPercent',
            'technicians'
        ));
    }
}
