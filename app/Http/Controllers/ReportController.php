<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialOutbound;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $currentTab = $request->input('tab', 'bulanan'); // 'bulanan' or 'stok_opname'
        $selectedMonth = $request->input('month', 'Semua');
        $selectedYear = $request->input('year', date('Y'));

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $years = [2026, 2025, 2024];

        // 1. Monthly Material Outbound Query
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
            $query->where('technician_name', 'like', "%{$request->technician}%");
        }

        if ($selectedMonth !== 'Semua') {
            $query->whereMonth('created_at', (int) $selectedMonth);
        }

        if ($selectedYear !== 'Semua') {
            $query->whereYear('created_at', (int) $selectedYear);
        }

        // Metrics filtered by period
        $metricQuery = clone $query;
        $totalTransactions = $metricQuery->count();
        $totalItemsOut = (int) (clone $query)->sum('quantity');
        $totalDropcoreRoll = (int) (clone $query)->where('material_name', 'like', '%Dropcore%')->sum('quantity');
        $totalModemUnits = (int) (clone $query)->where('material_name', 'like', '%Modem%')->sum('quantity');

        $reports = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $technicians = User::where('role', 'teknisi')->orderBy('name')->get();

        // 2. Stok Opname Audit Data
        $materials = Material::query();
        if ($request->filled('search_opname')) {
            $sOpname = $request->search_opname;
            $materials->where(function ($q) use ($sOpname) {
                $q->where('name', 'like', "%{$sOpname}%")
                    ->orWhere('code', 'like', "%{$sOpname}%")
                    ->orWhere('category', 'like', "%{$sOpname}%")
                    ->orWhere('location', 'like', "%{$sOpname}%");
            });
        }
        $opnameItems = $materials->orderBy('category')->orderBy('name')->get();

        $totalOpnameSkus = $opnameItems->count();
        $totalSystemStock = $opnameItems->sum('stock');
        $lowStockOpnameCount = $opnameItems->where(function ($m) {
            return $m->stock <= $m->min_stock;
        })->count();
        $healthyStockOpnameCount = $totalOpnameSkus - $lowStockOpnameCount;

        return view('laporan.index', compact(
            'reports',
            'technicians',
            'totalTransactions',
            'totalItemsOut',
            'totalDropcoreRoll',
            'totalModemUnits',
            'currentTab',
            'selectedMonth',
            'selectedYear',
            'months',
            'years',
            'opnameItems',
            'totalOpnameSkus',
            'totalSystemStock',
            'lowStockOpnameCount',
            'healthyStockOpnameCount'
        ));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $tab = $request->input('tab', 'bulanan');

        if ($tab === 'stok_opname') {
            $fileName = 'Laporan_Stok_Opname_Gudang_Banjarmasin_'.date('Y-m-d').'.csv';

            return response()->streamDownload(function () {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Kode SKU', 'Nama Material', 'Kategori', 'Lokasi Rak Gudang', 'Stok Tercatat Sistem', 'Batas Buffer Minimum', 'Satuan', 'Status Kondisi', 'Terakhir Diaudit']);

                Material::orderBy('category')->orderBy('name')->chunk(100, function ($rows) use ($handle) {
                    foreach ($rows as $row) {
                        $isLow = $row->stock <= $row->min_stock;
                        fputcsv($handle, [
                            $row->code,
                            $row->name,
                            $row->category,
                            $row->location ?? 'Gudang Banjarmasin',
                            $row->stock,
                            $row->min_stock,
                            $row->unit,
                            $isLow ? 'Stok Menipis (Buffer)' : 'Stok Aman',
                            date('Y-m-d H:i'),
                        ]);
                    }
                });

                fclose($handle);
            }, $fileName, [
                'Content-Type' => 'text/csv',
            ]);
        }

        $fileName = 'Laporan_Pengeluaran_Material_Bulanan_'.date('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($request) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tanggal', 'Kode Laporan', 'No. Tiket', 'No. SPK', 'Nama Pelanggan', 'Teknisi Lapangan', 'Nama Material', 'Jumlah', 'Satuan', 'Disetujui Oleh', 'Status']);

            $query = MaterialOutbound::query();
            if ($request->filled('month') && $request->month !== 'Semua') {
                $query->whereMonth('created_at', (int) $request->month);
            }
            if ($request->filled('year') && $request->year !== 'Semua') {
                $query->whereYear('created_at', (int) $request->year);
            }
            if ($request->filled('technician') && $request->technician !== 'Semua') {
                $query->where('technician_name', 'like', "%{$request->technician}%");
            }

            $query->orderByDesc('created_at')->chunk(100, function ($rows) use ($handle) {
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

    public function printReport(Request $request): View
    {
        $tab = $request->input('tab', 'bulanan');

        if ($tab === 'stok_opname') {
            $items = Material::orderBy('category')->orderBy('name')->get();
            $totalCount = $items->count();
            $totalQty = $items->sum('stock');
            $periodLabel = 'Bulan '.date('F Y').' (Audit Rekonsiliasi Gudang)';
        } else {
            $query = MaterialOutbound::query();
            if ($request->filled('month') && $request->month !== 'Semua') {
                $query->whereMonth('created_at', (int) $request->month);
            }
            if ($request->filled('year') && $request->year !== 'Semua') {
                $query->whereYear('created_at', (int) $request->year);
            }
            if ($request->filled('technician') && $request->technician !== 'Semua') {
                $query->where('technician_name', 'like', "%{$request->technician}%");
            }

            $items = $query->orderByDesc('created_at')->get();
            $totalCount = $items->count();
            $totalQty = $items->sum('quantity');
            $monthNum = (int) $request->input('month', date('n'));
            $months = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
            $monthName = $months[$monthNum] ?? 'Oktober';
            $periodLabel = 'Bulan '.($request->input('month') === 'Semua' ? 'Semua Bulan' : $monthName).' '.$request->input('year', date('Y'));
        }

        return view('laporan.print', compact('tab', 'items', 'totalCount', 'totalQty', 'periodLabel'));
    }
}
