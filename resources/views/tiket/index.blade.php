@extends('layouts.app', ['title' => 'Tiket & Keluhan Pelanggan'])

@section('content')
<div class="space-y-6">
    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Keluhan &amp; Tiket Gangguan Jaringan</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Pusat penanganan komplain pelanggan, analisis telemetri ODP, dan penerbitan Surat Perintah Kerja (SPK).
            </p>
        </div>

        <button onclick="document.getElementById('modalNewTicket').classList.remove('hidden')"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs sm:text-sm font-semibold shadow-sm shadow-blue-500/20 transition-all hover:scale-[1.01] active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Input Keluhan Baru
        </button>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <form method="GET" action="{{ route('tiket.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-5 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari no. tiket, nama pelanggan, alamat, atau ODP..."
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div class="sm:col-span-2">
                <select name="status" onchange="this.form.submit()"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                    <option value="Semua">Semua Status</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                    <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress (Di Lapangan)</option>
                    <option value="Solved" {{ request('status') === 'Solved' ? 'selected' : '' }}>Solved (Selesai)</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <select name="type" onchange="this.form.submit()"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                    <option value="Semua">Semua Tipe</option>
                    <option value="OUTAGE" {{ request('type') === 'OUTAGE' ? 'selected' : '' }}>OUTAGE (Massal/Feeder)</option>
                    <option value="ISOLIR" {{ request('type') === 'ISOLIR' ? 'selected' : '' }}>ISOLIR (Lokal/Pelanggan)</option>
                </select>
            </div>

            <div class="sm:col-span-3 flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-semibold hover:bg-slate-800 transition-colors">
                    Filter Tiket
                </button>
                @if(request()->anyFilled(['search', 'status', 'type']))
                    <a href="{{ route('tiket.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-medium transition-colors" title="Reset Filter">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Ticket List Table -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-900 text-base">Daftar Tiket Keluhan</h2>
            <span class="text-xs text-slate-500 font-medium">Halaman {{ $tickets->currentPage() }} dari {{ $tickets->lastPage() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50/75 text-[10px] text-slate-500 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5 font-semibold">No. Tiket / Waktu</th>
                        <th class="px-6 py-3.5 font-semibold">Pelanggan &amp; Lokasi</th>
                        <th class="px-6 py-3.5 font-semibold">Simpul ODP</th>
                        <th class="px-6 py-3.5 font-semibold">Deskripsi Keluhan</th>
                        <th class="px-6 py-3.5 font-semibold text-center">Klasifikasi</th>
                        <th class="px-6 py-3.5 font-semibold text-center">Status</th>
                        <th class="px-6 py-3.5 font-semibold text-right">Tindakan NOC</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($tickets as $ticket)
                        @php
                            $isOutage = $ticket->type === 'OUTAGE';
                            $statusBg = match($ticket->status) {
                                'Pending' => 'bg-amber-50 text-amber-800 border-amber-200',
                                'In Progress' => 'bg-blue-50 text-blue-800 border-blue-200',
                                'Solved' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                default => 'bg-slate-50 text-slate-800',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded">
                                    {{ $ticket->ticket_number }}
                                </span>
                                <div class="text-[10px] text-slate-400 mt-1">
                                    {{ $ticket->created_at->format('d M Y, H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900 text-xs">{{ $ticket->customer_name }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5 truncate max-w-xs">{{ $ticket->address }} ({{ $ticket->area }})</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">Telp: {{ $ticket->customer_phone ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs font-semibold text-slate-700">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 rounded-md">
                                    {{ $ticket->odp_code }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-slate-800 line-clamp-2">{{ $ticket->issue }}</p>
                                @if($ticket->workOrder)
                                    <div class="mt-1 flex items-center gap-1.5 text-[11px] text-indigo-700 font-semibold flex-wrap">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span>Tim: {{ $ticket->workOrder->technician_name }}</span>
                                        <span class="text-[10px] font-mono text-slate-400">({{ $ticket->workOrder->spk_number }})</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($isOutage)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                        OUTAGE
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        ISOLIR
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $statusBg }}">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <button onclick="openTimelineModal({{ json_encode($ticket) }})"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition-colors"
                                            title="Lihat Riwayat & Log Aktivitas">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Riwayat &amp; Log</span>
                                    </button>

                                    @if($ticket->status === 'Pending')
                                        <button onclick="openDispatchModal({{ json_encode($ticket) }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-xs transition-all hover:scale-[1.01]">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            Analisis &amp; SPK
                                        </button>
                                    @elseif($ticket->status === 'Solved')
                                        <button onclick="openDetailModal({{ json_encode($ticket) }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 rounded-lg text-xs font-semibold transition-colors">
                                            Solusi
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-xs">
                                Tidak ada tiket keluhan ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $tickets->links() }}
        </div>
    </div>
</div>

<!-- Modal 1: Input Keluhan Baru -->
<div id="modalNewTicket" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 sm:p-7 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                Input Tiket Keluhan Pelanggan Baru
            </h3>
            <button onclick="document.getElementById('modalNewTicket').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <form method="POST" action="{{ route('tiket.store') }}" class="space-y-4 mt-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Pelanggan *</label>
                    <input type="text" name="customer_name" placeholder="Haji Sulaiman" required
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">No. WhatsApp / HP</label>
                    <input type="text" name="customer_phone" placeholder="0812-3456-7890"
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap *</label>
                <input type="text" name="address" placeholder="Jl. Hasan Basry No. 45 Kayutangi" required
                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Wilayah / Cluster *</label>
                    <select name="area" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="Banjarmasin Utara">Banjarmasin Utara</option>
                        <option value="Banjarmasin Tengah">Banjarmasin Tengah</option>
                        <option value="Banjarmasin Selatan">Banjarmasin Selatan</option>
                        <option value="Barito Kuala">Barito Kuala (Alalak)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ODP Terhubung *</label>
                    <select name="odp_code" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        @foreach($topologyNodes as $node)
                            <option value="{{ $node->name }}">{{ $node->name }} ({{ $node->location }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tingkat Prioritas *</label>
                    <select name="priority" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="Medium">Medium (Standar)</option>
                        <option value="High" selected>High (Prioritas)</option>
                        <option value="Critical">Critical (VIP/Instansi)</option>
                        <option value="Low">Low</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Keluhan / Gejala Gangguan *</label>
                <textarea name="issue" rows="3" required placeholder="Lampu LOS merah berkedip sejak pagi, internet padam..."
                          class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalNewTicket').classList.add('hidden')"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-medium transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm transition-all">
                    Daftarkan Tiket
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Analisis & Dispatch SPK Teknisi (Section A, B, C) -->
<div id="modalDispatch" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-150 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <span class="text-[10px] font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded" id="dispTicketNum"></span>
                <h3 class="font-bold text-slate-900 text-base mt-1">Form Analisis NOC &amp; Penerbitan SPK</h3>
            </div>
            <button onclick="document.getElementById('modalDispatch').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <form id="formDispatch" method="POST" action="" class="space-y-5 mt-5">
            @csrf

            <!-- SECTION A: Telemetry & Diagnostic -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Bagian A: Hasil Diagnostik Telemetri NOC
                    </h4>
                    <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60">Live Ping Check</span>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-3 text-xs">
                    <div>
                        <span class="text-slate-400">Pelanggan:</span>
                        <p class="font-bold text-slate-800" id="dispCustomer"></p>
                    </div>
                    <div>
                        <span class="text-slate-400">Simpul ODP:</span>
                        <p class="font-bold text-slate-800 font-mono" id="dispOdp"></p>
                    </div>
                    <div class="col-span-2">
                        <span class="text-slate-400">Gejala Dilaporkan:</span>
                        <p class="text-slate-700 bg-white p-2.5 rounded-lg border border-slate-200 mt-1" id="dispIssue"></p>
                    </div>

                    <!-- Mini Satellite Map for this Ticket's ODP -->
                    <div class="col-span-2 mt-1">
                        <div class="flex items-center justify-between text-[11px] text-slate-500 mb-1">
                            <span class="font-bold text-slate-700 flex items-center gap-1">
                                🛰️ Pemetaan Satelit Posisi Tiang ODP &amp; Pelanggan:
                            </span>
                            <span id="dispCoords" class="font-mono text-blue-600 font-semibold"></span>
                        </div>
                        <div id="ticketOdpMap" class="w-full h-40 rounded-xl border border-slate-300 overflow-hidden bg-slate-900"></div>
                        <div class="mt-2 text-right">
                            <a id="btnNavGmaps" href="#" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 hover:text-blue-800 transition-colors">
                                <span>Buka Rute Navigasi Google Maps Lapangan</span>
                                <span>↗</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION B: Klasifikasi Gangguan -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                    Bagian B: Klasifikasi Jenis Gangguan *
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="flex items-start gap-3 p-3.5 border-2 rounded-xl cursor-pointer transition-all border-slate-200 hover:border-slate-300 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/50">
                        <input type="radio" name="classification_type" value="ISOLIR" id="radioIsolir" checked class="mt-1 text-amber-600 focus:ring-amber-500">
                        <div>
                            <span class="text-xs sm:text-sm font-bold text-slate-900 block">ISOLIR (Lokal / Rumah)</span>
                            <span class="text-[11px] text-slate-500 block mt-0.5">Kerusakan terbatas pada jalur dropcore, patchcord, atau modem ONT pelanggan.</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-3.5 border-2 rounded-xl cursor-pointer transition-all border-slate-200 hover:border-slate-300 has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50/50">
                        <input type="radio" name="classification_type" value="OUTAGE" id="radioOutage" class="mt-1 text-rose-600 focus:ring-rose-500">
                        <div>
                            <span class="text-xs sm:text-sm font-bold text-rose-700 block">OUTAGE (Massal / Feeder)</span>
                            <span class="text-[11px] text-slate-500 block mt-0.5">Kabel distribusi/feeder putus, ODP padam, mempengaruhi seluruh pelanggan cluster.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- SECTION B: Penugasan Tim Teknisi (Multi-Select) -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Bagian B: Penunjukan Tim Teknisi Lapangan *
                    </label>
                    <span class="text-[11px] text-blue-600 font-semibold bg-blue-50 px-2 py-0.5 rounded">
                        Pilih 1 atau Banyak Personel
                    </span>
                </div>
                <p class="text-[11px] text-slate-500 mb-2.5">
                    Centang personel teknisi yang ditugaskan. Untuk gangguan kabel putus (OUTAGE), disarankan menugaskan minimal 2 personel teknisi (Team Lead &amp; Splicer).
                </p>

                <div class="space-y-2 max-h-48 overflow-y-auto p-3 bg-slate-50 rounded-xl border border-slate-200">
                    @foreach($technicians as $idx => $tech)
                        @php
                            $isAvail = ($tech->technician_status ?? 'Available') === 'Available';
                        @endphp
                        <label class="flex items-center justify-between gap-3 p-2.5 rounded-xl border bg-white cursor-pointer transition-all hover:border-blue-300 {{ $isAvail ? 'border-slate-200/90' : 'border-amber-200 bg-amber-50/20' }}">
                            <div class="flex items-center gap-2.5">
                                <input type="checkbox" name="technician_ids[]" value="{{ $tech->id }}"
                                       class="rounded text-blue-600 focus:ring-blue-500 w-4 h-4 tech-select-cb"
                                       {{ $idx === 0 ? 'checked' : '' }}>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-xs text-slate-900">{{ $tech->name }}</span>
                                        <span class="text-[10px] font-semibold px-1.5 py-0.2 rounded {{ $isAvail ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                            ● {{ $tech->technician_status ?? 'Available' }}
                                        </span>
                                    </div>
                                    <span class="text-[11px] text-slate-400 block mt-0.5">
                                        {{ $tech->phone ?? '-' }} &bull; Area: {{ $tech->area ?? 'Banjarmasin' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-1.5">
                                <label class="text-[10px] text-slate-500 flex items-center gap-1 cursor-pointer bg-slate-50 px-2 py-1 rounded-lg border border-slate-200">
                                    <input type="radio" name="lead_technician_id" value="{{ $tech->id }}" {{ $idx === 0 ? 'checked' : '' }}>
                                    <span class="font-semibold text-slate-700">Ketua Tim</span>
                                </label>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="mt-2.5">
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">
                        Catatan Khusus Instruksi NOC untuk Tim Teknisi (Opsional)
                    </label>
                    <input type="text" name="admin_dispatch_notes" placeholder="Contoh: Tim diminta membawa tangga teleskopik 6m dan genset portable..."
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
            </div>

            <!-- SECTION C: Material Handover (Alokasi Gudang) -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-2 flex items-center justify-between">
                    <span>Bagian C: Alokasi &amp; Pengeluaran Material (Gudang Logistik)</span>
                    <span class="text-[11px] font-normal text-slate-500">Sesuai kebutuhan lapangan</span>
                </h4>

                <div class="space-y-2 max-h-48 overflow-y-auto p-3 bg-slate-50 rounded-xl border border-slate-200">
                    @foreach($materials->take(5) as $idx => $mat)
                        <div class="flex items-center justify-between gap-3 text-xs bg-white p-2.5 rounded-lg border border-slate-200/80">
                            <label class="flex items-center gap-2.5 cursor-pointer flex-1">
                                <input type="checkbox" name="material_ids[]" value="{{ $mat->id }}" class="rounded text-blue-600 focus:ring-blue-500">
                                <div>
                                    <span class="font-bold text-slate-800">{{ $mat->name }}</span>
                                    <span class="text-slate-400 block text-[10px]">Stok: {{ $mat->stock }} {{ $mat->unit }} &bull; {{ $mat->category }}</span>
                                </div>
                            </label>
                            <div class="flex items-center gap-1.5">
                                <span class="text-slate-500 text-[11px]">Bawa:</span>
                                <input type="number" name="material_quantities[]" value="1" min="1" max="{{ $mat->stock }}"
                                       class="w-16 px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg text-center text-xs font-bold focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <span class="text-slate-400 text-[10px]">{{ $mat->unit }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalDispatch').classList.add('hidden')"
                        class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-medium transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:scale-[1.01]">
                    Terbitkan SPK &amp; Kirim ke Teknisi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 4: Riwayat Lengkap & Log Perjalanan Tiket (Timeline) -->
<div id="modalTimeline" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 shrink-0">
            <div>
                <div class="flex items-center gap-2">
                    <span id="tlTicketNumber" class="font-mono text-xs font-bold text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-lg border border-blue-200"></span>
                    <span id="tlStatusBadge" class="text-[11px] font-bold px-2.5 py-0.5 rounded-full"></span>
                    <span id="tlTypeBadge" class="text-[11px] font-bold px-2 py-0.5 rounded-md"></span>
                </div>
                <h3 class="font-bold text-slate-900 text-base mt-1.5 flex items-center gap-2">
                    Riwayat Perjalanan &amp; Log Detail Tiket
                </h3>
            </div>
            <button onclick="document.getElementById('modalTimeline').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <!-- Scrollable Content -->
        <div class="overflow-y-auto flex-1 py-4 space-y-4 pr-1">
            <!-- Summary Info Box -->
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Pelanggan &amp; Lokasi</span>
                    <p id="tlCustomer" class="font-bold text-slate-900 mt-0.5"></p>
                    <p id="tlAddress" class="text-slate-600 text-[11px]"></p>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-semibold">Simpul ODP Terhubung</span>
                    <p id="tlOdp" class="font-bold font-mono text-blue-700 mt-0.5"></p>
                    <p id="tlIssue" class="text-slate-600 text-[11px] line-clamp-2"></p>
                </div>
            </div>

            <!-- Team Technicians Assigned Card (if any) -->
            <div id="tlTeamCard" class="hidden bg-indigo-50/70 p-4 rounded-2xl border border-indigo-100 text-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-bold text-indigo-950 flex items-center gap-1.5 text-xs">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Tim Teknisi Lapangan Bertugas
                    </span>
                    <span id="tlSpkNumber" class="font-mono font-bold text-[11px] text-indigo-700 bg-white px-2 py-0.5 rounded border border-indigo-200"></span>
                </div>
                <div id="tlTechList" class="flex flex-wrap gap-2 mt-2"></div>
            </div>

            <!-- Activity Logs Timeline -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-3 flex items-center justify-between">
                    <span>Linimasa Riwayat Penanganan</span>
                    <span class="text-[10px] text-slate-400 font-normal">Audit Log Berurutan Waktu</span>
                </h4>

                <div id="tlLogsContainer" class="relative pl-6 space-y-4 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                    <!-- Dynamic Log Items Injected via JS -->
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="pt-4 border-t border-slate-100 flex justify-end shrink-0">
            <button type="button" onclick="document.getElementById('modalTimeline').classList.add('hidden')"
                    class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-colors">
                Tutup Jendela Riwayat
            </button>
        </div>
    </div>
</div>

<script>
let ticketMap = null;
let ticketMarker = null;
const allNodes = @json($allNodes ?? $topologyNodes);

function openDispatchModal(ticket) {
    document.getElementById('formDispatch').action = '/tiket/' + ticket.id + '/dispatch';
    document.getElementById('dispTicketNum').innerText = ticket.ticket_number;
    document.getElementById('dispCustomer').innerText = ticket.customer_name + ' (' + ticket.area + ')';
    document.getElementById('dispOdp').innerText = ticket.odp_code;
    document.getElementById('dispIssue').innerText = ticket.issue;

    if (ticket.type === 'OUTAGE') {
        document.getElementById('radioOutage').checked = true;
    } else {
        document.getElementById('radioIsolir').checked = true;
    }

    document.getElementById('modalDispatch').classList.remove('hidden');

    // Find ODP coordinates
    const targetNode = allNodes.find(n => n.name === ticket.odp_code);
    let lat = -3.32;
    let lng = 114.60;

    if (targetNode && targetNode.coordinates) {
        const parts = targetNode.coordinates.split(',');
        if (parts.length === 2) {
            lat = parseFloat(parts[0].trim());
            lng = parseFloat(parts[1].trim());
            document.getElementById('dispCoords').innerText = targetNode.coordinates;
            document.getElementById('btnNavGmaps').href = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
        }
    } else {
        document.getElementById('dispCoords').innerText = 'Koordinat Tiang Standar Kalsel';
        document.getElementById('btnNavGmaps').href = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
    }

    // Initialize or resize Leaflet Map
    setTimeout(() => {
        if (!ticketMap) {
            const esriSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19
            });
            const esriLabels = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19
            });
            const hybrid = L.layerGroup([esriSatellite, esriLabels]);

            ticketMap = L.map('ticketOdpMap', {
                center: [lat, lng],
                zoom: 16,
                layers: [hybrid]
            });
        } else {
            ticketMap.invalidateSize();
            ticketMap.setView([lat, lng], 16);
        }

        if (ticketMarker) {
            ticketMap.removeLayer(ticketMarker);
        }

        const iconHtml = `
            <div style="position: relative; width: 28px; height: 28px;">
                <span style="position: absolute; inset: 0; border-radius: 50%; background: #3b82f6; opacity: 0.75; animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;"></span>
                <div style="position: relative; background: #2563eb; width: 28px; height: 28px; border-radius: 50%; border: 2.5px solid #ffffff; box-shadow: 0 4px 10px rgba(37,99,235,0.6); display: flex; align-items: center; justify-content: center; color: white; font-size: 11px; font-weight: bold;">
                    📍
                </div>
            </div>`;

        const pin = L.divIcon({
            className: 'mini-ticket-pin',
            html: iconHtml,
            iconSize: [28, 28],
            iconAnchor: [14, 14]
        });

        ticketMarker = L.marker([lat, lng], { icon: pin }).addTo(ticketMap);
        ticketMarker.bindPopup(`<b>${ticket.odp_code}</b><br><span style="font-size:10px;">${ticket.customer_name}</span>`).openPopup();
    }, 200);
}

function openDetailModal(ticket) {
    document.getElementById('detSpk').innerText = ticket.work_order ? ticket.work_order.spk_number : '-';
    document.getElementById('detTech').innerText = ticket.work_order ? ticket.work_order.technician_name : '-';
    document.getElementById('detNotes').innerText = (ticket.work_order && ticket.work_order.tech_notes) 
        ? ticket.work_order.tech_notes 
        : 'Perbaikan selesai dilaksanakan dan sinyal redaman pelanggan kembali normal.';
    document.getElementById('modalDetail').classList.remove('hidden');
}

function openTimelineModal(ticket) {
    document.getElementById('tlTicketNumber').innerText = ticket.ticket_number;
    
    // Status Badge
    const stEl = document.getElementById('tlStatusBadge');
    stEl.innerText = ticket.status;
    if (ticket.status === 'Solved') {
        stEl.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200';
    } else if (ticket.status === 'In Progress') {
        stEl.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-800 border border-blue-200 animate-pulse';
    } else {
        stEl.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200';
    }

    // Type Badge
    const tpEl = document.getElementById('tlTypeBadge');
    tpEl.innerText = ticket.type;
    tpEl.className = ticket.type === 'OUTAGE'
        ? 'text-[11px] font-bold px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200'
        : 'text-[11px] font-bold px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200';

    document.getElementById('tlCustomer').innerText = ticket.customer_name + ' (' + (ticket.customer_phone || '-') + ')';
    document.getElementById('tlAddress').innerText = ticket.address + ', ' + ticket.area;
    document.getElementById('tlOdp').innerText = ticket.odp_code;
    document.getElementById('tlIssue').innerText = ticket.issue;

    // Team Card
    const teamCard = document.getElementById('tlTeamCard');
    const techListEl = document.getElementById('tlTechList');
    techListEl.innerHTML = '';

    if (ticket.work_order) {
        teamCard.classList.remove('hidden');
        document.getElementById('tlSpkNumber').innerText = ticket.work_order.spk_number;

        if (ticket.work_order.technicians && ticket.work_order.technicians.length > 0) {
            ticket.work_order.technicians.forEach(t => {
                const isLead = t.role_in_team === 'Team Lead';
                techListEl.innerHTML += `
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-indigo-200 text-xs shadow-xs">
                        <span class="w-2 h-2 rounded-full ${isLead ? 'bg-indigo-600' : 'bg-blue-400'}"></span>
                        <strong class="text-slate-800">${t.technician_name}</strong>
                        <span class="text-[10px] ${isLead ? 'bg-indigo-100 text-indigo-700 font-bold' : 'bg-slate-100 text-slate-500'} px-1.5 py-0.2 rounded">
                            ${t.role_in_team || 'Teknisi'}
                        </span>
                    </div>
                `;
            });
        } else {
            techListEl.innerHTML = `
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-indigo-200 text-xs">
                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                    <strong class="text-slate-800">${ticket.work_order.technician_name}</strong>
                </div>
            `;
        }
    } else {
        teamCard.classList.add('hidden');
    }

    // Render Logs
    const container = document.getElementById('tlLogsContainer');
    container.innerHTML = '';

    const logs = ticket.ticket_logs || [];
    if (logs.length > 0) {
        logs.forEach(log => {
            const roleBadgeColor = log.actor_role === 'admin' 
                ? 'bg-blue-50 text-blue-700 border-blue-200' 
                : (log.actor_role === 'teknisi' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200');

            const dateStr = log.created_at ? new Date(log.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) : '-';

            container.innerHTML += `
                <div class="relative">
                    <div class="absolute -left-6 top-1 w-3 h-3 rounded-full bg-blue-600 ring-4 ring-white"></div>
                    <div class="bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-xs space-y-1.5">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <span class="font-bold text-xs text-slate-900">${log.title}</span>
                            <span class="text-[10px] text-slate-400 font-mono">${dateStr} WITA</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded border ${roleBadgeColor}">
                                ${log.actor_name}
                            </span>
                        </div>
                        <p class="text-xs text-slate-700 leading-relaxed font-sans">${log.description}</p>
                        ${log.notes ? `
                            <div class="mt-2 p-2.5 rounded-xl bg-slate-50 text-[11px] text-slate-600 border border-slate-200/70 font-mono">
                                <strong>Catatan Teknis:</strong> ${log.notes}
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
        });
    } else {
        container.innerHTML = `
            <div class="relative">
                <div class="absolute -left-6 top-1 w-3 h-3 rounded-full bg-blue-600 ring-4 ring-white"></div>
                <div class="bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-xs">
                    <p class="font-bold text-xs text-slate-900">Tiket Terdaftar di Sistem</p>
                    <p class="text-xs text-slate-500 mt-1">Laporan berhasil dicatat dan masuk dalam antrean pemantauan NOC.</p>
                </div>
            </div>
        `;
    }

    document.getElementById('modalTimeline').classList.remove('hidden');
}
</script>
@endsection
