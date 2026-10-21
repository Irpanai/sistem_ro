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

        // Get work orders assigned to this technician or default technician
        $workOrders = WorkOrder::with(['ticket', 'materialsOutbound'])
            ->where(function ($q) use ($user) {
                if ($user) {
                    $q->where('technician_id', $user->id)
                        ->orWhere('technician_name', $user->name);
                }
            })
            ->orWhere('technician_name', 'Rizky Ramadhan')
            ->orderByDesc('created_at')
            ->get();

        $activeCount = $workOrders->where('status', 'In Progress')->count();
        $solvedCount = $workOrders->where('status', 'Solved')->count();
        $allNodes = TopologyNode::all();

        return view('teknisi.portal', compact('workOrders', 'activeCount', 'solvedCount', 'allNodes'));
    }
}
