@extends('layouts.app', ['title' => 'NOC Dashboard'])

@section('content')
<div class="space-y-6">
    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Network Operations Center (NOC) Dashboard</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Real-time monitoring keluhan jaringan, status transmisi OLT/ODP, dan penugasan teknisi lapangan.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:border-slate-300 text-slate-700 text-xs font-semibold shadow-xs hover:bg-slate-50 transition-all">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>Segarkan Data</span>
            </a>

            <a href="{{ route('topologi.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-sm shadow-indigo-500/20 transition-all hover:scale-[1.01]">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
                </svg>
                <span>GIS Peta Penuh</span>
            </a>

            <a href="{{ route('tiket.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm shadow-blue-500/20 transition-all hover:scale-[1.01]">
                <span>Buka Antrean Tiket</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>

    <!-- TOP SECTION: 4 KPI Cards (Uniform Height & Consistent Padding) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Card 1: Total Active Tickets -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between hover:shadow-md transition-all">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Total Active Tickets
                    </span>
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900 tracking-tight font-mono">
                        {{ $totalActiveTickets }}
                    </span>
                    <span class="text-xs font-medium text-slate-500">Tiket Berjalan</span>
                </div>
            </div>
            <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span class="text-amber-600 font-semibold flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    {{ $pendingTicketsCount }} Pending
                </span>
                <span class="text-blue-600 font-semibold flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    {{ $inProgressTicketsCount }} Dispatched
                </span>
            </div>
        </div>

        <!-- Card 2: Outage Incidents (Red) -->
        <div class="bg-white p-6 rounded-2xl border border-rose-200/90 shadow-xs flex flex-col justify-between relative overflow-hidden hover:shadow-md transition-all">
            <div class="absolute top-0 left-0 right-0 h-1 bg-rose-500"></div>
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-rose-600 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                        Outage Incidents
                    </span>
                    <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-rose-600 tracking-tight font-mono">
                        {{ $outageIncidents }}
                    </span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200/60">
                        Massal / Feeder
                    </span>
                </div>
            </div>
            <div class="mt-4 pt-3.5 border-t border-rose-50 flex items-center justify-between text-xs text-slate-500">
                <span>Dampak: Multi-User / ODP</span>
                <span class="text-rose-600 font-semibold">Prioritas Utama</span>
            </div>
        </div>

        <!-- Card 3: Isolir Incidents (Orange) -->
        <div class="bg-white p-6 rounded-2xl border border-amber-200/90 shadow-xs flex flex-col justify-between relative overflow-hidden hover:shadow-md transition-all">
            <div class="absolute top-0 left-0 right-0 h-1 bg-amber-500"></div>
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-amber-600 uppercase tracking-wider">
                        Isolir Incidents
                    </span>
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-amber-600 tracking-tight font-mono">
                        {{ $isolirIncidents }}
                    </span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200/60">
                        Individu
                    </span>
                </div>
            </div>
            <div class="mt-4 pt-3.5 border-t border-amber-50 flex items-center justify-between text-xs text-slate-500">
                <span>Redaman tinggi / Modem</span>
                <span class="text-amber-700 font-semibold">1 Pelanggan</span>
            </div>
        </div>

        <!-- Card 4: Solved Tickets (Green) -->
        <div class="bg-white p-6 rounded-2xl border border-emerald-200/90 shadow-xs flex flex-col justify-between relative overflow-hidden hover:shadow-md transition-all">
            <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-500"></div>
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">
                        Solved Tickets
                    </span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-emerald-600 tracking-tight font-mono">
                        {{ $solvedTickets }}
                    </span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200/60">
                        Tuntas
                    </span>
                </div>
            </div>
            <div class="mt-4 pt-3.5 border-t border-emerald-50 flex items-center justify-between text-xs text-slate-500">
                <span>SLA Penyelesaian: 95.8%</span>
                <span class="text-emerald-700 font-semibold">Clear</span>
            </div>
        </div>
    </div>

    <!-- MIDDLE SECTION: Network Diagnostic View (Real-time OLT & ODP status + GIS Map) -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <h3 class="text-base font-bold text-slate-900 tracking-tight">
                        Network Diagnostic &amp; Live Fiber GIS Pemetaan Satelit
                    </h3>
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Live telemetry simpul transmisi fiber optic wilayah Banjarmasin dan Barito Kuala (BTL).
                </p>
            </div>

            <!-- Area & Status Filters -->
            <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
                <div class="inline-flex rounded-xl bg-slate-100 p-1 border border-slate-200/80">
                    <button type="submit" name="area" value="Semua" class="px-3 py-1 rounded-lg text-xs font-medium transition-all {{ request('area', 'Semua') === 'Semua' ? 'bg-white text-slate-900 shadow-xs font-semibold' : 'text-slate-600 hover:text-slate-900' }}">
                        Semua Wilayah
                    </button>
                    <button type="submit" name="area" value="Banjarmasin" class="px-3 py-1 rounded-lg text-xs font-medium transition-all {{ request('area') === 'Banjarmasin' ? 'bg-white text-slate-900 shadow-xs font-semibold' : 'text-slate-600 hover:text-slate-900' }}">
                        Banjarmasin
                    </button>
                    <button type="submit" name="area" value="Barito Kuala" class="px-3 py-1 rounded-lg text-xs font-medium transition-all {{ request('area') === 'Barito Kuala' ? 'bg-white text-slate-900 shadow-xs font-semibold' : 'text-slate-600 hover:text-slate-900' }}">
                        Barito Kuala
                    </button>
                </div>

                <select name="status" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    <option value="Semua" {{ request('status', 'Semua') === 'Semua' ? 'selected' : '' }}>Semua Status ({{ $totalNodesCount }})</option>
                    <option value="Online" {{ request('status') === 'Online' ? 'selected' : '' }}>Online ({{ $onlineNodesCount }})</option>
                    <option value="Offline" {{ request('status') === 'Offline' ? 'selected' : '' }}>Offline ({{ $offlineNodesCount }})</option>
                </select>
            </form>
        </div>

        <!-- GIS Satellite Map for NOC -->
        <div class="rounded-xl border border-slate-200/90 overflow-hidden relative shadow-inner">
            <div class="absolute top-3 left-12 z-20 bg-slate-900/80 backdrop-blur-md text-white px-3 py-1.5 rounded-lg text-xs flex items-center gap-2 border border-slate-700">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="font-bold">Live GIS Telemetri</span>
                <span class="text-slate-400">| Layer Satelit Citra Udara Kalsel</span>
            </div>
            <div id="dashboardGisMap" class="w-full h-80 bg-slate-900"></div>
        </div>

        <!-- Nodes Grid Cards: Standardized Height & Aligned Layout -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Status Node Perangkat Lapangan ({{ $topologyNodes->count() }} Simpul Terpantau)
                </h4>
                <span class="text-[11px] text-slate-400">Klik kartu untuk menyorot posisi di peta</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @forelse($topologyNodes as $node)
                    @php
                        $isOnline = $node->status === 'Online';
                        $isOlt = $node->type === 'OLT';
                        $utilizationRate = $node->total_ports > 0 ? round(($node->used_ports / $node->total_ports) * 100) : 0;
                    @endphp
                    <div onclick="panDashboardMap('{{ $node->name }}')"
                         class="cursor-pointer p-4 rounded-xl border transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md flex flex-col justify-between min-h-[185px] {{ $isOnline ? 'bg-white border-slate-200/90 hover:border-slate-300' : 'bg-rose-50/50 border-rose-200 hover:border-rose-300 ring-1 ring-rose-400/40' }}">
                        <div>
                            <!-- Header -->
                            <div class="flex items-center justify-between gap-1 mb-2">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider {{ $isOlt ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/70' : 'bg-sky-50 text-sky-800 border border-sky-200/70' }}">
                                    {{ $node->type }}
                                </span>

                                <span class="inline-flex items-center gap-1.5 text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $isOnline ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-100 text-rose-700 border border-rose-300 animate-pulse' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $isOnline ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ $node->status }}
                                </span>
                            </div>

                            <!-- Name -->
                            <h4 class="font-bold text-xs text-slate-800 font-mono tracking-tight truncate" title="{{ $node->name }}">
                                {{ $node->name }}
                            </h4>

                            <!-- Location with Minimal MapPin Icon -->
                            <div class="text-slate-500 text-[11px] mt-1 flex items-center gap-1 truncate" title="{{ $node->location }}">
                                <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="truncate">{{ $node->area }}</span>
                            </div>

                            <!-- Port Usage Progress -->
                            <div class="mt-3 pt-2.5 border-t border-slate-100">
                                <div class="flex items-center justify-between text-[10px] text-slate-600 mb-1">
                                    <span>Port</span>
                                    <span class="font-mono font-semibold">{{ $node->used_ports }}/{{ $node->total_ports }} ({{ $utilizationRate }}%)</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-1.5 rounded-full {{ ! $isOnline ? 'bg-rose-500' : ($utilizationRate > 85 ? 'bg-amber-500' : 'bg-blue-600') }}" style="width: {{ $utilizationRate }}%"></div>
                                </div>
                            </div>

                            @if(! $isOnline && $node->issue)
                                <div class="mt-2.5 p-2 rounded-lg bg-rose-100/90 border border-rose-200 text-[10px] text-rose-800 font-medium leading-tight">
                                    <strong>Insiden:</strong> {{ $node->issue }}
                                </div>
                            @endif
                        </div>

                        <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400">
                            <span class="font-mono">Live Sync</span>
                            <span class="font-semibold text-slate-700">
                                {{ $node->connected_users ? $node->connected_users . ' User' : $node->connected_odp . ' ODP' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-8 text-center text-slate-400 text-xs">
                        Tidak ada node yang cocok dengan filter.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- BOTTOM SECTION: Split into Two
        Left: Pie Chart (Outage vs Isolir Statistic)
        Right: Technician Performance Table -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left: Pie Chart -->
        <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <h3 class="font-bold text-slate-900 text-sm tracking-tight">
                        Outage vs Isolir Statistic
                    </h3>
                    <span class="text-[10px] px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-semibold font-mono">
                        {{ $totalOutage + $totalIsolir }} Kasus
                    </span>
                </div>
                <p class="text-xs text-slate-500">
                    Perbandingan frekuensi gangguan massal (Outage) vs gangguan individu pelanggan (Isolir).
                </p>
            </div>

            <!-- Chart Canvas -->
            <div class="h-56 my-3 flex items-center justify-center relative">
                <canvas id="outageIsolirChart"></canvas>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-3.5 border-t border-slate-100">
                <div class="p-3 rounded-xl bg-rose-50/70 border border-rose-100">
                    <div class="text-[11px] font-semibold text-rose-700">Outage (Massal)</div>
                    <div class="text-xl font-bold text-rose-600 mt-0.5 font-mono">
                        {{ $totalOutage }} <span class="text-xs font-normal text-slate-500">Tiket</span>
                    </div>
                    <div class="text-[10px] text-slate-500 mt-0.5">
                        {{ $outagePercent }}% dari total keluhan
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-orange-50/70 border border-orange-100">
                    <div class="text-[11px] font-semibold text-orange-700">Isolir (Individu)</div>
                    <div class="text-xl font-bold text-orange-600 mt-0.5 font-mono">
                        {{ $totalIsolir }} <span class="text-xs font-normal text-slate-500">Tiket</span>
                    </div>
                    <div class="text-[10px] text-slate-500 mt-0.5">
                        {{ $isolirPercent }}% dari total keluhan
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Technician Performance Table -->
        <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <h3 class="font-bold text-slate-900 text-sm tracking-tight">
                            Technician Performance &amp; SLA
                        </h3>
                    </div>
                    <span class="text-[11px] font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">
                        Tim Lapangan
                    </span>
                </div>
                <p class="text-xs text-slate-500">
                    Daftar produktivitas penyelesaian tiket per teknisi lapangan regional Kalsel.
                </p>
            </div>

            <div class="overflow-x-auto mt-4">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                            <th class="pb-3 pl-2">Nama Teknisi</th>
                            <th class="pb-3">Wilayah Tugas</th>
                            <th class="pb-3 text-center">Status</th>
                            <th class="pb-3 text-center">Tiket Selesai</th>
                            <th class="pb-3 text-right pr-2">Rating</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($technicians as $index => $tech)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 pl-2">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-[11px] border border-slate-200 font-mono">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900">{{ $tech->name }}</div>
                                            <div class="text-[10px] text-slate-400 font-mono">{{ $tech->username }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3 text-slate-600">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        {{ $tech->area }}
                                    </span>
                                </td>

                                <td class="py-3 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $tech->technician_status === 'Available' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $tech->technician_status === 'Available' ? 'bg-emerald-500' : 'bg-blue-500' }}"></span>
                                        {{ $tech->technician_status }}
                                    </span>
                                </td>

                                <td class="py-3 text-center font-bold text-slate-900 font-mono">
                                    <span class="px-2 py-1 rounded-lg bg-slate-100 font-semibold text-slate-800 text-[11px]">
                                        {{ $tech->solved_count + ($tech->username === 'teknisi_rizky' ? 28 : ($tech->username === 'teknisi_noor' ? 19 : 15)) }} Solved
                                    </span>
                                </td>

                                <td class="py-3 text-right pr-2 font-semibold text-amber-600">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3 h-3 text-amber-500 fill-amber-500" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ $tech->rating }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Teknisi siap diterjunkan untuk dispatch Work Order (SPK) baru.</span>
                <span class="font-semibold text-slate-700">Total: {{ $technicians->count() }} Petugas</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let dashboardMap;
    const dashMarkers = {};

    document.addEventListener('DOMContentLoaded', function () {
        // 1. Chart Init
        const ctx = document.getElementById('outageIsolirChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Outage (Massal)', 'Isolir (Individu)'],
                    datasets: [{
                        data: [{{ $totalOutage }}, {{ $totalIsolir }}],
                        backgroundColor: ['#EF4444', '#F97316'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { family: "'Plus Jakarta Sans', sans-serif", size: 12 },
                                padding: 15
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        }

        // 2. Leaflet Satellite Map on Dashboard
        const esriSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri',
            maxZoom: 19
        });

        const esriLabels = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19
        });

        const satelliteHybrid = L.layerGroup([esriSatellite, esriLabels]);

        const osmRoads = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap',
            maxZoom: 19
        });

        dashboardMap = L.map('dashboardGisMap', {
            center: [-3.25, 114.62],
            zoom: 11,
            layers: [satelliteHybrid]
        });

        const baseMaps = {
            "🛰️ Satelit Citra Udara": satelliteHybrid,
            "🗺️ Peta Jalan": osmRoads
        };
        L.control.layers(baseMaps).addTo(dashboardMap);

        // Nodes from Controller
        const nodes = @json($topologyNodes);
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

            let iconHtml = '';
            if (isOlt) {
                iconHtml = `
                    <div style="background: #4f46e5; width: 30px; height: 30px; border-radius: 8px; border: 2px solid #ffffff; box-shadow: 0 4px 10px rgba(79,70,229,0.5); display: flex; align-items: center; justify-content: center; color: white;">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                            <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                        </svg>
                    </div>`;
            } else if (isOnline) {
                iconHtml = `
                    <div style="background: #10b981; width: 22px; height: 22px; border-radius: 50%; border: 2px solid #ffffff; box-shadow: 0 2px 6px rgba(16,185,129,0.4); display: flex; align-items: center; justify-content: center; color: white; font-size: 9px; font-weight: bold;">
                        ✓
                    </div>`;
            } else {
                iconHtml = `
                    <div style="position: relative; width: 24px; height: 24px;">
                        <span style="position: absolute; inset: 0; border-radius: 50%; background: #f43f5e; opacity: 0.75; animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;"></span>
                        <div style="position: relative; background: #e11d48; width: 24px; height: 24px; border-radius: 50%; border: 2px solid #ffffff; box-shadow: 0 3px 10px rgba(225,29,72,0.6); display: flex; align-items: center; justify-content: center; color: white; font-size: 11px; font-weight: bold;">
                            !
                        </div>
                    </div>`;
            }

            const customIcon = L.divIcon({
                className: 'dash-custom-icon',
                html: iconHtml,
                iconSize: [26, 26],
                iconAnchor: [13, 13],
                popupAnchor: [0, -12]
            });

            const marker = L.marker([lat, lng], { icon: customIcon }).addTo(dashboardMap);

            const popupContent = `
                <div style="font-family: 'Plus Jakarta Sans', sans-serif; padding: 4px; min-width: 180px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
                        <span style="font-size: 9px; font-weight: 800; background: ${isOlt ? '#e0e7ff; color: #4338ca;' : '#e0f2fe; color: #0369a1;'} padding: 1px 5px; border-radius: 4px;">
                            ${node.type}
                        </span>
                        <span style="font-size: 10px; font-weight: 700; color: ${isOnline ? '#059669' : '#e11d48'};">
                            ● ${node.status}
                        </span>
                    </div>
                    <div style="font-weight: 800; font-size: 12px; color: #0f172a; font-family: monospace;">
                        ${node.name}
                    </div>
                    <div style="font-size: 10px; color: #64748b; margin-top: 1px;">
                        ${node.location}
                    </div>
                    <div style="margin-top: 6px; padding-top: 4px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; font-size: 10px;">
                        <span style="color: #64748b;">Port:</span>
                        <strong style="color: #0f172a;">${node.used_ports}/${node.total_ports}</strong>
                    </div>
                </div>
            `;

            marker.bindPopup(popupContent);
            dashMarkers[node.name] = { marker, lat, lng };

            // Polyline
            if (!isOlt) {
                const parentOlt = olts.find(o => o.area === node.area) || olts[0];
                if (parentOlt && parentOlt.coordinates) {
                    const oltCoords = parentOlt.coordinates.split(',');
                    const oltLat = parseFloat(oltCoords[0].trim());
                    const oltLng = parseFloat(oltCoords[1].trim());

                    L.polyline([[oltLat, oltLng], [lat, lng]], {
                        color: isOnline ? '#38bdf8' : '#f43f5e',
                        weight: isOnline ? 1.75 : 2,
                        opacity: 0.8,
                        dashArray: isOnline ? null : '5, 5'
                    }).addTo(dashboardMap);
                }
            }
        });
    });

    function panDashboardMap(nodeName) {
        if (dashMarkers[nodeName]) {
            dashboardMap.flyTo([dashMarkers[nodeName].lat, dashMarkers[nodeName].lng], 14, { duration: 1 });
            dashMarkers[nodeName].marker.openPopup();
            document.getElementById('dashboardGisMap').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
</script>
@endpush
@endsection
