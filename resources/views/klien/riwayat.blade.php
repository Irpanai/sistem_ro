@extends('layouts.app', ['title' => 'Riwayat Tiket & Keluhan Pelanggan'])

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-blue-950 to-indigo-950 text-white p-6 sm:p-8 rounded-3xl shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-500/10 rounded-full blur-2xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 text-blue-200 text-xs font-semibold backdrop-blur-md mb-3 border border-blue-500/30">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Audit Log &amp; Rekap Transparansi Layanan
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Riwayat Tiket &amp; Pengaduan
                </h1>
                <p class="text-slate-300 text-xs sm:text-sm mt-1.5 max-w-xl leading-relaxed">
                    Daftar histori seluruh kendala yang pernah Anda laporkan, rekam jejak kedatangan teknisi lapangan, dan hasil pengukuran redaman serat optik.
                </p>
            </div>

            <a href="{{ route('klien.portal') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/30 transition-all hover:scale-[1.02] active:scale-[0.98] text-xs sm:text-sm whitespace-nowrap self-start md:self-auto">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Buat Laporan Baru di Portal
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards (3 Columns) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Laporan Dibuat</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1 font-mono">{{ $totalTicketsCount }} <span class="text-xs font-normal text-slate-500 font-sans">Tiket</span></h3>
                <p class="text-xs text-slate-400 mt-1">Sejak awal berlangganan internet</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Berhasil Diselesaikan</p>
                <h3 class="text-3xl font-extrabold text-emerald-600 mt-1 font-mono">{{ $solvedTicketsCount }} <span class="text-xs font-normal text-slate-500 font-sans">Solved</span></h3>
                <p class="text-xs text-emerald-600 font-medium mt-1">Koneksi telah kembali normal</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Laporan Aktif</p>
                <h3 class="text-3xl font-extrabold text-amber-600 mt-1 font-mono">{{ $activeTicketsCount }} <span class="text-xs font-normal text-slate-500 font-sans">Proses</span></h3>
                <p class="text-xs text-amber-600 font-medium mt-1">{{ $activeTicketsCount > 0 ? 'Sedang ditangani oleh teknisi' : 'Semua keluhan tuntas' }}</p>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Search Input -->
        <div class="relative flex-1 max-w-md">
            <input type="text" id="searchTicketInput" oninput="applyFilters()"
                   placeholder="Cari nomor tiket, ODP, atau kendala..."
                   class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20">
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        <!-- Filter Status Buttons -->
        <div class="inline-flex p-1 bg-slate-100 rounded-xl border border-slate-200 text-xs font-semibold self-start sm:self-auto">
            <button type="button" onclick="setStatusFilter('all')" id="btn-filter-all"
                    class="filter-btn px-3.5 py-1.5 rounded-lg bg-white text-blue-700 shadow-xs transition-all font-bold">
                Semua ({{ $totalTicketsCount }})
            </button>
            <button type="button" onclick="setStatusFilter('solved')" id="btn-filter-solved"
                    class="filter-btn px-3.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition-all">
                Selesai / Solved ({{ $solvedTicketsCount }})
            </button>
            <button type="button" onclick="setStatusFilter('active')" id="btn-filter-active"
                    class="filter-btn px-3.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition-all">
                Aktif ({{ $activeTicketsCount }})
            </button>
        </div>
    </div>

    <!-- Tickets History Cards -->
    <div class="space-y-4" id="historyCardsContainer">
        @forelse($tickets as $ticket)
            @php
                $isSolved = $ticket->status === 'Solved';
                $ticketJson = json_encode($ticket);
            @endphp
            <div class="history-ticket-card bg-white rounded-2xl border {{ $isSolved ? 'border-slate-200/90' : 'border-amber-300 ring-2 ring-amber-500/10' }} shadow-xs p-6 space-y-4 transition-all"
                 data-status="{{ $isSolved ? 'solved' : 'active' }}"
                 data-search="{{ strtolower($ticket->ticket_number . ' ' . $ticket->issue . ' ' . $ticket->odp_code . ' ' . ($ticket->workOrder?->technician_name ?? '')) }}">
                
                <!-- Card Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-mono text-xs font-bold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-lg">
                                {{ $ticket->ticket_number }}
                            </span>
                            <span class="text-xs text-slate-400">
                                Waktu Laporan: {{ $ticket->created_at->format('d M Y, H:i') }} WITA
                            </span>
                            @if($ticket->odp_code)
                                <button type="button" onclick="openClientMap('{{ $ticket->odp_code }}', '{{ $ticket->area }}')"
                                        class="inline-flex items-center gap-1 font-mono text-[11px] font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-2 py-0.5 rounded-md transition-colors"
                                        title="Buka Peta Satelit ODP">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 1 6"></polygon>
                                    </svg>
                                    <span>ODP: {{ $ticket->odp_code }}</span>
                                </button>
                            @endif
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm sm:text-base mt-2">{{ $ticket->issue }}</h3>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <!-- Button Cetak Bukti Laporan (PDF) -->
                        <a href="{{ route('klien.tiket.print', $ticket) }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all border border-slate-200 shadow-xs"
                           title="Cetak Tanda Terima & Bukti Pengaduan (PDF)">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            <span>Cetak Bukti (PDF)</span>
                        </a>

                        <!-- Button Linimasa Detail -->
                        <button type="button" onclick="openClientTimelineModal({{ htmlspecialchars($ticketJson, ENT_QUOTES, 'UTF-8') }})"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition-all border border-indigo-200/60"
                                title="Lihat Riwayat & Linimasa Lengkap">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Lihat Linimasa &amp; Audit Log</span>
                        </button>

                        @if($isSolved)
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                Selesai (Solved)
                            </span>
                        @elseif($ticket->status === 'In Progress')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200 animate-pulse">
                                Sedang Ditangani
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                Menunggu Verifikasi
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Info Grid: Alamat, Tim Teknisi, Catatan Teknis -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <!-- Kolom 1: Alamat & Tim Teknisi -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/70 space-y-2">
                        <div>
                            <span class="text-slate-400 font-semibold uppercase text-[10px] block">Alamat Pemasangan</span>
                            <p class="text-slate-800 font-medium">{{ $ticket->address ?: 'Banjarmasin' }}</p>
                        </div>
                        <div class="pt-2 border-t border-slate-200/60">
                            <span class="text-slate-400 font-semibold uppercase text-[10px] block mb-1">Tim Teknisi yang Ditugaskan</span>
                            @if($ticket->workOrder)
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    @if($ticket->workOrder->technicians && $ticket->workOrder->technicians->count() > 0)
                                        @foreach($ticket->workOrder->technicians as $tech)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[11px] font-bold {{ $tech->is_lead ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-white text-slate-800 border border-slate-200' }}">
                                                @if($tech->is_lead)
                                                    <span>👑 Lead:</span>
                                                @else
                                                    <span>👤:</span>
                                                @endif
                                                <span>{{ $tech->technician_name }}</span>
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="font-bold text-slate-800">{{ $ticket->workOrder->technician_name }}</span>
                                    @endif
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1 font-mono">No. SPK: {{ $ticket->workOrder->spk_number }}</p>
                            @else
                                <span class="text-slate-400 italic">Belum ada penugasan teknisi (Menunggu verifikasi NOC)</span>
                            @endif
                        </div>
                    </div>

                    <!-- Kolom 2: Catatan Hasil Perbaikan Lapangan -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/70 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400 font-semibold uppercase text-[10px]">Catatan Hasil Perbaikan Lapangan</span>
                                @if($ticket->workOrder && $ticket->workOrder->resolved_at)
                                    <span class="text-[10px] font-mono text-emerald-700 font-semibold">
                                        Tuntas: {{ $ticket->workOrder->resolved_at->format('d/m/Y H:i') }} WITA
                                    </span>
                                @endif
                            </div>
                            <div class="mt-1.5 p-3 rounded-lg bg-white border border-slate-200 font-mono text-xs text-slate-800 leading-relaxed min-h-16">
                                @if($ticket->workOrder && $ticket->workOrder->tech_notes)
                                    {{ $ticket->workOrder->tech_notes }}
                                @elseif($isSolved)
                                    Redaman sinyal optik telah kembali normal (-18 dBm) dan koneksi internet stabil.
                                @else
                                    <span class="text-slate-400 italic font-sans text-xs">Pekerjaan perbaikan masih berlangsung oleh tim lapangan...</span>
                                @endif
                            </div>
                        </div>

                        @if($ticket->workOrder && $ticket->workOrder->materialsOutbound && $ticket->workOrder->materialsOutbound->count() > 0)
                            <div class="mt-2 pt-2 border-t border-slate-200/60 flex items-center gap-2 flex-wrap text-[11px]">
                                <span class="text-slate-400 font-semibold">Material Terpakai:</span>
                                @foreach($ticket->workOrder->materialsOutbound as $mat)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-medium text-[10px] border border-blue-200/60">
                                        {{ $mat->material_name }} ({{ $mat->quantity }} {{ $mat->unit }})
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-slate-200/90 p-12 text-center shadow-xs">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-3 text-xl font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900 text-base">Belum Ada Riwayat Keluhan</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Anda belum pernah membuat laporan gangguan jaringan. Layanan serat optik Anda berjalan lancar.
                </p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Detail Riwayat & Linimasa Penanganan Tiket Klien -->
<div id="modalClientTimeline" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl border border-slate-100 max-h-[90vh] flex flex-col animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 shrink-0">
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-mono text-xs font-bold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-lg" id="clientTlTicketNumber"></span>
                    <span id="clientTlStatusBadge" class="text-[11px] font-bold px-2.5 py-0.5 rounded-full"></span>
                </div>
                <h3 class="font-bold text-slate-900 text-base mt-2">Linimasa Penanganan Laporan Jaringan Anda</h3>
            </div>
            <button onclick="document.getElementById('modalClientTimeline').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <div class="overflow-y-auto space-y-4 py-4 pr-1">
            <!-- Ticket Info Card -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/80 text-xs space-y-2">
                <div>
                    <span class="text-slate-400 font-semibold uppercase text-[10px] block">Keluhan yang Dilaporkan</span>
                    <p class="text-slate-900 font-bold text-xs sm:text-sm mt-0.5" id="clientTlIssue"></p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1 border-t border-slate-200/50">
                    <div>
                        <span class="text-slate-400 font-semibold uppercase text-[10px] block">Titik Distribusi ODP</span>
                        <strong class="text-indigo-700 font-mono text-xs" id="clientTlOdp"></strong>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold uppercase text-[10px] block">Waktu Laporan Masuk</span>
                        <span class="text-slate-700 font-mono text-xs" id="clientTlReportedAt"></span>
                    </div>
                </div>
            </div>

            <!-- Team Assigned Box -->
            <div id="clientTlTeamCard" class="bg-blue-50/70 p-4 rounded-xl border border-blue-100 text-xs space-y-2">
                <span class="text-blue-900 font-bold uppercase tracking-wider text-[10px] flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Tim Teknisi Lapangan yang Ditugaskan:
                </span>
                <div id="clientTlTechList" class="flex flex-wrap gap-2 pt-1">
                    <!-- Populated dynamically -->
                </div>
            </div>

            <!-- Timeline Stream -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-3 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Riwayat Perkembangan Penanganan (Audit Log)
                </h4>

                <div class="relative pl-6 space-y-4 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200" id="clientTlLogsContainer">
                    <!-- Dynamic logs -->
                </div>
            </div>
        </div>

        <div class="pt-3 border-t border-slate-100 flex justify-end shrink-0">
            <button type="button" onclick="document.getElementById('modalClientTimeline').classList.add('hidden')"
                    class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors">
                Tutup Linimasa
            </button>
        </div>
    </div>
</div>

<!-- Modal Peta Satelit ODP Distribusi Pelanggan -->
<div id="modalClientOdpMap" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <span class="text-[10px] font-mono font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded" id="clientMapOdpName"></span>
                <h3 class="font-bold text-slate-900 text-base mt-1">Peta Satelit Titik Sambung Serat Optik Anda</h3>
            </div>
            <button onclick="document.getElementById('modalClientOdpMap').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <div class="space-y-3 mt-4">
            <div class="flex items-center justify-between text-xs text-slate-600">
                <span id="clientMapLocation" class="font-bold text-slate-800"></span>
                <span id="clientMapCoords" class="font-mono text-indigo-600 font-semibold text-[11px]"></span>
            </div>

            <div id="clientLeafletMap" class="w-full h-64 rounded-xl border border-slate-200 overflow-hidden bg-slate-900"></div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] text-slate-400">Citra Satelit Resolusi Tinggi (Esri Hybrid)</span>
                <a id="clientGmapsLink" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all">
                    <span>Lihat di Google Maps</span>
                    <span>↗</span>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentFilter = 'all';
let clientMap = null;
let clientMarker = null;
const allNodes = @json($allNodes ?? []);

function setStatusFilter(status) {
    currentFilter = status;
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.className = 'filter-btn px-3.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition-all font-semibold';
    });
    const activeBtn = document.getElementById('btn-filter-' + status);
    if (activeBtn) {
        activeBtn.className = 'filter-btn px-3.5 py-1.5 rounded-lg bg-white text-blue-700 shadow-xs transition-all font-bold';
    }
    applyFilters();
}

function applyFilters() {
    const query = (document.getElementById('searchTicketInput').value || '').toLowerCase().trim();
    const cards = document.querySelectorAll('.history-ticket-card');

    cards.forEach(card => {
        const cardStatus = card.getAttribute('data-status');
        const cardSearch = card.getAttribute('data-search') || '';

        const matchesStatus = (currentFilter === 'all') || (cardStatus === currentFilter);
        const matchesQuery = !query || cardSearch.includes(query);

        if (matchesStatus && matchesQuery) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function openClientTimelineModal(ticket) {
    if (!ticket || !ticket.ticket_number) return;

    document.getElementById('clientTlTicketNumber').innerText = ticket.ticket_number;
    
    // Status Badge
    const stEl = document.getElementById('clientTlStatusBadge');
    stEl.innerText = ticket.status === 'Solved' ? 'Masalah Teratasi' : (ticket.status === 'In Progress' ? 'Sedang Ditangani Teknisi' : 'Menunggu Verifikasi NOC');
    if (ticket.status === 'Solved') {
        stEl.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200';
    } else if (ticket.status === 'In Progress') {
        stEl.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-800 border border-blue-200 animate-pulse';
    } else {
        stEl.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200';
    }

    document.getElementById('clientTlIssue').innerText = ticket.issue || '-';
    document.getElementById('clientTlOdp').innerText = ticket.odp_code || '-';
    
    const repDate = ticket.created_at ? new Date(ticket.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) : '-';
    document.getElementById('clientTlReportedAt').innerText = repDate + ' WITA';

    // Team Card
    const teamCard = document.getElementById('clientTlTeamCard');
    const techListEl = document.getElementById('clientTlTechList');
    techListEl.innerHTML = '';

    if (ticket.work_order) {
        teamCard.classList.remove('hidden');

        if (ticket.work_order.technicians && ticket.work_order.technicians.length > 0) {
            ticket.work_order.technicians.forEach(t => {
                const isLead = t.role_in_team === 'Team Lead';
                techListEl.innerHTML += `
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white border border-blue-200 text-xs shadow-xs">
                        <span class="w-2 h-2 rounded-full ${isLead ? 'bg-amber-500' : 'bg-blue-500'}"></span>
                        <strong class="text-slate-800">${t.technician_name}</strong>
                        <span class="text-[10px] ${isLead ? 'bg-amber-100 text-amber-800 font-bold' : 'bg-slate-100 text-slate-600'} px-1.5 py-0.5 rounded">
                            ${t.role_in_team || 'Teknisi'}
                        </span>
                    </div>
                `;
            });
        } else if (ticket.work_order.technician_name) {
            techListEl.innerHTML = `
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white border border-blue-200 text-xs">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    <strong class="text-slate-800">${ticket.work_order.technician_name}</strong>
                </div>
            `;
        }
    } else {
        teamCard.classList.add('hidden');
    }

    // Render Logs
    const container = document.getElementById('clientTlLogsContainer');
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
                                Diperbarui oleh: ${log.actor_name}
                            </span>
                        </div>
                        <p class="text-xs text-slate-700 leading-relaxed font-sans">${log.description}</p>
                        ${log.notes ? `
                            <div class="mt-2 p-2.5 rounded-xl bg-slate-50 text-[11px] text-slate-600 border border-slate-200/70 font-mono">
                                <strong>Catatan:</strong> ${log.notes}
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
                    <p class="font-bold text-xs text-slate-900">Laporan Tercatat di Sistem</p>
                    <p class="text-xs text-slate-500 mt-1">Laporan berhasil dicatat dan masuk dalam antrean pemantauan tim NOC.</p>
                </div>
            </div>
        `;
    }

    document.getElementById('modalClientTimeline').classList.remove('hidden');
}

function openClientMap(odpCode, locationText) {
    document.getElementById('clientMapOdpName').innerText = odpCode || 'ODP-BJM-KYT-01';
    document.getElementById('clientMapLocation').innerText = locationText || 'Kayutangi, Banjarmasin Utara';
    document.getElementById('modalClientOdpMap').classList.remove('hidden');

    const node = allNodes.find(n => n.name === odpCode);
    let lat = -3.32;
    let lng = 114.60;

    if (node && node.coordinates) {
        const parts = node.coordinates.split(',');
        if (parts.length === 2) {
            lat = parseFloat(parts[0].trim());
            lng = parseFloat(parts[1].trim());
            document.getElementById('clientMapCoords').innerText = node.coordinates;
        }
    } else {
        document.getElementById('clientMapCoords').innerText = '-3.32, 114.60';
    }

    document.getElementById('clientGmapsLink').href = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;

    setTimeout(() => {
        if (!clientMap) {
            const esriSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19
            });
            const esriLabels = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19
            });
            const hybrid = L.layerGroup([esriSatellite, esriLabels]);

            clientMap = L.map('clientLeafletMap', {
                center: [lat, lng],
                zoom: 16,
                layers: [hybrid]
            });
        } else {
            clientMap.invalidateSize();
            clientMap.setView([lat, lng], 16);
        }

        if (clientMarker) {
            clientMap.removeLayer(clientMarker);
        }

        const iconHtml = `
            <div style="position: relative; width: 30px; height: 30px;">
                <span style="position: absolute; inset: 0; border-radius: 50%; background: #6366f1; opacity: 0.75; animation: ping 1.2s cubic-bezier(0, 0, 0.2, 1) infinite;"></span>
                <div style="position: relative; background: #4f46e5; width: 30px; height: 30px; border-radius: 50%; border: 2.5px solid #ffffff; box-shadow: 0 4px 12px rgba(79,70,229,0.6); display: flex; align-items: center; justify-content: center; color: white; font-size: 13px; font-weight: bold;">
                    📡
                </div>
            </div>`;

        const pin = L.divIcon({
            className: 'client-map-pin',
            html: iconHtml,
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });

        clientMarker = L.marker([lat, lng], { icon: pin }).addTo(clientMap);
        clientMarker.bindPopup(`<b>${odpCode}</b><br><span style="font-size:10px;">${locationText}</span>`).openPopup();
    }, 200);
}
</script>
@endpush
@endsection
