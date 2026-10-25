@extends('layouts.print')

@section('title', 'Kartu Kendali Material ' . $material->code)

@section('content')
    <!-- Document Title & Header Meta -->
    <div class="text-center mb-6">
        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight uppercase">
            KARTU KENDALI INVENTARIS &amp; STOK GUDANG LOGISTIK
        </h2>
        <div class="mt-1 flex items-center justify-center gap-2 text-xs font-mono">
            <span class="text-slate-600">Kode SKU Material:</span>
            <strong class="text-blue-700 font-bold bg-blue-50 px-2.5 py-0.5 rounded border border-blue-200">
                {{ $material->code }}
            </strong>
        </div>
        <p class="text-xs text-slate-500 mt-1">
            Kartu kontrol stok fisik dan identitas material logistik jaringan Kantor Banjarmasin.
        </p>
    </div>

    <!-- Metadata Badges -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mb-6 text-xs">
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Kategori Material</span>
            <strong class="font-bold text-slate-900">{{ $material->category }}</strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Lokasi Rak Gudang</span>
            <strong class="font-bold text-blue-700">{{ $material->location ?? 'Gudang Utama Banjarmasin' }}</strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Stok Fisik Tersedia</span>
            <strong class="font-mono font-bold text-lg {{ $material->stock <= $material->min_stock ? 'text-rose-700' : 'text-emerald-700' }}">
                {{ $material->stock }} {{ $material->unit }}
            </strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Status Ketersediaan</span>
            <strong class="font-bold {{ $material->stock <= $material->min_stock ? 'text-amber-700' : 'text-emerald-700' }}">
                {{ $material->stock <= $material->min_stock ? 'Menipis (Buffer)' : 'Stok Aman' }}
            </strong>
        </div>
    </div>

    <!-- Section A: Rincian Spesifikasi SKU -->
    <div class="mb-5">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider bg-slate-100 px-3 py-1.5 rounded-md border-l-4 border-blue-600 mb-2.5">
            A. Rincian Spesifikasi &amp; Identitas SKU
        </h3>
        <table class="w-full text-xs text-left">
            <tr class="border-b border-slate-100">
                <td class="py-2 text-slate-500 w-1/4">Nama Material / Barang</td>
                <td class="py-2 font-bold text-slate-900 w-1/4">{{ $material->name }}</td>
                <td class="py-2 text-slate-500 w-1/4">Kode Barcode / SKU</td>
                <td class="py-2 font-mono font-bold text-blue-700 w-1/4">{{ $material->code }}</td>
            </tr>
            <tr class="border-b border-slate-100">
                <td class="py-2 text-slate-500">Satuan Satuan Pengeluaran</td>
                <td class="py-2 font-semibold text-slate-800">{{ $material->unit }}</td>
                <td class="py-2 text-slate-500">Batas Minimum Buffer</td>
                <td class="py-2 font-mono text-slate-800">{{ $material->min_stock }} {{ $material->unit }}</td>
            </tr>
            <tr class="border-b border-slate-100">
                <td class="py-2 text-slate-500">Estimasi Biaya Satuan</td>
                <td class="py-2 font-mono font-semibold text-slate-800">{{ $material->price ? 'Rp ' . number_format((float)$material->price, 0, ',', '.') : 'Sesuai Pengadaan Pusat' }}</td>
                <td class="py-2 text-slate-500">Gudang Penyimpanan</td>
                <td class="py-2 font-semibold text-slate-800">Sentral Banjarmasin Selatan</td>
            </tr>
        </table>
    </div>

    <!-- Section B: Petunjuk Penyimpanan & Penanganan -->
    <div class="mb-8 p-3 rounded-lg border border-slate-200 bg-slate-50 text-[11px] text-slate-600 space-y-1">
        <span class="font-bold text-slate-800 block">SOP Penyimpanan &amp; Penyerahan Barang:</span>
        <p class="leading-relaxed">
            1. Seluruh pengeluaran material wajib disertai rujukan Nomor SPK resmi dari Admin Kantor Banjarmasin.<br>
            2. Material optik sensitif (patchcord, pigtail, ONT) wajib disimpan dalam ruangan berpendingin dan bebas debu.<br>
            3. Stok opname fisik dicocokkan secara berkala setiap akhir bulan.
        </p>
    </div>

    <!-- Sign-off Block (2 Kolom) -->
    <div class="grid grid-cols-2 gap-8 text-center text-xs pt-4 border-t border-slate-200">
        <div>
            <span class="text-slate-500 text-[10px] block">Penanggung Jawab Gudang:</span>
            <strong class="font-bold text-slate-900 block mt-1">Petugas Inventaris Gudang</strong>
            <div class="h-16 flex items-center justify-center">
                <span class="text-[10px] text-slate-400 font-mono italic">[ Tanda Tangan &amp; Cap ]</span>
            </div>
            <span class="font-bold text-slate-800 block border-t border-slate-300 pt-1">
                Admin Gudang Logistik BJM
            </span>
            <span class="text-[10px] text-slate-400">Unit Pengelolaan Logistik</span>
        </div>

        <div>
            <span class="text-slate-500 text-[10px] block">Mengetahui:</span>
            <strong class="font-bold text-slate-900 block mt-1">Admin Kantor (Banjarmasin)</strong>
            <div class="h-16 flex items-center justify-center">
                <span class="text-[10px] text-slate-400 font-mono italic">[ Tanda Tangan &amp; Cap ]</span>
            </div>
            <span class="font-bold text-slate-800 block border-t border-slate-300 pt-1">
                {{ auth()->user()?->name ?? 'Admin Kantor Banjarmasin' }}
            </span>
            <span class="text-[10px] text-slate-400">Kepala Administrasi Kantor</span>
        </div>
    </div>
@endsection
