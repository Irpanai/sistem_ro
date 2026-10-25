<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TopologyNode;
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

        // 2. Topology Diagnostic Query with filter (OLT excluded from Dashboard)
        $query = TopologyNode::query()->where('type', 'ODP');
        if ($request->filled('area') && $request->area !== 'Semua') {
            $query->where('area', 'like', "%{$request->area}%");
        }
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }
        $topologyNodes = $query->orderBy('name')->get();

        $totalNodesCount = TopologyNode::where('type', 'ODP')->count();
        $onlineNodesCount = TopologyNode::where('type', 'ODP')->where('status', 'Online')->count();
        $offlineNodesCount = TopologyNode::where('type', 'ODP')->where('status', 'Offline')->count();

        // 3. Outage vs Isolir Statistics (All Time)
        $totalOutage = Ticket::where('type', 'OUTAGE')->count();
        $totalIsolir = Ticket::where('type', 'ISOLIR')->count();
        $totalTickets = $totalOutage + $totalIsolir;

        $outagePercent = $totalTickets > 0 ? round(($totalOutage / $totalTickets) * 100, 1) : 0;
        $isolirPercent = $totalTickets > 0 ? round(($totalIsolir / $totalTickets) * 100, 1) : 0;

        // 4. Team SLA & Field Operations Performance (SLA Khusus Tim Lapangan)
        $teamsPerformance = collect([
            [
                'code' => 'TEAM-ALP',
                'name' => 'Tim Alpha',
                'specialty' => 'Splicing & Feeder Utama',
                'lead' => 'Rizky Ramadhan',
                'members' => ['Ahmad Faisal', 'Dedi Pratama'],
                'coverage' => 'Banjarmasin Utara & Banjarmasin Timur',
                'target_hours' => 3.0,
                'avg_hours' => 1.8,
                'sla_compliance' => 98.4,
                'solved_tickets' => 42 + $solvedTickets,
                'status' => 'Siaga',
                'status_color' => 'emerald',
                'rating' => 4.9,
            ],
            [
                'code' => 'TEAM-BET',
                'name' => 'Tim Beta',
                'specialty' => 'Distribusi ODP & Dropcore',
                'lead' => 'Muhammad Noor',
                'members' => ['Budi Santoso', 'Hendra Wijaya'],
                'coverage' => 'Banjarmasin Barat & Banjarmasin Selatan',
                'target_hours' => 3.0,
                'avg_hours' => 2.1,
                'sla_compliance' => 96.8,
                'solved_tickets' => 35,
                'status' => 'Di Lapangan',
                'status_color' => 'blue',
                'rating' => 4.8,
            ],
            [
                'code' => 'TEAM-CHA',
                'name' => 'Tim Charlie',
                'specialty' => 'Quick Response CPE Pelanggan',
                'lead' => 'Irfan Saputra',
                'members' => ['Fajar Siddiq', 'M. Ridho'],
                'coverage' => 'Banjarmasin Tengah & Pusat Kota',
                'target_hours' => 2.5,
                'avg_hours' => 1.4,
                'sla_compliance' => 99.2,
                'solved_tickets' => 29,
                'status' => 'Siaga',
                'status_color' => 'emerald',
                'rating' => 5.0,
            ],
        ]);

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
            'teamsPerformance'
        ));
    }
}
