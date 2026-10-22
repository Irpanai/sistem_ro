@extends('layouts.app', ['title' => 'Logistik Material & Gudang'])

@section('content')
@php
    $user = auth()->user();
    $isAdmin = $user && $user->role === 'admin';
    $isTeknisi = $user && $user->role === 'teknisi';
    $defaultTab = $isTeknisi ? 'handover' : 'inventory';
@endphp

<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Logistik Material &amp; Inventaris Gudang</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                @if($isTeknisi)
                    Daftar material perbaikan yang diserahkan Admin NOC kepada Anda serta katalog ketersediaan stok sparepart gudang.
                @else
                    Monitoring persediaan material serat optik, perangkat modem OLT/ONT, dan audit pengeluaran spare part teknisi.
                @endif
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            @if($isAdmin)
                <button onclick="document.getElementById('modalAddMaterial').classList.remove('hidden')"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs sm:text-sm font-semibold shadow-sm shadow-blue-500/20 transition-all hover:scale-[1.01] active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Material Baru
                </button>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-xl text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Mode Teknisi: Akses Material Diserahkan &amp; Cek Gudang
                </span>
            @endif
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    {{ $isTeknisi ? 'Material Diserahkan ke Anda' : 'Total SKU Gudang' }}
                </p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1 font-mono">
                    {{ $isTeknisi ? $assignedOutbounds->count() : $totalSkus }}
                    <span class="text-xs font-normal text-slate-500 font-sans">{{ $isTeknisi ? 'Alokasi SPK' : 'Jenis Barang' }}</span>
                </h3>
                <p class="text-xs text-slate-400 mt-1">{{ $isTeknisi ? 'Log serah terima dari Admin NOC' : 'Tersedia di Gudang Banjarmasin' }}</p>
            </div>
            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-rose-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Stok Menipis / Kritis</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-rose-600 mt-1 font-mono">{{ $lowStockCount }} <span class="text-xs font-normal text-slate-500 font-sans">Perlu Restock</span></h3>
                <p class="text-xs text-rose-500 font-medium mt-1">Di bawah batas buffer minimum</p>
            </div>
            <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-emerald-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Stok Aman &amp; Optimal</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-emerald-600 mt-1 font-mono">{{ $healthyStockCount }} <span class="text-xs font-normal text-slate-500 font-sans">Material Aman</span></h3>
                <p class="text-xs text-emerald-600 font-medium mt-1">Siap dialokasikan untuk penanganan SPK</p>
            </div>
            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- TAB SWITCHER: Serah Terima vs Katalog Stok -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-1">
        <button onclick="switchTab('handover')" id="btnTabHandover"
                class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 {{ $defaultTab === 'handover' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            <span>Material Diserahkan ke Teknisi ({{ $assignedOutbounds->count() }})</span>
        </button>

        <button onclick="switchTab('inventory')" id="btnTabInventory"
                class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 {{ $defaultTab === 'inventory' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span>Katalog Stok Gudang {{ $isTeknisi ? '(Read-Only)' : '' }} ({{ $materials->count() }})</span>
        </button>
    </div>

    <!-- SECTION 1: MATERIAL DISERAHKAN KE TEKNISI -->
    <div id="sectionHandover" class="{{ $defaultTab === 'handover' ? '' : 'hidden' }} space-y-4">
        <div class="bg-blue-50/70 border border-blue-200/80 p-4 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900">
                        {{ $isTeknisi ? 'Daftar Material yang Diserahkan Admin NOC Kepada Anda / Tim' : 'Audit Pengeluaran & Serah Terima Material ke Teknisi Lapangan' }}
                    </h3>
                    <p class="text-[11px] text-slate-500">
                        Dicatat otomatis setiap kali Admin NOC menyetujui pengeluaran material saat menerbitkan Surat Perintah Kerja (SPK).
                    </p>
                </div>
            </div>
            <span class="text-[11px] font-mono font-bold px-3 py-1 bg-white text-blue-700 rounded-lg border border-blue-200 shrink-0">
                Total: {{ $assignedOutbounds->count() }} Transaksi
            </span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3.5 px-4 sm:px-6">Kode Log</th>
                            <th class="py-3.5 px-4">No. SPK / Tiket</th>
                            <th class="py-3.5 px-4">Pelanggan &amp; Lokasi</th>
                            <th class="py-3.5 px-4">Material Diserahkan</th>
                            <th class="py-3.5 px-4">Teknisi Penerima</th>
                            <th class="py-3.5 px-4">Disetujui Oleh</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($assignedOutbounds as $out)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6 font-mono font-bold text-blue-700">
                                    {{ $out->outbound_code }}
                                    <span class="block font-sans font-normal text-[10px] text-slate-400">
                                        {{ $out->created_at ? $out->created_at->format('d/m/Y H:i') : '-' }} WITA
                                    </span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="font-mono font-semibold text-slate-900 block">{{ $out->spk_number ?? '-' }}</span>
                                    <span class="font-mono text-[10px] text-slate-400">{{ $out->ticket_number ?? '-' }}</span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <p class="font-bold text-slate-800">{{ $out->customer_name ?? 'Pelanggan' }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $out->ticket ? $out->ticket->odp_code : 'Simpul ODP' }}</p>
                                </td>

                                <td class="py-3.5 px-4">
                                    <p class="font-bold text-slate-900">{{ $out->material_name }}</p>
                                    <span class="inline-flex items-center gap-1 font-mono font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded text-[11px] border border-emerald-200 mt-0.5">
                                        {{ $out->quantity }} {{ $out->unit }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-[10px] shrink-0">
                                            ✓
                                        </div>
                                        <span class="font-semibold text-slate-800 text-xs">{{ $out->technician_name }}</span>
                                    </div>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="text-xs text-slate-600 font-medium">{{ $out->approved_by }}</span>
                                    <span class="block text-[10px] text-slate-400">NOC Verification</span>
                                </td>

                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold {{ $out->status === 'Used & Completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                        {{ $out->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    Belum ada catatan material yang diserahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- SECTION 2: KATALOG STOK GUDANG -->
    <div id="sectionInventory" class="{{ $defaultTab === 'inventory' ? '' : 'hidden' }} space-y-4">
        <!-- Filter and Search -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
            <form method="GET" action="{{ route('logistik.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                <div class="sm:col-span-5 relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari kode SKU, nama material, atau kategori..."
                           class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <div class="sm:col-span-3">
                    <select name="category" onchange="this.form.submit()"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                        <option value="Semua">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <select name="condition" onchange="this.form.submit()"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                        <option value="">Semua Kondisi</option>
                        <option value="Menipis" {{ request('condition') === 'Menipis' ? 'selected' : '' }}>Stok Menipis</option>
                        <option value="Aman" {{ request('condition') === 'Aman' ? 'selected' : '' }}>Stok Aman</option>
                    </select>
                </div>

                <div class="sm:col-span-2 flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-semibold hover:bg-slate-800 transition-colors">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'category', 'condition']))
                        <a href="{{ route('logistik.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-medium transition-colors" title="Reset Filter">
                            ✕
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Inventory Table -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3.5 px-4 sm:px-6">Kode SKU</th>
                            <th class="py-3.5 px-4">Nama Material</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4 text-center">Stok Saat Ini</th>
                            <th class="py-3.5 px-4 text-center">Min. Buffer</th>
                            <th class="py-3.5 px-4">Lokasi Rak</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Aksi / Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($materials as $material)
                            @php
                                $isLow = $material->stock <= $material->min_stock;
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6 font-mono font-bold text-slate-900">
                                    {{ $material->code }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="font-bold text-slate-900">{{ $material->name }}</p>
                                    @if($material->price)
                                        <p class="text-[10px] text-slate-400 font-mono">Est: {{ $material->price }}</p>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-700">
                                        {{ $material->category }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $isLow ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                        @if($isLow)
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                        @endif
                                        {{ $material->stock }} {{ $material->unit }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono text-slate-500">
                                    {{ $material->min_stock }} {{ $material->unit }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">
                                    {{ $material->location ?? 'Gudang Pusat' }}
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-right">
                                    @if($isAdmin)
                                        <button onclick="openRestockModal('{{ $material->id }}', '{{ addslashes($material->name) }}', '{{ $material->stock }}', '{{ $material->unit }}')"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg text-xs font-semibold transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Restock
                                        </button>
                                    @else
                                        <span class="text-[11px] font-semibold text-slate-400 bg-slate-50 px-2 py-1 rounded border border-slate-200">
                                            Tersedia di Gudang
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    Tidak ada material yang cocok dengan kriteria pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if($isAdmin)
<!-- Modal Restock (Admin Only) -->
<div id="modalRestock" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="font-bold text-slate-900 text-base">Restock Pasokan Gudang</h3>
            <button onclick="document.getElementById('modalRestock').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <form id="formRestock" method="POST" action="" class="space-y-4 mt-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase">Nama Material</label>
                <p id="restockMaterialName" class="text-sm font-bold text-slate-900 mt-1"></p>
                <p class="text-xs text-slate-400 mt-0.5">Stok Saat Ini: <span id="restockCurrentStock" class="font-bold text-blue-600 font-mono"></span></p>
            </div>

            <div>
                <label for="added_quantity" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Jumlah Tambahan Masuk</label>
                <div class="flex items-center gap-2">
                    <input type="number" id="added_quantity" name="added_quantity" min="1" value="10" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-mono">
                    <span id="restockUnit" class="text-xs font-semibold text-slate-500 px-2">Unit</span>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalRestock').classList.add('hidden')"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-medium transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm transition-all">
                    Simpan Penambahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Material Baru (Admin Only) -->
<div id="modalAddMaterial" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="font-bold text-slate-900 text-base">Tambah SKU Material Baru</h3>
            <button onclick="document.getElementById('modalAddMaterial').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <form method="POST" action="{{ route('logistik.store') }}" class="space-y-4 mt-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kode SKU *</label>
                    <input type="text" name="code" placeholder="Contoh: CBL-DRP-02" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori *</label>
                    <input type="text" name="category" placeholder="Kabel Fiber / Perangkat" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Material / Spesifikasi *</label>
                <input type="text" name="name" placeholder="Contoh: Kabel Dropcore 2 Core GJXH-2B" required
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Stok Awal *</label>
                    <input type="number" name="stock" value="50" min="0" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Batas Min *</label>
                    <input type="number" name="min_stock" value="15" min="1" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Satuan *</label>
                    <input type="text" name="unit" placeholder="Roll / Pcs / Box" required
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Estimasi Harga (Opsional)</label>
                    <input type="text" name="price" placeholder="Rp 450.000 / roll"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Lokasi Rak Simpan</label>
                    <input type="text" name="location" placeholder="Rak A-02 / Gudang 1"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalAddMaterial').classList.add('hidden')"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-medium transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm transition-all">
                    Simpan Material
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
function switchTab(tab) {
    const secHandover = document.getElementById('sectionHandover');
    const secInventory = document.getElementById('sectionInventory');
    const btnHandover = document.getElementById('btnTabHandover');
    const btnInventory = document.getElementById('btnTabInventory');

    if (tab === 'handover') {
        secHandover.classList.remove('hidden');
        secInventory.classList.add('hidden');
        btnHandover.className = "px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 bg-blue-600 text-white shadow-sm";
        btnInventory.className = "px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100";
    } else {
        secHandover.classList.add('hidden');
        secInventory.classList.remove('hidden');
        btnInventory.className = "px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 bg-blue-600 text-white shadow-sm";
        btnHandover.className = "px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100";
    }
}

function openRestockModal(id, name, currentStock, unit) {
    document.getElementById('formRestock').action = '/logistik/' + id + '/restock';
    document.getElementById('restockMaterialName').innerText = name;
    document.getElementById('restockCurrentStock').innerText = currentStock + ' ' + unit;
    document.getElementById('restockUnit').innerText = unit;
    document.getElementById('modalRestock').classList.remove('hidden');
}
</script>
@endsection
