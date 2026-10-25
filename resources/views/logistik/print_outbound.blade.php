@extends('layouts.print')

@section('title', 'Surat Serah Terima Material ' . $outbound->outbound_code)

@section('content')
    <!-- Document Title & Header Meta -->
    <div class="text-center mb-6">
        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight uppercase">
            SURAT BUKTI PENGELUARAN &amp; SERAH TERIMA MATERIAL (SPMB)
        </h2>
        <div class="mt-1 flex items-center justify-center gap-2 text-xs font-mono">
            <span class="text-slate-600">No. Dokumen SPMB:</span>
            <strong class="text-slate-900 font-bold bg-slate-100 px-2.5 py-0.5 rounded border border-slate-300">
                {{ $outbound->outbound_code }}
            </strong>
            <span class="text-slate-300">|</span>
            <span class="text-slate-600">Rujukan SPK:</span>
            <strong class="text-blue-700 font-bold">
                {{ $outbound->spk_number ?? 'SPK-2026-DISP' }}
            </strong>
        </div>
        <p class="text-xs text-slate-500 mt-1">
            Bukti resmi pengeluaran barang &amp; serah terima material logistik gudang Kantor Banjarmasin ke teknisi.
        </p>
    </div>

    <!-- Metadata Badges -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mb-6 text-xs">
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Tanggal Serah Terima</span>
            <strong class="font-mono text-slate-800">{{ $outbound->created_at->format('d/m/Y H:i') }} WITA</strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">No. Tiket Terkait</span>
            <strong class="font-bold text-blue-700">{{ $outbound->ticket_number }}</strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Status Serah Terima</span>
            <strong class="font-bold text-emerald-700">{{ $outbound->status }}</strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Disetujui Oleh</span>
            <strong class="font-bold text-slate-800">{{ $outbound->approved_by }}</strong>
        </div>
    </div>

    <!-- Section A: Pihak Penyerah & Penerima -->
    <div class="mb-5">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider bg-slate-100 px-3 py-1.5 rounded-md border-l-4 border-blue-600 mb-2.5">
            A. Informasi Pihak Penyerah &amp; Penerima Logistik
        </h3>
        <table class="w-full text-xs text-left">
            <tr class="border-b border-slate-100">
                <td class="py-2 text-slate-500 w-1/4">Petugas Gudang / Penyerah</td>
                <td class="py-2 font-bold text-slate-900 w-1/4">{{ $outbound->approved_by }}</td>
                <td class="py-2 text-slate-500 w-1/4">Teknisi Penerima</td>
                <td class="py-2 font-bold text-blue-700 w-1/4">{{ $outbound->technician_name }}</td>
            </tr>
            <tr class="border-b border-slate-100">
                <td class="py-2 text-slate-500">Unit / Kantor Asal</td>
                <td class="py-2 font-semibold text-slate-800">Gudang Kantor Banjarmasin</td>
                <td class="py-2 text-slate-500">Keperluan Penugasan</td>
                <td class="py-2 font-semibold text-slate-800">Perbaikan SPK {{ $outbound->spk_number }}</td>
            </tr>
            <tr class="border-b border-slate-100">
                <td class="py-2 text-slate-500">Pelanggan Tujuan</td>
                <td class="py-2 font-semibold text-slate-800" colspan="3">{{ $outbound->customer_name }}</td>
            </tr>
        </table>
    </div>

    <!-- Section B: Rincian Barang yang Diserahkan -->
    <div class="mb-8">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider bg-slate-100 px-3 py-1.5 rounded-md border-l-4 border-blue-600 mb-2.5">
            B. Rincian Material yang Dikeluarkan dari Gudang
        </h3>
        <table class="w-full text-xs text-left border border-slate-200 rounded-lg overflow-hidden">
            <thead class="bg-slate-100 text-slate-600 text-[10px] uppercase font-semibold">
                <tr>
                    <th class="p-2.5 border-b w-12 text-center">No</th>
                    <th class="p-2.5 border-b">Kode Barang / SKU</th>
                    <th class="p-2.5 border-b">Nama Deskripsi Material</th>
                    <th class="p-2.5 border-b text-center">Kuantitas</th>
                    <th class="p-2.5 border-b text-center">Satuan</th>
                    <th class="p-2.5 border-b">Kondisi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-800">
                <tr>
                    <td class="p-2.5 text-center font-mono font-bold text-slate-500">1</td>
                    <td class="p-2.5 font-mono font-semibold text-blue-700">SKU-{{ $outbound->material_id }}</td>
                    <td class="p-2.5 font-bold text-slate-900">{{ $outbound->material_name }}</td>
                    <td class="p-2.5 text-center font-mono font-black text-slate-900 text-sm">{{ $outbound->quantity }}</td>
                    <td class="p-2.5 text-center font-semibold text-slate-600">{{ $outbound->unit }}</td>
                    <td class="p-2.5">
                        <span class="text-[10px] px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">Baik / Baru</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Section C: Pakta Tanggung Jawab Lapangan -->
    <div class="mb-8 p-3 rounded-lg border border-slate-200 bg-slate-50 text-[11px] text-slate-600 space-y-1">
        <span class="font-bold text-slate-800 block">Ketentuan Pertanggungjawaban Logistik:</span>
        <p class="leading-relaxed">
            1. Teknisi penerima bertanggung jawab penuh atas material yang dibawa ke lapangan hingga pekerjaan SPK tuntas.<br>
            2. Apabila terdapat sisa material yang tidak terpasang, teknisi wajib melakukan retur sisa material ke gudang logistik Kantor Banjarmasin.<br>
            3. Kerusakan atau kehilangan material di luar prosedur operasional menjadi beban evaluasi tim teknisi bersangkutan.
        </p>
    </div>

    <!-- Sign-off Block (2 Kolom) -->
    <div class="grid grid-cols-2 gap-8 text-center text-xs pt-4 border-t border-slate-200">
        <div>
            <span class="text-slate-500 text-[10px] block">Yang Menyerahkan:</span>
            <strong class="font-bold text-slate-900 block mt-1">Admin Logistik / Kantor Banjarmasin</strong>
            <div class="h-16 flex items-center justify-center">
                <span class="text-[10px] text-slate-400 font-mono italic">[ Tanda Tangan &amp; Cap Gudang ]</span>
            </div>
            <span class="font-bold text-slate-800 block border-t border-slate-300 pt-1">
                {{ $outbound->approved_by }}
            </span>
            <span class="text-[10px] text-slate-400">Pengelola Logistik &amp; Gudang</span>
        </div>

        <div>
            <span class="text-slate-500 text-[10px] block">Yang Menerima:</span>
            <strong class="font-bold text-slate-900 block mt-1">Teknisi Lapangan</strong>
            <div class="h-16 flex items-center justify-center">
                <span class="text-[10px] text-slate-400 font-mono italic">[ Tanda Tangan Penerima ]</span>
            </div>
            <span class="font-bold text-slate-800 block border-t border-slate-300 pt-1">
                {{ $outbound->technician_name }}
            </span>
            <span class="text-[10px] text-slate-400">Tim Teknisi Operasional Lapangan</span>
        </div>
    </div>
@endsection
