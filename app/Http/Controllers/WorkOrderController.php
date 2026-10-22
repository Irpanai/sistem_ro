<?php

namespace App\Http\Controllers;

use App\Models\MaterialOutbound;
use App\Models\TicketLog;
use App\Models\User;
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

            // 4. Update Technicians Status if all active SPKs are done
            $techUserIds = $workOrder->technicians()->pluck('user_id')->toArray();
            if ($workOrder->technician_id && ! in_array($workOrder->technician_id, $techUserIds)) {
                $techUserIds[] = $workOrder->technician_id;
            }

            foreach ($techUserIds as $techId) {
                $hasActiveSpk = WorkOrder::where('status', 'In Progress')
                    ->where(function ($q) use ($techId) {
                        $q->where('technician_id', $techId)
                            ->orWhereHas('technicians', function ($tq) use ($techId) {
                                $tq->where('user_id', $techId);
                            });
                    })
                    ->exists();

                if (! $hasActiveSpk) {
                    User::where('id', $techId)->update(['technician_status' => 'Available']);
                }
            }

            // 5. Create TicketLog
            TicketLog::create([
                'ticket_id' => $workOrder->ticket_id,
                'work_order_id' => $workOrder->id,
                'user_id' => auth()->id(),
                'actor_name' => auth()->user()?->name ?? $workOrder->technician_name,
                'actor_role' => 'teknisi',
                'action' => 'TICKET_SOLVED',
                'title' => 'Perbaikan Lapangan Selesai (Solved)',
                'description' => "Tim Teknisi ({$workOrder->technician_name}) telah menyelesaikan perbaikan lapangan pada titik {$workOrder->ticket->odp_code}. Seluruh koneksi pelanggan kembali normal.",
                'notes' => $request->tech_notes,
            ]);
        });

        return back()->with('success', "Pekerjaan untuk SPK {$workOrder->spk_number} telah ditandai Selesai (Solved).");
    }
}
