<?php

namespace App\Http\Controllers;

use App\Models\MaterialOutbound;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = MaterialOutbound::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('outbound_code', 'like', "%{$search}%")
                    ->orWhere('ticket_number', 'like', "%{$search}%")
                    ->orWhere('spk_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('technician_name', 'like', "%{$search}%")
                    ->orWhere('material_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('technician') && $request->technician !== 'Semua') {
            $query->where('technician_name', $request->technician);
        }

        $reports = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $technicians = User::where('role', 'teknisi')->orderBy('name')->get();

        $totalTransactions = MaterialOutbound::count();
        $totalItemsOut = (int) MaterialOutbound::sum('quantity');
        $totalDropcoreRoll = (int) MaterialOutbound::where('material_name', 'like', '%Dropcore%')->sum('quantity');
        $totalModemUnits = (int) MaterialOutbound::where('material_name', 'like', '%Modem%')->sum('quantity');

        return view('laporan.index', compact(
            'reports',
            'technicians',
            'totalTransactions',
            'totalItemsOut',
            'totalDropcoreRoll',
            'totalModemUnits'
        ));
    }

    public function exportCsv(): StreamedResponse
    {
        $fileName = 'Laporan_Pengeluaran_Material_SIPKJ_'.date('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tanggal', 'Kode Laporan', 'No. Tiket', 'No. SPK', 'Nama Pelanggan', 'Teknisi Lapangan', 'Nama Material', 'Jumlah', 'Satuan', 'Disetujui Oleh', 'Status']);

            MaterialOutbound::orderByDesc('created_at')->chunk(100, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->created_at->format('Y-m-d H:i'),
                        $row->outbound_code,
                        $row->ticket_number,
                        $row->spk_number,
                        $row->customer_name,
                        $row->technician_name,
                        $row->material_name,
                        $row->quantity,
                        $row->unit,
                        $row->approved_by,
                        $row->status,
                    ]);
                }
            });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
