@extends('layouts.app', ['title' => 'Riwayat SPK & Kroscek Tiket Selesai'])

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-blue-950 to-indigo-950 text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-200 text-xs font-semibold backdrop-blur-md mb-2 border border-emerald-500/30">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                Rekap &amp; Kroscek Kinerja Lapangan
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Riwayat SPK &amp; Tiket Selesai
            </h1>
            <p class="text-slate-300 text-xs sm:text-sm mt-1.5 max-w-xl leading-relaxed">
                Histori seluruh pekerjaan perbaikan jaringan yang telah Anda selesaikan. Gunakan halaman ini untuk kroscek hasil ukur OPM, material terpasang, dan tim yang bertugas.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('teknisi.portal') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-2xl shadow-lg shadow-emerald-600/30 transition-all hover:scale-[1.02] active:scale-[0.98] text-xs sm:text-sm whitespace-nowrap">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>Lihat SPK Aktif Saya</span>
            </a>
        </div>
    </div>

    <!-- Quick Metrics (3 Cards - Equal Padding) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Total Tiket / SPK Selesai</p>
                <h3 class="text-3xl font-extrabold text-emerald-600 mt-1 font-mono">{{ $totalSolved }} <span class="text-xs font-normal text-slate-500 font-sans">Tiket Solved</span></h3>
                <p class="text-xs text-slate-400 mt-1">Total kontribusi perbaikan lapangan</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Total Material Terpakai</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1 font-mono">{{ $totalMaterialsUsed }} <span class="text-xs font-normal text-slate-500 font-sans">Item / Unit</span></h3>
                <p class="text-xs text-slate-400 mt-1">Kabel FO, Fast Connector, Sleeve, dll</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Status Verifikasi NOC</p>
                <h3 class="text-3xl font-extrabold text-indigo-600 mt-1 font-mono">100% <span class="text-xs font-normal text-slate-500 font-sans">Terverifikasi</span></h3>
                <p class="text-xs text-indigo-600 font-medium mt-1">Hasil redaman OPM memenuhi standar</p>
            </div>
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Search Input -->
        <div class="relative flex-1 max-w-md">
            <input type="text" id="searchTechRiwayatInput" oninput="applyTechFilters()"
                   placeholder="Kroscek nomor SPK, tiket, pelanggan, ODP, atau catatan..."
                   class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500/20">
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        <!-- Filter Tipe Gangguan -->
        <div class="inline-flex p-1 bg-slate-100 rounded-xl border border-slate-200 text-xs font-semibold self-start sm:self-auto">
            <button type="button" onclick="setTechTypeFilter('all')" id="btn-tech-all"
                    class="tech-filter-btn px-3.5 py-1.5 rounded-lg bg-white text-emerald-700 shadow-xs transition-all font-bold">
                Semua SPK Selesai ({{ $totalSolved }})
            </button>
            <button type="button" onclick="setTechTypeFilter('OUTAGE')" id="btn-tech-outage"
                    class="tech-filter-btn px-3.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition-all">
                Feeder Outage
            </button>
            <button type="button" onclick="setTechTypeFilter('ISOLIR')" id="btn-tech-isolir"
                    class="tech-filter-btn px-3.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition-all">
                Dropcore Isolir
            </button>
        </div>
    </div>

    <!-- SPK History Cards List -->
    <div class="space-y-4" id="techHistoryContainer">
        @forelse($workOrders as $wo)
            @php
                $isOutage = $wo->ticket && $wo->ticket->type === 'OUTAGE';
                $ticketJson = $wo->ticket ? json_encode($wo->ticket) : '{}';
                $searchContent = strtolower(
                    $wo->spk_number . ' ' . 
                    ($wo->ticket?->ticket_number ?? '') . ' ' . 
                    ($wo->ticket?->customer_name ?? '') . ' ' . 
                    ($wo->ticket?->odp_code ?? '') . ' ' . 
                    ($wo->ticket?->issue ?? '') . ' ' . 
                    $wo->tech_notes . ' ' . 
                    $wo->technician_name
                );
            @endphp
            <div class="tech-riwayat-card bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-4 transition-all"
                 data-type="{{ $wo->ticket?->type ?? 'ISOLIR' }}"
                 data-search="{{ $searchContent }}">
                
                <!-- Header Card -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-mono text-xs sm:text-sm font-extrabold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg">
                            {{ $wo->spk_number }}
                        </span>
                        <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg">
                            Ref: {{ $wo->ticket ? $wo->ticket->ticket_number : '-' }}
                        </span>
                        @if($isOutage)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                OUTAGE (Feeder Massal)
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                ISOLIR (Dropcore Lokal)
                            </span>
                        @endif
                        <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Solved (Selesai Tuntas)
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Button Riwayat & Log -->
                        <button type="button" onclick="openTechTimelineModal({{ htmlspecialchars($ticketJson, ENT_QUOTES, 'UTF-8') }})"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition-all border border-indigo-200/60"
                                title="Lihat Kronologi Linimasa & Audit Log">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Audit Log &amp; Linimasa</span>
                        </button>
                    </div>
                </div>

                <!-- Tim Teknisi Bertugas Info Box -->
                <div class="bg-indigo-50/50 border border-indigo-100 p-3 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[10px] font-bold text-indigo-900 uppercase tracking-wider flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Rekan Tim yang Bertugas:
                        </span>
                        @if($wo->technicians && $wo->technicians->count() > 0)
                            @foreach($wo->technicians as $tech)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $tech->is_lead ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-white text-slate-800 border border-slate-200' }}">
                                    @if($tech->is_lead)
                                        <span class="text-amber-600">👑 Lead:</span>
                                    @else
                                        <span class="text-blue-600">👤 Tim:</span>
                                    @endif
                                    <span>{{ $tech->technician_name }}</span>
                                </span>
                            @endforeach
                        @else
                            <span class="font-bold text-indigo-950">{{ $wo->technician_name ?: 'Teknisi Lapangan' }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 text-[11px] text-slate-500 font-mono">
                        <span>Dispatched: {{ $wo->dispatched_at ? $wo->dispatched_at->format('d/m H:i') : '-' }}</span>
                        <span>&bull;</span>
                        <span class="text-emerald-700 font-bold">Solved: {{ $wo->resolved_at ? $wo->resolved_at->format('d/m/Y H:i') : '-' }} WITA</span>
                    </div>
                </div>

                <!-- 3 Columns Detail (Pelanggan & Lokasi, Topologi & Keluhan, Material Terpakai) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                    <!-- Col 1: Pelanggan & Alamat -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/70">
                        <span class="text-slate-400 font-semibold uppercase text-[10px] block">Pelanggan &amp; Alamat</span>
                        <div class="font-bold text-slate-900 text-xs sm:text-sm mt-0.5">{{ $wo->ticket ? $wo->ticket->customer_name : '-' }}</div>
                        <p class="text-slate-600 mt-1 leading-relaxed">{{ $wo->ticket ? $wo->ticket->address : '-' }}</p>
                        @if($wo->ticket && $wo->ticket->customer_phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $wo->ticket->customer_phone) }}" target="_blank"
                               class="inline-flex items-center gap-1.5 mt-2 text-emerald-700 font-bold hover:underline">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span>WA: {{ $wo->ticket->customer_phone }}</span>
                            </a>
                        @endif
                    </div>

                    <!-- Col 2: Simpul ODP & Gejala -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/70 flex flex-col justify-between">
                        <div>
                            <span class="text-slate-400 font-semibold uppercase text-[10px] block">Simpul Topologi &amp; Keluhan</span>
                            <div class="font-bold text-slate-900 font-mono text-xs mt-0.5">
                                {{ $wo->ticket ? $wo->ticket->odp_code : '-' }} ({{ $wo->ticket ? $wo->ticket->area : '-' }})
                            </div>
                            <p class="text-slate-700 mt-1.5 line-clamp-2 bg-white p-2.5 rounded-lg border border-slate-200 text-[11px] leading-relaxed">
                                "{{ $wo->ticket ? $wo->ticket->issue : '-' }}"
                            </p>
                        </div>
                        <button type="button" onclick="openTechOdpMap('{{ $wo->ticket ? $wo->ticket->odp_code : '' }}', '{{ addslashes($wo->ticket ? $wo->ticket->customer_name : '') }}', '{{ addslashes($wo->ticket ? $wo->ticket->address : '') }}')"
                                class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-[11px] font-bold mt-2.5 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 1 6"></polygon>
                                <line x1="8" y1="2" x2="8" y2="18"></line>
                                <line x1="16" y1="6" x2="16" y2="22"></line>
                            </svg>
                            <span>Kroscek Lokasi di Peta Satelit</span>
                        </button>
                    </div>

                    <!-- Col 3: Material Terpakai -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/70">
                        <span class="text-slate-400 font-semibold uppercase text-[10px] block">Material Logistik Terpakai</span>
                        <div class="mt-1.5 space-y-1.5 max-h-24 overflow-y-auto">
                            @forelse($wo->materialsOutbound as $mat)
                                <div class="flex items-center justify-between text-[11px] bg-white px-2.5 py-1.5 rounded-lg border border-slate-200">
                                    <span class="font-semibold text-slate-800">{{ $mat->material_name }}</span>
                                    <span class="font-bold text-blue-700 font-mono">{{ $mat->quantity }} {{ $mat->unit }}</span>
                                </div>
                            @empty
                                <p class="text-slate-400 italic text-[11px] py-1">Tidak ada material gudang yang diambil.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Catatan Teknis Selesai & Hasil OPM -->
                <div class="bg-emerald-50/70 p-4 rounded-xl border border-emerald-200 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-emerald-950 uppercase tracking-wider text-[10px] flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Catatan Teknis &amp; Hasil Ukur Redaman OPM yang Telah Diinput:
                        </span>
                        <span class="text-emerald-700 font-mono text-[11px] font-bold">
                            Tuntas Diverifikasi
                        </span>
                    </div>
                    <p class="font-mono text-emerald-900 mt-1.5 leading-relaxed text-xs bg-white/70 p-3 rounded-lg border border-emerald-200">
                        {{ $wo->tech_notes ?: 'Redaman normal -19 dBm, penarikan ulang kabel selesai dan koneksi dites stabil.' }}
                    </p>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-slate-200/90 p-12 text-center shadow-xs">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3 text-xl font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900 text-base">Belum Ada Riwayat SPK Selesai</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    SPK yang Anda selesaikan di lapangan akan otomatis tercatat di sini untuk kroscek dan rekap performa bulanan.
                </p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Riwayat & Linimasa Tiket untuk Teknisi -->
<div id="modalTechTimeline" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl border border-slate-100 max-h-[90vh] flex flex-col animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 shrink-0">
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-mono text-xs font-bold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-lg" id="techTlTicketNumber"></span>
                    <span id="techTlStatusBadge" class="text-[11px] font-bold px-2.5 py-0.5 rounded-full"></span>
                    <span id="techTlTypeBadge" class="text-[11px] font-bold px-2 py-0.5 rounded-md"></span>
                </div>
                <h3 class="font-bold text-slate-900 text-base mt-2">Riwayat &amp; Linimasa Penanganan Gangguan</h3>
            </div>
            <button onclick="document.getElementById('modalTechTimeline').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <div class="overflow-y-auto space-y-4 py-4 pr-1">
            <!-- Ticket Info Card -->
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/80 text-xs space-y-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <div>
                        <span class="text-slate-400 font-semibold uppercase text-[10px] block">Pelanggan</span>
                        <strong class="text-slate-900 text-xs" id="techTlCustomer"></strong>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold uppercase text-[10px] block">Simpul Topologi ODP</span>
                        <strong class="text-slate-900 font-mono text-xs" id="techTlOdp"></strong>
                    </div>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold uppercase text-[10px] block">Alamat</span>
                    <p class="text-slate-700" id="techTlAddress"></p>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold uppercase text-[10px] block">Gejala Gangguan</span>
                    <p class="text-slate-800 font-medium" id="techTlIssue"></p>
                </div>
            </div>

            <!-- Team Assigned Card -->
            <div id="techTlTeamCard" class="bg-indigo-50/60 p-4 rounded-xl border border-indigo-100 text-xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-indigo-900 font-bold uppercase tracking-wider text-[10px] flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Tim Teknisi Bertugas (SPK: <span id="techTlSpkNumber" class="font-mono"></span>)
                    </span>
                </div>
                <div id="techTlTechList" class="flex flex-wrap gap-2 pt-1">
                    <!-- Populated dynamically -->
                </div>
            </div>

            <!-- Timeline Stream -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-3 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Linimasa Aktivitas &amp; Log Lapangan
                </h4>

                <div class="relative pl-6 space-y-4 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200" id="techTlLogsContainer">
                    <!-- Dynamic logs -->
                </div>
            </div>
        </div>

        <div class="pt-3 border-t border-slate-100 flex justify-end shrink-0">
            <button type="button" onclick="document.getElementById('modalTechTimeline').classList.add('hidden')"
                    class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors">
                Tutup Linimasa
            </button>
        </div>
    </div>
</div>

<!-- Modal Peta Satelit Lokasi Tiang ODP untuk Teknisi -->
<div id="modalTechMap" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <span class="text-[10px] font-mono font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded" id="techMapOdpName"></span>
                <h3 class="font-bold text-slate-900 text-base mt-1">Peta Satelit Lokasi Tiang &amp; Jalur Navigasi</h3>
            </div>
            <button onclick="document.getElementById('modalTechMap').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <div class="space-y-3 mt-4">
            <div class="flex items-center justify-between text-xs text-slate-600">
                <span id="techMapCustomer" class="font-bold text-slate-900"></span>
                <span id="techMapCoords" class="font-mono text-blue-600 font-semibold"></span>
            </div>
            <p id="techMapAddress" class="text-xs text-slate-500"></p>

            <div id="techLeafletMap" class="w-full h-64 rounded-xl border border-slate-200 overflow-hidden bg-slate-900"></div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] text-slate-400">Citra Satelit Esri &amp; OpenStreetMap</span>
                <a id="techGmapsLink" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all">
                    <span>Mulai Navigasi di Google Maps</span>
                    <span>↗</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
let currentTechTypeFilter = 'all';
let techMap = null;
let techMarker = null;
const allNodes = @json($allNodes ?? []);

function setTechTypeFilter(type) {
    currentTechTypeFilter = type;
    document.querySelectorAll('.tech-filter-btn').forEach(btn => {
        btn.className = 'tech-filter-btn px-3.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition-all font-semibold';
    });
    const idMap = { 'all': 'btn-tech-all', 'OUTAGE': 'btn-tech-outage', 'ISOLIR': 'btn-tech-isolir' };
    const activeBtn = document.getElementById(idMap[type]);
    if (activeBtn) {
        activeBtn.className = 'tech-filter-btn px-3.5 py-1.5 rounded-lg bg-white text-emerald-700 shadow-xs transition-all font-bold';
    }
    applyTechFilters();
}

function applyTechFilters() {
    const query = (document.getElementById('searchTechRiwayatInput').value || '').toLowerCase().trim();
    const cards = document.querySelectorAll('.tech-riwayat-card');

    cards.forEach(card => {
        const cardType = card.getAttribute('data-type') || '';
        const cardSearch = card.getAttribute('data-search') || '';

        const matchesType = (currentTechTypeFilter === 'all') || (cardType === currentTechTypeFilter);
        const matchesQuery = !query || cardSearch.includes(query);

        if (matchesType && matchesQuery) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function openTechTimelineModal(ticket) {
    if (!ticket || !ticket.ticket_number) return;

    document.getElementById('techTlTicketNumber').innerText = ticket.ticket_number;
    
    // Status Badge
    const stEl = document.getElementById('techTlStatusBadge');
    stEl.innerText = ticket.status;
    if (ticket.status === 'Solved') {
        stEl.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200';
    } else if (ticket.status === 'In Progress') {
        stEl.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-800 border border-blue-200 animate-pulse';
    } else {
        stEl.className = 'text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200';
    }

    // Type Badge
    const tpEl = document.getElementById('techTlTypeBadge');
    tpEl.innerText = ticket.type;
    tpEl.className = ticket.type === 'OUTAGE'
        ? 'text-[11px] font-bold px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200'
        : 'text-[11px] font-bold px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200';

    document.getElementById('techTlCustomer').innerText = ticket.customer_name + (ticket.customer_phone ? ' (' + ticket.customer_phone + ')' : '');
    document.getElementById('techTlAddress').innerText = (ticket.address || '') + (ticket.area ? ', ' + ticket.area : '');
    document.getElementById('techTlOdp').innerText = ticket.odp_code || '-';
    document.getElementById('techTlIssue').innerText = ticket.issue || '-';

    // Team Card
    const teamCard = document.getElementById('techTlTeamCard');
    const techListEl = document.getElementById('techTlTechList');
    techListEl.innerHTML = '';

    if (ticket.work_order) {
        teamCard.classList.remove('hidden');
        document.getElementById('techTlSpkNumber').innerText = ticket.work_order.spk_number || '-';

        if (ticket.work_order.technicians && ticket.work_order.technicians.length > 0) {
            ticket.work_order.technicians.forEach(t => {
                const isLead = t.role_in_team === 'Team Lead';
                techListEl.innerHTML += `
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-indigo-200 text-xs shadow-xs">
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
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-indigo-200 text-xs">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    <strong class="text-slate-800">${ticket.work_order.technician_name}</strong>
                </div>
            `;
        }
    } else {
        teamCard.classList.add('hidden');
    }

    // Render Logs
    const container = document.getElementById('techTlLogsContainer');
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
                                ${log.actor_name} (${log.actor_role.toUpperCase()})
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
                    <p class="font-bold text-xs text-slate-900">Tiket Terdaftar di Sistem</p>
                    <p class="text-xs text-slate-500 mt-1">Laporan berhasil dicatat dan masuk dalam antrean pemantauan NOC.</p>
                </div>
            </div>
        `;
    }

    document.getElementById('modalTechTimeline').classList.remove('hidden');
}

function openTechOdpMap(odpCode, customerName, address) {
    document.getElementById('techMapOdpName').innerText = odpCode || 'Simpul ODP';
    document.getElementById('techMapCustomer').innerText = customerName ? 'Pelanggan: ' + customerName : '';
    document.getElementById('techMapAddress').innerText = address || '';
    document.getElementById('modalTechMap').classList.remove('hidden');

    const node = allNodes.find(n => n.name === odpCode);
    let lat = -3.32;
    let lng = 114.60;

    if (node && node.coordinates) {
        const parts = node.coordinates.split(',');
        if (parts.length === 2) {
            lat = parseFloat(parts[0].trim());
            lng = parseFloat(parts[1].trim());
            document.getElementById('techMapCoords').innerText = node.coordinates;
        }
    } else {
        document.getElementById('techMapCoords').innerText = '-3.32, 114.60';
    }

    document.getElementById('techGmapsLink').href = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;

    setTimeout(() => {
        if (!techMap) {
            const esriSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19
            });
            const esriLabels = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19
            });
            const hybrid = L.layerGroup([esriSatellite, esriLabels]);

            techMap = L.map('techLeafletMap', {
                center: [lat, lng],
                zoom: 16,
                layers: [hybrid]
            });
        } else {
            techMap.invalidateSize();
            techMap.setView([lat, lng], 16);
        }

        if (techMarker) {
            techMap.removeLayer(techMarker);
        }

        const iconHtml = `
            <div style="position: relative; width: 30px; height: 30px;">
                <span style="position: absolute; inset: 0; border-radius: 50%; background: #2563eb; opacity: 0.75; animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;"></span>
                <div style="position: relative; background: #1d4ed8; width: 30px; height: 30px; border-radius: 50%; border: 2.5px solid #ffffff; box-shadow: 0 4px 12px rgba(29,78,216,0.6); display: flex; align-items: center; justify-content: center; color: white; font-size: 13px; font-weight: bold;">
                    📍
                </div>
            </div>`;

        const pin = L.divIcon({
            className: 'tech-map-pin',
            html: iconHtml,
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });

        techMarker = L.marker([lat, lng], { icon: pin }).addTo(techMap);
        techMarker.bindPopup(`<b>${odpCode}</b><br><span style="font-size:10px;">${customerName}</span>`).openPopup();
    }, 200);
}
</script>
@endsection
