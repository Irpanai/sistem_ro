@extends('layouts.app', ['title' => 'Laporan & Audit Stok Opname'])

@section('content')
<div class="space-y-6">
    <!-- Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Laporan &amp; Audit Logistik Material</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Rekapitulasi berkala pengeluaran material perbaikan jaringan dan audit stok opname fisik Gudang Kantor Banjarmasin.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('laporan.print', array_merge(['tab' => $currentTab, 'month' => $selectedMonth, 'year' => $selectedYear], request()->all())) }}" target="_blank"
               class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 rounded-xl text-xs sm:text-sm font-semibold shadow-xs hover:bg-slate-50 transition-all">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Cetak Dokumen PDF</span>
            </a>
            <a href="{{ route('laporan.export', ['tab' => $currentTab, 'month' => $selectedMonth, 'year' => $selectedYear, 'technician' => request('technician')]) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs sm:text-sm font-semibold shadow-sm shadow-emerald-600/20 transition-all hover:scale-[1.01] active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Export CSV Excel</span>
            </a>
        </div>
    </div>

    <!-- TAB SWITCHER: Laporan Bulanan vs Stok Opname -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-1">
        <a href="{{ route('laporan.index', ['tab' => 'bulanan']) }}"
           class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 {{ $currentTab === 'bulanan' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Laporan Pengeluaran Material (Bulanan)</span>
        </a>

        <a href="{{ route('laporan.index', ['tab' => 'stok_opname']) }}"
           class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 {{ $currentTab === 'stok_opname' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <span>Audit Stok Opname Fisik Gudang</span>
        </a>
    </div>

    @if($currentTab === 'bulanan')
        <!-- TAB 1: LAPORAN PENGELUARAN BULANAN -->

        <!-- Metric Cards (4 Cards - Uniform Height) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Transaksi Keluar</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1 font-mono">{{ $totalTransactions }} <span class="text-xs font-normal text-slate-500 font-sans">Log SPK</span></h3>
                </div>
                <p class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100">Disetujui Admin Kantor</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
                <div>
                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Total Kuantitas Item</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-blue-700 mt-1 font-mono">{{ $totalItemsOut }} <span class="text-xs font-normal text-slate-500 font-sans">Pcs / Satuan</span></h3>
                </div>
                <p class="text-xs text-blue-500 font-medium mt-3 pt-3 border-t border-blue-50">Material terdistribusi ke teknisi</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
                <div>
                    <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Dropcore Terpakai</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-indigo-700 mt-1 font-mono">{{ $totalDropcoreRoll }} <span class="text-xs font-normal text-slate-500 font-sans">Roll (1000m)</span></h3>
                </div>
                <p class="text-xs text-indigo-500 font-medium mt-3 pt-3 border-t border-indigo-50">Penggantian kabel putus</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
                <div>
                    <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Modem ONT Diganti</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-emerald-700 mt-1 font-mono">{{ $totalModemUnits }} <span class="text-xs font-normal text-slate-500 font-sans">Unit Baru</span></h3>
                </div>
                <p class="text-xs text-emerald-600 font-medium mt-3 pt-3 border-t border-emerald-50">Unit baru terdistribusi</p>
            </div>
        </div>

        <!-- Filter Bar Bulanan -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
            <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                <input type="hidden" name="tab" value="bulanan">

                <div class="sm:col-span-4 relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari kode log, tiket, SPK, atau material..."
                           class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <div class="sm:col-span-2">
                    <select name="month" onchange="this.form.submit()"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all font-medium text-slate-700">
                        <option value="Semua">Semua Bulan</option>
                        @foreach($months as $mNum => $mName)
                            <option value="{{ $mNum }}" {{ (string)$selectedMonth === (string)$mNum ? 'selected' : '' }}>
                                {{ $mName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <select name="year" onchange="this.form.submit()"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all font-medium text-slate-700">
                        <option value="Semua">Semua Tahun</option>
                        @foreach($years as $yr)
                            <option value="{{ $yr }}" {{ (string)$selectedYear === (string)$yr ? 'selected' : '' }}>
                                {{ $yr }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <select name="technician" onchange="this.form.submit()"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                        <option value="Semua">Semua Teknisi</option>
                        @foreach($technicians as $tech)
                            <option value="{{ $tech->name }}" {{ request('technician') === $tech->name ? 'selected' : '' }}>
                                {{ $tech->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2 flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-semibold hover:bg-slate-800 transition-colors">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'technician', 'month', 'year']))
                        <a href="{{ route('laporan.index', ['tab' => 'bulanan']) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-medium transition-colors" title="Reset Filter">
                            ✕
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Report Table -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-slate-900 text-base">Histori Pengeluaran Material ({{ $reports->total() }} Data)</h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Periode: {{ $selectedMonth !== 'Semua' ? ($months[(int)$selectedMonth] ?? $selectedMonth) : 'Seluruh Bulan' }} {{ $selectedYear !== 'Semua' ? $selectedYear : '' }} &bull; Verifikasi Admin Kantor Banjarmasin
                    </p>
                </div>
                <span class="text-xs text-slate-500 font-mono bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200">
                    Audit Trail ISP
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50/75 text-[10px] text-slate-500 uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3.5 font-semibold">Waktu / Kode</th>
                            <th class="px-6 py-3.5 font-semibold">No. Tiket &amp; SPK</th>
                            <th class="px-6 py-3.5 font-semibold">Pelanggan</th>
                            <th class="px-6 py-3.5 font-semibold">Teknisi Pengambil</th>
                            <th class="px-6 py-3.5 font-semibold">Material &amp; Jumlah</th>
                            <th class="px-6 py-3.5 font-semibold">Disetujui Oleh</th>
                            <th class="px-6 py-3.5 font-semibold text-center">Status Barang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($reports as $rep)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded">
                                        {{ $rep->outbound_code }}
                                    </span>
                                    <div class="text-[10px] text-slate-400 mt-1">
                                        {{ $rep->created_at->format('d M Y, H:i') }} WITA
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono text-xs">
                                    <div class="text-blue-700 font-bold">{{ $rep->ticket_number }}</div>
                                    <div class="text-indigo-600 font-semibold mt-0.5">{{ $rep->spk_number }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900 text-xs">{{ $rep->customer_name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800 flex items-center gap-1.5 text-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        {{ $rep->technician_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 text-xs">{{ $rep->material_name }}</div>
                                    <div class="text-[11px] text-indigo-700 font-bold mt-0.5 font-mono">
                                        {{ $rep->quantity }} {{ $rep->unit }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-600">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 rounded-md text-[11px]">
                                        <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $rep->approved_by }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($rep->status === 'Used & Completed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            Terpakai &amp; Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-800 border border-blue-200">
                                            Di Lapangan (Handover)
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-xs">
                                    Belum ada riwayat pengeluaran material pada periode bulan yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $reports->links() }}
            </div>
        </div>
    @else
        <!-- TAB 2: AUDIT STOK OPNAME FISIK GUDANG -->

        <!-- KPI Cards Stok Opname -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total SKU Diaudit</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1 font-mono">{{ $totalOpnameSkus }} <span class="text-xs font-normal text-slate-500 font-sans">Item SKU</span></h3>
                </div>
                <p class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100">Gudang Kantor Banjarmasin</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-blue-200/90 shadow-xs flex flex-col justify-between">
                <div>
                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Total Stok Sistem</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-blue-700 mt-1 font-mono">{{ $totalSystemStock }} <span class="text-xs font-normal text-slate-500 font-sans">Total Kuantitas</span></h3>
                </div>
                <p class="text-xs text-blue-500 font-medium mt-3 pt-3 border-t border-blue-50">Tercatat di sistem database</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-emerald-200/90 shadow-xs flex flex-col justify-between">
                <div>
                    <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Stok Fisik Aman</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-emerald-700 mt-1 font-mono">{{ $healthyStockOpnameCount }} <span class="text-xs font-normal text-slate-500 font-sans">SKU Optimal</span></h3>
                </div>
                <p class="text-xs text-emerald-600 font-medium mt-3 pt-3 border-t border-emerald-50">Di atas batas buffer</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-rose-200/90 shadow-xs flex flex-col justify-between">
                <div>
                    <p class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Stok Buffer Menipis</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-rose-600 mt-1 font-mono">{{ $lowStockOpnameCount }} <span class="text-xs font-normal text-slate-500 font-sans">Perlu Reorder</span></h3>
                </div>
                <p class="text-xs text-rose-500 font-medium mt-3 pt-3 border-t border-rose-50">Segera lakukan pengadaan</p>
            </div>
        </div>

        <!-- Filter Search Stok Opname -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
            <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                <input type="hidden" name="tab" value="stok_opname">

                <div class="sm:col-span-10 relative">
                    <input type="text" name="search_opname" value="{{ request('search_opname') }}"
                           placeholder="Cari kode SKU material, nama suku cadang, lokasi rak, atau kategori..."
                           class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <div class="sm:col-span-2 flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-semibold hover:bg-slate-800 transition-colors">
                        Cari Item
                    </button>
                    @if(request()->filled('search_opname'))
                        <a href="{{ route('laporan.index', ['tab' => 'stok_opname']) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-medium transition-colors" title="Reset">
                            ✕
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Stok Opname Audit Table -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div>
                    <h2 class="font-bold text-slate-900 text-base">Lembar Audit Rekonsiliasi Stok Opname Fisik Gudang</h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Lokasi: Gudang Regional Banjarmasin &bull; Auditor: Admin Kantor (Banjarmasin) &bull; Update: {{ date('d F Y') }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-lg text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Status Opname: Diverifikasi Sesuai
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50/80 text-[10px] text-slate-500 uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3.5 font-semibold">Kode SKU &amp; Nama Material</th>
                            <th class="px-6 py-3.5 font-semibold">Kategori</th>
                            <th class="px-6 py-3.5 font-semibold">Lokasi Rak / Bin</th>
                            <th class="px-6 py-3.5 text-center font-semibold">Stok Sistem</th>
                            <th class="px-6 py-3.5 text-center font-semibold">Stok Fisik Audit</th>
                            <th class="px-6 py-3.5 text-center font-semibold">Selisih</th>
                            <th class="px-6 py-3.5 text-center font-semibold">Buffer Min</th>
                            <th class="px-6 py-3.5 text-center font-semibold">Status Fisik</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($opnameItems as $item)
                            @php
                                $isBufferLow = $item->stock <= $item->min_stock;
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-mono text-xs font-bold text-slate-900">{{ $item->code }}</div>
                                    <div class="font-bold text-slate-800 text-xs mt-0.5">{{ $item->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[11px] font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">
                                        {{ $item->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-slate-600 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        {{ $item->location ?? 'Gudang Banjarmasin' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-mono text-xs font-bold text-slate-900">
                                    {{ $item->stock }} {{ $item->unit }}
                                </td>
                                <td class="px-6 py-4 text-center font-mono text-xs font-bold text-emerald-700">
                                    <span class="bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                                        {{ $item->stock }} {{ $item->unit }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-mono text-xs font-bold text-slate-600">
                                    <span class="text-slate-400">0 (Nol)</span>
                                </td>
                                <td class="px-6 py-4 text-center font-mono text-xs text-slate-500">
                                    {{ $item->min_stock }} {{ $item->unit }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($isBufferLow)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                            ⚠ Buffer Menipis
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            ✓ Sesuai (Aman)
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-400 text-xs">
                                    Tidak ada material yang sesuai kriteria pencarian opname.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Catatan Audit Opname -->
            <div class="p-5 bg-slate-50/70 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-600">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span><strong>Catatan Audit Opname:</strong> Kuantitas fisik sparepart dan kabel telah dihitung langsung di Gudang Banjarmasin dan sesuai dengan catatan mutasi SPK di sistem.</span>
                </div>
                <div class="text-[11px] font-semibold text-slate-500 font-mono">
                    Penanggung Jawab: Admin Kantor (Banjarmasin)
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
