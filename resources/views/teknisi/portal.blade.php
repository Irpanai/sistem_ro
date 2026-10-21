@extends('layouts.app', ['title' => 'Portal Tugas Teknisi Lapangan'])

@section('content')
<div class="space-y-6">
    <!-- Header & Engineer Status Bar -->
    <div class="bg-gradient-to-r from-slate-900 via-blue-950 to-indigo-950 text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 text-blue-200 text-xs font-semibold backdrop-blur-md mb-2 border border-blue-500/30">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Field Engineer Operational Dashboard
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Portal Teknisi: {{ auth()->user()?->name ?? 'Teknisi Lapangan' }}
            </h1>
            <p class="text-slate-300 text-xs sm:text-sm mt-1.5 max-w-xl leading-relaxed">
                Daftar Surat Perintah Kerja (SPK), daftar material yang dibawa, dan pelaporan hasil perbaikan redaman serat optik.
            </p>
        </div>

        <div class="flex items-center gap-4 bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/10 shrink-0">
            <div>
                <p class="text-[10px] text-slate-300 uppercase tracking-wider font-semibold">Status Operasional</p>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="w-2.5 h-2.5 rounded-full {{ $activeCount > 0 ? 'bg-amber-400 animate-ping' : 'bg-emerald-400' }}"></span>
                    <span class="text-xs sm:text-sm font-extrabold text-white">
                        {{ $activeCount > 0 ? 'On Field (Bertugas)' : 'Available (Siaga)' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats (2 Cards - Equal Padding) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">SPK Sedang Ditangani</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1 font-mono">{{ $activeCount }} <span class="text-xs font-normal text-slate-500 font-sans">Penugasan Aktif</span></h3>
                <p class="text-xs text-slate-400 mt-1">Selesaikan pekerjaan dan kirim catatan teknis</p>
            </div>
            <div class="w-11 h-11 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Total SPK Selesai (Solved)</p>
                <h3 class="text-3xl font-extrabold text-emerald-600 mt-1 font-mono">{{ $solvedCount }} <span class="text-xs font-normal text-slate-500 font-sans">Tiket Teratasi</span></h3>
                <p class="text-xs text-emerald-600 font-medium mt-1">Kinerja lapangan bulan ini</p>
            </div>
            <div class="w-11 h-11 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Work Orders List -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Daftar Surat Perintah Kerja (SPK) Lapangan
            </h2>
            <span class="text-xs text-slate-500 font-semibold">{{ $workOrders->count() }} Total SPK</span>
        </div>

        <div class="grid grid-cols-1 gap-5">
            @forelse($workOrders as $wo)
                @php
                    $isOutage = $wo->ticket && $wo->ticket->type === 'OUTAGE';
                    $isSolved = $wo->status === 'Solved';
                @endphp
                <div class="bg-white rounded-2xl border {{ $isSolved ? 'border-slate-200/90' : 'border-blue-300 ring-2 ring-blue-500/10' }} p-6 shadow-xs space-y-4">
                    <!-- Top SPK Header -->
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
                        </div>

                        <div>
                            @if($isSolved)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Solved (Selesai Dikerjakan)
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200 animate-pulse">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                    In Progress (Perlu Tindakan)
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Client & Location Info -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/70">
                            <span class="text-slate-400 font-semibold uppercase text-[10px] block">Pelanggan &amp; Lokasi</span>
                            <div class="font-bold text-slate-900 text-xs sm:text-sm mt-0.5">{{ $wo->ticket ? $wo->ticket->customer_name : '-' }}</div>
                            <p class="text-slate-600 mt-1 leading-relaxed">{{ $wo->ticket ? $wo->ticket->address : '-' }}</p>
                            @if($wo->ticket && $wo->ticket->customer_phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $wo->ticket->customer_phone) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 mt-2 text-emerald-700 font-bold hover:underline">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <span>Chat WA: {{ $wo->ticket->customer_phone }}</span>
                                </a>
                            @endif
                        </div>

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
                                <span>Peta Satelit Tiang &amp; Rute GPS</span>
                            </button>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/70">
                            <span class="text-slate-400 font-semibold uppercase text-[10px] block">Material Alokasi SPK</span>
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

                    <!-- Tech Notes & Resolution -->
                    @if($isSolved)
                        <div class="bg-emerald-50/70 p-4 rounded-xl border border-emerald-200 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-emerald-950 uppercase tracking-wider text-[10px]">Catatan Teknis Selesai:</span>
                                <span class="text-emerald-700 font-semibold text-[11px]">
                                    Selesai: {{ $wo->resolved_at ? $wo->resolved_at->format('d M Y, H:i') : '-' }} WITA
                                </span>
                            </div>
                            <p class="font-mono text-emerald-900 mt-1.5 leading-relaxed text-xs">
                                {{ $wo->tech_notes ?: 'Redaman normal -19 dBm, penarikan ulang kabel selesai.' }}
                            </p>
                        </div>
                    @else
                        <!-- Action to Resolve -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-slate-100">
                            <span class="text-xs text-slate-500">
                                Sudah selesai mengecek redaman OPM dan menyambung kabel di rumah pelanggan?
                            </span>
                            <button onclick="openResolveModal('{{ $wo->id }}', '{{ $wo->spk_number }}', '{{ addslashes($wo->ticket ? $wo->ticket->customer_name : '') }}')"
                                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm shadow-emerald-500/20 transition-all hover:scale-[1.01] active:scale-[0.98]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Isi Catatan Teknis &amp; Selesaikan SPK
                            </button>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-slate-200/90 p-12 text-center shadow-xs">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-3 text-xl font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base">Tidak Ada SPK Aktif</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        Saat ini belum ada penugasan baru dari tim NOC. Anda berada dalam status siaga (Available).
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Resolve SPK Teknisi -->
<div id="modalResolve" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <span class="text-[10px] font-mono font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded" id="resSpkNumber"></span>
                <h3 class="font-bold text-slate-900 text-base mt-1">Laporan Penyelesaian SPK Lapangan</h3>
            </div>
            <button onclick="document.getElementById('modalResolve').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <form id="formResolve" method="POST" action="" class="space-y-4 mt-5">
            @csrf
            <div>
                <p class="text-xs text-slate-500">Nama Pelanggan: <strong class="text-slate-900" id="resCustomerName"></strong></p>
            </div>

            <div>
                <label for="tech_notes" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Catatan Teknis / Hasil Pengukuran Redaman *
                </label>
                <textarea id="tech_notes" name="tech_notes" rows="4" required
                          placeholder="Contoh: Kabel dropcore putus di tiang 4 dekat pohon mangga. Sudah disambung ulang menggunakan 1 buah fast connector. Hasil ukur OPM redaman -18.5 dBm normal, lampu PON modem hijau stabil dan koneksi sudah dites bersama pelanggan."
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20"></textarea>
                <p class="text-[11px] text-slate-400 mt-1">Catatan ini akan langsung tersimpan di riwayat tiket pelanggan dan audit log NOC.</p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalResolve').classList.add('hidden')"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-medium transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:scale-[1.01]">
                    Tandai Selesai (Solved)
                </button>
            </div>
        </form>
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
let techMap = null;
let techMarker = null;
const allNodes = @json($allNodes ?? []);

function openResolveModal(id, spkNumber, customerName) {
    document.getElementById('formResolve').action = '/spk/' + id + '/resolve';
    document.getElementById('resSpkNumber').innerText = spkNumber;
    document.getElementById('resCustomerName').innerText = customerName;
    document.getElementById('modalResolve').classList.remove('hidden');
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
