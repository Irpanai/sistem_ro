@extends('layouts.app', ['title' => 'Portal Layanan Pelanggan'])

@section('content')
<div class="space-y-6">
    <!-- Welcome Customer Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-blue-950 to-indigo-950 text-white p-6 sm:p-8 rounded-3xl shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-500/10 rounded-full blur-2xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 text-blue-200 text-xs font-semibold backdrop-blur-md mb-3 border border-blue-500/30">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Portal Mandiri Pelanggan (Self-Service)
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Halo, {{ auth()->user()?->name ?? 'Pelanggan Setia' }}
                </h1>
                <p class="text-slate-300 text-xs sm:text-sm mt-1.5 max-w-xl leading-relaxed">
                    Pantau status koneksi serat optik rumah/kantor Anda, laporkan kendala secara instan, dan pantau kedatangan teknisi lapangan secara transparan.
                </p>
            </div>

            <button onclick="document.getElementById('modalLaporKlien').classList.remove('hidden')"
                    class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/30 transition-all hover:scale-[1.02] active:scale-[0.98] text-xs sm:text-sm whitespace-nowrap">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Buat Laporan Gangguan Baru
            </button>
        </div>
    </div>

    <!-- Connection Diagnostic Status (3 Cards - Equal Padding) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Paket Langganan</p>
                <h3 class="text-base sm:text-lg font-bold text-slate-900 mt-0.5">Fiber Ultra 100 Mbps</h3>
                <p class="text-[11px] text-emerald-600 font-semibold mt-0.5">Aktif &bull; Unlimited Kuota</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <circle cx="12" cy="12" r="2"></circle>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.24 7.76a6 6 0 010 8.49m-8.48-.01a6 6 0 010-8.49m11.31-2.82a10 10 0 010 14.14m-14.14 0a10 10 0 010-14.14"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">Titik Sambung Distribusi</p>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 font-mono mt-0.5">ODP-BJM-KYT-01</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Kayutangi, Banjarmasin Utara</p>
                </div>
            </div>
            <button onclick="openClientMap('ODP-BJM-KYT-01', 'Kayutangi, Banjarmasin Utara')"
                    class="p-2 sm:px-3 sm:py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-colors shrink-0"
                    title="Buka Peta Satelit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                    <line x1="8" y1="2" x2="8" y2="18"></line>
                    <line x1="16" y1="6" x2="16" y2="22"></line>
                </svg>
                <span class="hidden sm:inline">Peta Satelit</span>
            </button>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl {{ $activeTicketsCount > 0 ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center shrink-0">
                @if($activeTicketsCount > 0)
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @endif
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Status Pengaduan Anda</p>
                <h3 class="text-base sm:text-lg font-bold text-slate-900 font-mono mt-0.5">
                    {{ $activeTicketsCount }} Tiket Aktif
                </h3>
                <a href="{{ route('klien.riwayat') }}" class="inline-flex items-center gap-1 text-[11px] text-blue-600 font-bold hover:underline mt-1">
                    <span>Buka Riwayat Selengkapnya</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Tickets & Tracking Timeline with Filter Tabs -->
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="text-base sm:text-lg font-bold text-slate-900">
                    Status &amp; Riwayat Laporan Pengaduan Anda
                </h2>
            </div>

            <!-- Tab Filter Buttons -->
            <div class="inline-flex p-1 bg-slate-100 rounded-xl border border-slate-200 text-xs font-semibold self-start sm:self-auto">
                <button type="button" onclick="filterClientTickets('all')" id="tab-client-all"
                        class="tab-client-btn px-3.5 py-1.5 rounded-lg bg-white text-blue-700 shadow-xs transition-all font-bold">
                    Semua ({{ $tickets->count() }})
                </button>
                <button type="button" onclick="filterClientTickets('active')" id="tab-client-active"
                        class="tab-client-btn px-3.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition-all">
                    Laporan Aktif ({{ $activeTicketsCount }})
                </button>
                <button type="button" onclick="filterClientTickets('solved')" id="tab-client-solved"
                        class="tab-client-btn px-3.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition-all">
                    Riwayat Selesai ({{ $solvedTicketsCount }})
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5" id="clientTicketsContainer">
            @forelse($tickets as $ticket)
                @php
                    $isSolved = $ticket->status === 'Solved';
                    $step = match($ticket->status) {
                        'Pending' => 1,
                        'In Progress' => 2,
                        'Solved' => 3,
                        default => 1
                    };
                    $ticketJson = json_encode($ticket);
                @endphp
                <div class="client-ticket-card bg-white rounded-2xl border {{ $isSolved ? 'border-slate-200/90' : 'border-blue-300 ring-2 ring-blue-500/10' }} shadow-xs p-6 space-y-5"
                     data-status="{{ $isSolved ? 'solved' : 'active' }}">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-mono text-xs font-bold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-lg">
                                    {{ $ticket->ticket_number }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    Dilaporkan {{ $ticket->created_at->format('d M Y, H:i') }} WITA
                                </span>
                                @if($ticket->odp_code)
                                    <button type="button" onclick="openClientMap('{{ $ticket->odp_code }}', '{{ $ticket->area }}')"
                                            class="inline-flex items-center gap-1 font-mono text-[11px] font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-2 py-0.5 rounded-md transition-colors"
                                            title="Buka Peta Satelit ODP">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                                        </svg>
                                        <span>ODP: {{ $ticket->odp_code }}</span>
                                    </button>
                                @endif
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm sm:text-base mt-2">{{ $ticket->issue }}</h3>
                        </div>

                        <div class="flex items-center gap-2">
                            <!-- Button Riwayat & Linimasa Lengkap -->
                            <button type="button" onclick="openClientTimelineModal({{ htmlspecialchars($ticketJson, ENT_QUOTES, 'UTF-8') }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition-all border border-indigo-200/60"
                                    title="Lihat Detail Riwayat & Linimasa Penanganan">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Lihat Riwayat &amp; Linimasa</span>
                            </button>

                            @if($isSolved)
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    Masalah Telah Diperbaiki
                                </span>
                            @elseif($ticket->status === 'In Progress')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200 animate-pulse">
                                    Teknisi Sedang Menangani
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                    Menunggu Verifikasi NOC
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- 3-Step Visual Progress Stepper -->
                    <div class="relative py-2">
                        <div class="hidden sm:block absolute top-1/2 left-8 right-8 h-0.5 bg-slate-200 -translate-y-1/2 -z-0"></div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 relative z-10">
                            <!-- Step 1 -->
                            <div class="flex items-center sm:flex-col sm:text-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs bg-blue-600 text-white shadow-md shadow-blue-500/30">
                                    1
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">Laporan Diterima</h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Tiket tercatat di sistem NOC</p>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="flex items-center sm:flex-col sm:text-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs {{ $step >= 2 ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30' : 'bg-slate-100 text-slate-400' }}">
                                    2
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold {{ $step >= 2 ? 'text-slate-900' : 'text-slate-400' }}">Teknisi Ditugaskan</h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        {{ $ticket->workOrder ? 'SPK Terbit: ' . ($ticket->workOrder->technician_name ?: 'Tim Lapangan') : 'Menunggu SPK NOC' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex items-center sm:flex-col sm:text-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs {{ $step >= 3 ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/30' : 'bg-slate-100 text-slate-400' }}">
                                    3
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold {{ $step >= 3 ? 'text-emerald-700' : 'text-slate-400' }}">Selesai (Solved)</h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        {{ $ticket->status === 'Solved' ? 'Internet Normal Kembali' : 'Dalam Pengerjaan' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tim Teknisi Bertugas Info Box jika ada SPK -->
                    @if($ticket->workOrder)
                        <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-slate-700 flex items-center gap-1.5 uppercase text-[10px] tracking-wider">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Tim Teknisi Ditugaskan:
                                </span>
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
                                    <span class="font-bold text-slate-900">{{ $ticket->workOrder->technician_name }}</span>
                                @endif
                            </div>
                            <span class="font-mono text-[11px] text-slate-500 font-medium">
                                No. SPK: {{ $ticket->workOrder->spk_number }}
                            </span>
                        </div>
                    @endif

                    @if($ticket->workOrder && $ticket->workOrder->tech_notes)
                        <div class="bg-emerald-50/70 p-4 rounded-xl border border-emerald-200 text-xs">
                            <span class="font-bold text-emerald-950 block mb-1">Catatan Hasil Perbaikan Teknisi:</span>
                            <p class="text-emerald-900 font-mono text-xs">{{ $ticket->workOrder->tech_notes }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-slate-200/90 p-12 text-center shadow-xs">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3 text-xl font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base">Tidak Ada Laporan Gangguan</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        Koneksi internet serat optik Anda saat ini berjalan normal dan lancar. Silakan klik tombol di atas jika mengalami kendala.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Lapor Kendala Pelanggan -->
<div id="modalLaporKlien" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                Formulir Laporan Gangguan Internet
            </h3>
            <button onclick="document.getElementById('modalLaporKlien').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <form method="POST" action="{{ route('klien.lapor') }}" class="space-y-4 mt-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Gejala Utama Gangguan *</label>
                <select name="issue_category" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    <option value="Lampu LOS Modem Berkedip Merah">Lampu LOS Modem Berkedip Merah (Kabel Putus / Tidak Ada Redaman)</option>
                    <option value="Internet Lemot / Sering RTO (Putus-putus)">Internet Sangat Lemot / Ping Tinggi / Sering RTO</option>
                    <option value="Modem Mati Total / Tersambar Petir">Modem ONT Mati Total (Power padam / Adaptor mati)</option>
                    <option value="Kabel Dropcore Tertimpa Pohon / Kendaraan">Kabel Dropcore Terjuntai / Tersangkut Truk</option>
                    <option value="Lainnya">Kendala Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Jelaskan Tambahan Detail Kendala</label>
                <textarea name="detail" rows="3" placeholder="Contoh: Lampu LOS mulai merah sejak tadi siang setelah hujan lebat..."
                          class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp Aktif *</label>
                    <input type="text" name="phone" value="{{ auth()->user()?->phone ?? '0812-3456-7890' }}" required
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ODP / Area Terdekat *</label>
                    <select name="odp_code" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        @foreach($topologyNodes as $node)
                            <option value="{{ $node->name }}">{{ $node->name }} ({{ $node->location }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Pemasangan Rumah *</label>
                <input type="text" name="address" value="Jl. Hasan Basry No. 45 Kayutangi, Banjarmasin Utara" required
                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalLaporKlien').classList.add('hidden')"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-medium transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm transition-all">
                    Kirim Laporan Gangguan
                </button>
            </div>
        </form>
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

@push('scripts')
<script>
let clientMap = null;
let clientMarker = null;
const allNodes = @json($allNodes ?? []);

function filterClientTickets(status) {
    const cards = document.querySelectorAll('.client-ticket-card');
    cards.forEach(card => {
        if (status === 'all') {
            card.style.display = 'block';
        } else {
            card.style.display = card.getAttribute('data-status') === status ? 'block' : 'none';
        }
    });

    // Update active tab button style
    document.querySelectorAll('.tab-client-btn').forEach(btn => {
        btn.className = 'tab-client-btn px-3.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition-all font-semibold';
    });
    const activeBtn = document.getElementById('tab-client-' + status);
    if (activeBtn) {
        activeBtn.className = 'tab-client-btn px-3.5 py-1.5 rounded-lg bg-white text-blue-700 shadow-xs transition-all font-bold';
    }
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
