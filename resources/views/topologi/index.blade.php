@extends('layouts.app', ['title' => 'Master Data Topologi & Pemetaan GIS'])

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Master Data Topologi &amp; Pemetaan GIS Fiber Optic</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Visualisasi geografis citra satelit simpul OLT, distribusi ODP, dan rute kabel transmisi di Kalimantan Selatan.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <button onclick="zoomArea('bjm')" class="px-3 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-semibold shadow-xs transition-all">
                Fokus Banjarmasin
            </button>
            <button onclick="zoomArea('btl')" class="px-3 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-semibold shadow-xs transition-all">
                Fokus Barito Kuala
            </button>
            <button onclick="document.getElementById('addNodeCard').classList.toggle('hidden')" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm shadow-blue-500/20 transition-all hover:scale-[1.01]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Node</span>
            </button>
        </div>
    </div>

    <!-- MAP SECTION: Interactive GIS Satellite & Road Map -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                        <line x1="8" y1="2" x2="8" y2="18"></line>
                        <line x1="16" y1="6" x2="16" y2="22"></line>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900">
                        Peta Geospasial Infrastruktur Fiber Optic (Citra Satelit Rinci)
                    </h2>
                    <p class="text-[11px] text-slate-500">
                        Klik pada pin simpul OLT / ODP untuk melihat data teknis redaman, utilisasi port, dan rute perbaikan.
                    </p>
                </div>
            </div>

            <!-- Legend Info -->
            <div class="flex items-center gap-3 text-xs flex-wrap">
                <span class="inline-flex items-center gap-1.5 font-medium text-slate-700 bg-white px-2.5 py-1 rounded-lg border border-slate-200">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span> OLT Server
                </span>
                <span class="inline-flex items-center gap-1.5 font-medium text-slate-700 bg-white px-2.5 py-1 rounded-lg border border-slate-200">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> ODP Normal
                </span>
                <span class="inline-flex items-center gap-1.5 font-medium text-rose-700 bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-200">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping"></span> ODP Putus (Cut)
                </span>
            </div>
        </div>

        <!-- Map Container -->
        <div id="topologyMap" class="w-full h-[480px] bg-slate-900 z-10"></div>
    </div>

    <!-- FORM INPUT NODE BARU (Input 2) -->
    <div id="addNodeCard" class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs hidden transition-all">
        <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-slate-900">
                    Formulir Registrasi Simpul Baru (OLT / ODP)
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Lengkapi parameter teknis koordinat GPS untuk sinkronisasi pemetaan satelit.
                </p>
            </div>
            <span class="text-[11px] font-semibold px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-200">
                Input 2: Registrasi Node
            </span>
        </div>

        <form action="{{ route('topologi.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nama Node / ODP / OLT <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" required placeholder="Contoh: ODP-BJM-05" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Tipe Perangkat
                    </label>
                    <select name="type" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="ODP">ODP (Optical Distribution Point)</option>
                        <option value="OLT">OLT (Optical Line Terminal)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Wilayah / Area
                    </label>
                    <select name="area" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="Banjarmasin">Banjarmasin</option>
                        <option value="Barito Kuala">Barito Kuala</option>
                        <option value="Banjarbaru">Banjarbaru</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Status Awal
                    </label>
                    <select name="status" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="Online">Online (Aktif Normal)</option>
                        <option value="Offline">Offline (Gangguan / Cut)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Koordinat GPS (Latitude, Longitude)
                    </label>
                    <input type="text" name="coordinates" placeholder="-3.3190, 114.5910" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Alamat Lengkap / Patokan Tiang <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="location" required placeholder="Jl. Sultan Adam Tiang PLN No. 5" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Kapasitas Port
                    </label>
                    <select name="total_ports" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-mono">
                        <option value="8">8 Port</option>
                        <option value="16" selected>16 Port</option>
                        <option value="24">24 Port</option>
                        <option value="32">32 Port</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <span class="text-xs text-slate-500 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Node yang didaftarkan akan otomatis dipetakan pada sistem GIS satelit NOC.
                </span>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition-all">
                    Simpan Node Topologi
                </button>
            </div>
        </form>
    </div>

    <!-- TABLE LISTING CURRENT TOPOLOGY DATA -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-bold text-slate-900">
                    Daftar Node Jaringan Terdaftar
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Menampilkan {{ $nodes->total() }} node jaringan OLT &amp; ODP di wilayah operasional Kalsel.
                </p>
            </div>

            <!-- Search & Filters -->
            <form action="{{ route('topologi.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, alamat..." class="pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 w-48">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <select name="area" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    <option value="Semua">Semua Area</option>
                    <option value="Banjarmasin" {{ request('area') === 'Banjarmasin' ? 'selected' : '' }}>Banjarmasin</option>
                    <option value="Barito Kuala" {{ request('area') === 'Barito Kuala' ? 'selected' : '' }}>Barito Kuala</option>
                </select>

                <select name="type" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    <option value="Semua">Semua Tipe</option>
                    <option value="ODP" {{ request('type') === 'ODP' ? 'selected' : '' }}>ODP</option>
                    <option value="OLT" {{ request('type') === 'OLT' ? 'selected' : '' }}>OLT</option>
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/75 border-b border-slate-100 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-3.5 px-6">Node Name &amp; ID</th>
                        <th class="py-3.5 px-6">Tipe</th>
                        <th class="py-3.5 px-6">Wilayah &amp; Lokasi</th>
                        <th class="py-3.5 px-6 text-center">Port Usage</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($nodes as $node)
                        @php
                            $isOnline = $node->status === 'Online';
                            $usage = $node->total_ports > 0 ? round(($node->used_ports / $node->total_ports) * 100) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-6 font-mono">
                                <div class="font-bold text-slate-900 text-xs flex items-center gap-1.5">
                                    <button onclick="focusNode('{{ $node->name }}')" class="hover:text-blue-600 transition-colors" title="Lihat di Peta">
                                        {{ $node->name }}
                                    </button>
                                </div>
                                <div class="text-[10px] text-slate-400 mt-0.5">{{ $node->node_id }} &bull; {{ $node->coordinates ?? '-' }}</div>
                            </td>

                            <td class="py-4 px-6">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $node->type === 'OLT' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/70' : 'bg-sky-50 text-sky-800 border border-sky-200/70' }}">
                                    {{ $node->type }}
                                </span>
                            </td>

                            <td class="py-4 px-6 max-w-xs">
                                <div class="font-semibold text-slate-900">{{ $node->area }}</div>
                                <div class="text-[11px] text-slate-500 truncate mt-0.5 flex items-center gap-1" title="{{ $node->location }}">
                                    <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    <span class="truncate">{{ $node->location }}</span>
                                </div>
                            </td>

                            <td class="py-4 px-6 text-center">
                                <div class="font-mono text-xs font-semibold text-slate-800">
                                    {{ $node->used_ports }} / {{ $node->total_ports }}
                                </div>
                                <div class="w-20 mx-auto bg-slate-100 rounded-full h-1 mt-1.5 overflow-hidden">
                                    <div class="h-1 rounded-full {{ ! $isOnline ? 'bg-rose-500' : 'bg-blue-600' }}" style="width: {{ $usage }}%"></div>
                                </div>
                            </td>

                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold {{ $isOnline ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200 animate-pulse' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $isOnline ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ $node->status }}
                                </span>
                            </td>

                            <td class="py-4 px-6 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <!-- Toggle Status Button -->
                                    <form action="{{ route('topologi.toggle', $node) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-semibold border transition-all {{ $isOnline ? 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100' : 'bg-emerald-50 border-emerald-200 text-emerald-700 hover:bg-emerald-100' }}">
                                            {{ $isOnline ? 'Simulasi Cut' : 'Pulihkan' }}
                                        </button>
                                    </form>

                                    <!-- Delete Button -->
                                    <form action="{{ route('topologi.destroy', $node) }}" method="POST" class="inline" onsubmit="return confirm('Hapus node {{ $node->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Node">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 text-xs">
                                Tidak ada data node yang cocok dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $nodes->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    let map;
    const markers = {};

    document.addEventListener('DOMContentLoaded', function () {
        // Base Layers
        const esriSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
            maxZoom: 19
        });

        const esriLabels = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19
        });

        // Combined Satellite Hybrid
        const satelliteHybrid = L.layerGroup([esriSatellite, esriLabels]);

        const osmRoads = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        });

        const cartoDark = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png', {
            attribution: '&copy; CartoDB',
            maxZoom: 19
        });

        // Initialize Map centered at Banjarmasin & Barito Kuala
        map = L.map('topologyMap', {
            center: [-3.25, 114.62],
            zoom: 11,
            layers: [satelliteHybrid]
        });

        // Layer Control
        const baseMaps = {
            "🛰️ Citra Satelit HD (Hybrid)": satelliteHybrid,
            "🗺️ Peta Jalan (OpenStreetMap)": osmRoads,
            "🌙 Dark Mode NOC": cartoDark
        };
        L.control.layers(baseMaps).addTo(map);

        // Nodes Data passed from controller
        const nodes = @json($allNodes ?? $nodes->items());

        // Find OLTs for cable connections
        const olts = nodes.filter(n => n.type === 'OLT');

        nodes.forEach(node => {
            if (!node.coordinates) return;
            const parts = node.coordinates.split(',');
            if (parts.length !== 2) return;
            const lat = parseFloat(parts[0].trim());
            const lng = parseFloat(parts[1].trim());
            if (isNaN(lat) || isNaN(lng)) return;

            const isOnline = node.status === 'Online';
            const isOlt = node.type === 'OLT';

            // Custom Icon
            let iconHtml = '';
            if (isOlt) {
                iconHtml = `
                    <div style="background: #4f46e5; width: 34px; height: 34px; border-radius: 10px; border: 2.5px solid #ffffff; box-shadow: 0 4px 12px rgba(79,70,229,0.5); display: flex; align-items: center; justify-content: center; color: white;">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                            <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                            <line x1="6" y1="6" x2="6.01" y2="6"></line>
                            <line x1="6" y1="18" x2="6.01" y2="18"></line>
                        </svg>
                    </div>`;
            } else if (isOnline) {
                iconHtml = `
                    <div style="background: #10b981; width: 26px; height: 26px; border-radius: 50%; border: 2.5px solid #ffffff; box-shadow: 0 3px 8px rgba(16,185,129,0.4); display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; font-weight: bold;">
                        ✓
                    </div>`;
            } else {
                iconHtml = `
                    <div style="position: relative; width: 28px; height: 28px;">
                        <span style="position: absolute; inset: 0; border-radius: 50%; background: #f43f5e; opacity: 0.75; animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;"></span>
                        <div style="position: relative; background: #e11d48; width: 28px; height: 28px; border-radius: 50%; border: 2.5px solid #ffffff; box-shadow: 0 4px 12px rgba(225,29,72,0.6); display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold;">
                            !
                        </div>
                    </div>`;
            }

            const customIcon = L.divIcon({
                className: 'custom-div-icon',
                html: iconHtml,
                iconSize: [30, 30],
                iconAnchor: [15, 15],
                popupAnchor: [0, -15]
            });

            const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);

            const popupContent = `
                <div style="font-family: 'Plus Jakarta Sans', sans-serif; padding: 4px; min-width: 210px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                        <span style="font-size: 9px; font-weight: 800; background: ${isOlt ? '#e0e7ff; color: #4338ca;' : '#e0f2fe; color: #0369a1;'} padding: 2px 6px; border-radius: 4px;">
                            ${node.type}
                        </span>
                        <span style="font-size: 10px; font-weight: 700; color: ${isOnline ? '#059669' : '#e11d48'};">
                            ● ${node.status}
                        </span>
                    </div>
                    <div style="font-weight: 800; font-size: 13px; color: #0f172a; font-family: monospace;">
                        ${node.name}
                    </div>
                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                        ${node.location}
                    </div>
                    <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; font-size: 11px;">
                        <span style="color: #64748b;">Port Terpakai:</span>
                        <strong style="color: #0f172a;">${node.used_ports}/${node.total_ports}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 11px; margin-top: 2px;">
                        <span style="color: #64748b;">Pelanggan:</span>
                        <strong style="color: #2563eb;">${node.connected_users || 0} User</strong>
                    </div>
                    ${!isOnline && node.issue ? `<div style="margin-top: 6px; background: #ffe4e6; color: #9f1239; font-size: 10px; padding: 4px 6px; border-radius: 6px;"><strong>Insiden:</strong> ${node.issue}</div>` : ''}
                    <div style="margin-top: 8px; pt-2;">
                        <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}" target="_blank"
                           style="display: block; text-align: center; background: #2563eb; color: #ffffff; padding: 5px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; text-decoration: none;">
                            📍 Buka Rute Lapangan (Google Maps)
                        </a>
                    </div>
                </div>
            `;

            marker.bindPopup(popupContent);
            markers[node.name] = { marker, lat, lng };

            // Draw Fiber Cables (Polyline) to OLT in same area
            if (!isOlt) {
                const parentOlt = olts.find(o => o.area === node.area) || olts[0];
                if (parentOlt && parentOlt.coordinates) {
                    const oltCoords = parentOlt.coordinates.split(',');
                    const oltLat = parseFloat(oltCoords[0].trim());
                    const oltLng = parseFloat(oltCoords[1].trim());

                    L.polyline([[oltLat, oltLng], [lat, lng]], {
                        color: isOnline ? '#38bdf8' : '#f43f5e',
                        weight: isOnline ? 2 : 2.5,
                        opacity: 0.85,
                        dashArray: isOnline ? null : '6, 6'
                    }).addTo(map);
                }
            }
        });
    });

    function focusNode(name) {
        if (markers[name]) {
            map.flyTo([markers[name].lat, markers[name].lng], 15, { duration: 1.2 });
            markers[name].marker.openPopup();
            document.getElementById('topologyMap').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    function zoomArea(area) {
        if (area === 'bjm') {
            map.flyTo([-3.32, 114.60], 13, { duration: 1 });
        } else if (area === 'btl') {
            map.flyTo([-3.10, 114.65], 11, { duration: 1 });
        }
    }
</script>
@endpush
@endsection
