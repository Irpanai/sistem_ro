<?php

namespace App\Http\Controllers;

use App\Models\MaterialOutbound;
use App\Models\WorkOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkOrderController extends Controller
{
    public function resolve(Request $request, WorkOrder $workOrder): RedirectResponse
    {
        $request->validate([
            'tech_notes' => 'required|string',
        ]);

        DB::transaction(function () use ($workOrder, $request) {
            $now = now();

            // 1. Update Work Order
            $workOrder->update([
                'status' => 'Solved',
                'tech_notes' => $request->tech_notes,
                'resolved_at' => $now,
            ]);

            // 2. Update Ticket
            $workOrder->ticket->update([
                'status' => 'Solved',
            ]);

            // 3. Update Material Outbounds
            MaterialOutbound::where('work_order_id', $workOrder->id)
                ->update(['status' => 'Used & Completed']);

            // 4. Update Technician Status if all active SPKs are done
            if ($workOrder->technician_id) {
                $hasActiveSpk = WorkOrder::where('technician_id', $workOrder->technician_id)
                    ->where('status', 'In Progress')
                    ->exists();

                if (! $hasActiveSpk) {
                    $workOrder->technician->update(['technician_status' => 'Available']);
                }
            }
        });

        return back()->with('success', "Pekerjaan untuk SPK {$workOrder->spk_number} telah ditandai Selesai (Solved).");
    }
}
