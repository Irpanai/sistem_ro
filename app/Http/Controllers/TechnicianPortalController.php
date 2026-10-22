<?php

namespace App\Http\Controllers;

use App\Models\TopologyNode;
use App\Models\WorkOrder;
use Illuminate\View\View;

class TechnicianPortalController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Get work orders assigned to this technician or where this technician is in the assigned team
        $workOrders = WorkOrder::with(['ticket.ticketLogs', 'materialsOutbound', 'technicians'])
            ->where(function ($q) use ($user) {
                if ($user) {
                    $q->where('technician_id', $user->id)
                        ->orWhere('technician_name', 'like', "%{$user->name}%")
                        ->orWhereHas('technicians', function ($tq) use ($user) {
                            $tq->where('user_id', $user->id);
                        });
                }
            })
            ->orWhere('technician_name', 'like', '%Rizky Ramadhan%')
            ->orderByDesc('created_at')
            ->get();

        $activeCount = $workOrders->where('status', 'In Progress')->count();
        $solvedCount = $workOrders->where('status', 'Solved')->count();
        $allNodes = TopologyNode::all();

        return view('teknisi.portal', compact('workOrders', 'activeCount', 'solvedCount', 'allNodes'));
    }

    public function history(): View
    {
        $user = auth()->user();

        // Get all completed work orders for this technician or where technician was in the team
        $workOrders = WorkOrder::with(['ticket.ticketLogs', 'materialsOutbound', 'technicians'])
            ->where('status', 'Solved')
            ->where(function ($q) use ($user) {
                if ($user) {
                    $q->where('technician_id', $user->id)
                        ->orWhere('technician_name', 'like', "%{$user->name}%")
                        ->orWhereHas('technicians', function ($tq) use ($user) {
                            $tq->where('user_id', $user->id);
                        });
                }
            })
            ->orWhere(function ($q) {
                $q->where('status', 'Solved')
                    ->where('technician_name', 'like', '%Rizky Ramadhan%');
            })
            ->orderByDesc('resolved_at')
            ->get();

        $totalSolved = $workOrders->count();
        $totalMaterialsUsed = $workOrders->sum(fn ($wo) => $wo->materialsOutbound->sum('quantity'));
        $allNodes = TopologyNode::all();

        return view('teknisi.riwayat', compact('workOrders', 'totalSolved', 'totalMaterialsUsed', 'allNodes'));
    }
}
