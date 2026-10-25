@php
    $user = auth()->user();
    $role = $user?->role ?? 'admin';
    $pendingTicketsCount = \App\Models\Ticket::where('status', 'Pending')->count();
    $onlineTopologyCount = \App\Models\TopologyNode::where('status', 'Online')->count();
    $totalTopologyCount = \App\Models\TopologyNode::count();
@endphp

<!-- Mobile Backdrop Overlay -->
<div id="mobileBackdrop" class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-xs hidden lg:hidden transition-opacity"></div>

<!-- Sidebar Drawer -->
<aside id="mobileDrawer" class="fixed lg:sticky top-0 left-0 h-screen w-64 bg-slate-900 border-r border-slate-800/80 flex flex-col shrink-0 select-none z-50 transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0">
    <!-- Brand Header -->
    <div class="h-16 px-5 border-b border-slate-800/80 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 p-0.5 shadow-md shadow-blue-500/20 flex items-center justify-center shrink-0">
                <div class="w-full h-full bg-slate-900 rounded-[10px] flex items-center justify-center text-blue-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
            <div class="min-w-0">
                <span class="font-bold text-white tracking-tight text-sm block truncate">
                    BORNEO FIBER
                </span>
                <p class="text-[10px] text-slate-400 truncate font-medium">
                    SIPKJ &bull; Regional Kalsel
                </p>
            </div>
        </a>

        <!-- Mobile Close Button -->
        <button id="closeDrawerBtn" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <!-- User Role Badge -->
    <div class="px-5 py-2.5 bg-slate-950/40 border-b border-slate-800/60 flex items-center justify-between">
        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">
            Mode Akses
        </span>
        <span class="inline-flex items-center gap-1.5 text-[10px] font-bold px-2 py-0.5 rounded-md {{ $role === 'admin' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : ($role === 'teknisi' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20') }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $role === 'admin' ? 'bg-blue-400' : ($role === 'teknisi' ? 'bg-emerald-400' : 'bg-amber-400') }}"></span>
            {{ $role === 'admin' ? 'Admin Kantor (Banjarmasin)' : ($role === 'teknisi' ? 'Teknisi' : 'Klien') }}
        </span>
    </div>

    <!-- Navigation Menu Items -->
    <div class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <div class="px-3 pb-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">
            Menu Navigasi
        </div>

        @if($role === 'admin')
            <!-- 1. Dashboard -->
            <a href="{{ route('dashboard') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60 font-medium' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs truncate">Dashboard</div>
                    <div class="text-[10px] truncate {{ request()->routeIs('dashboard') ? 'text-blue-100' : 'text-slate-400' }}">Overview &amp; Analytics</div>
                </div>
            </a>

            <!-- 2. Data Topologi -->
            <a href="{{ route('topologi.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left transition-all {{ request()->routeIs('topologi.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60 font-medium' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ request()->routeIs('topologi.*') ? 'text-white' : 'text-slate-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <circle cx="12" cy="5" r="3"></circle>
                        <circle cx="5" cy="19" r="3"></circle>
                        <circle cx="19" cy="19" r="3"></circle>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 0l-5 4m5-4l5 4"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs truncate">Data Topologi</div>
                    <div class="text-[10px] truncate {{ request()->routeIs('topologi.*') ? 'text-blue-100' : 'text-slate-400' }}">Per Wilayah Kecamatan</div>
                </div>
            </a>

            <!-- 3. Logistik Material -->
            <a href="{{ route('logistik.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left transition-all {{ request()->routeIs('logistik.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60 font-medium' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ request()->routeIs('logistik.*') ? 'text-white' : 'text-slate-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs truncate">Logistik Material</div>
                    <div class="text-[10px] truncate {{ request()->routeIs('logistik.*') ? 'text-blue-100' : 'text-slate-400' }}">Master Gudang &amp; Stok</div>
                </div>
            </a>

            <!-- 4. Keluhan & Tiket -->
            <a href="{{ route('tiket.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left transition-all {{ request()->routeIs('tiket.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60 font-medium' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ request()->routeIs('tiket.*') ? 'text-white' : 'text-slate-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs truncate">Keluhan &amp; Tiket</div>
                    <div class="text-[10px] truncate {{ request()->routeIs('tiket.*') ? 'text-blue-100' : 'text-slate-400' }}">Ticketing &amp; SPK</div>
                </div>
                @if($pendingTicketsCount > 0)
                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-rose-500 text-white">
                        {{ $pendingTicketsCount }}
                    </span>
                @endif
            </a>

            <!-- 5. Manajemen User -->
            <a href="{{ route('users.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left transition-all {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60 font-medium' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ request()->routeIs('users.*') ? 'text-white' : 'text-slate-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs truncate">Manajemen User</div>
                    <div class="text-[10px] truncate {{ request()->routeIs('users.*') ? 'text-blue-100' : 'text-slate-400' }}">Klien &amp; Teknisi</div>
                </div>
            </a>

            <!-- 6. Laporan -->
            <a href="{{ route('laporan.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left transition-all {{ request()->routeIs('laporan.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60 font-medium' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ request()->routeIs('laporan.*') ? 'text-white' : 'text-slate-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs truncate">Laporan</div>
                    <div class="text-[10px] truncate {{ request()->routeIs('laporan.*') ? 'text-blue-100' : 'text-slate-400' }}">Audit Pengeluaran</div>
                </div>
            </a>
        @elseif($role === 'teknisi')
            <!-- Teknisi View -->
            <!-- 1. Tugas SPK Saya -->
            <a href="{{ route('teknisi.portal') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left transition-all {{ request()->routeIs('teknisi.portal') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60 font-medium' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ request()->routeIs('teknisi.portal') ? 'text-white' : 'text-slate-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs truncate">Tugas SPK Saya</div>
                    <div class="text-[10px] truncate {{ request()->routeIs('teknisi.portal') ? 'text-emerald-100' : 'text-slate-400' }}">Work Order Lapangan</div>
                </div>
            </a>

            <!-- 2. Riwayat Tiket Selesai (Kroscek Pekerjaan) -->
            <a href="{{ route('teknisi.riwayat') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left transition-all {{ request()->routeIs('teknisi.riwayat') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60 font-medium' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ request()->routeIs('teknisi.riwayat') ? 'text-white' : 'text-slate-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs truncate">Riwayat Tiket Selesai</div>
                    <div class="text-[10px] truncate {{ request()->routeIs('teknisi.riwayat') ? 'text-emerald-100' : 'text-slate-400' }}">Kroscek &amp; Catatan OPM</div>
                </div>
            </a>

            <!-- 3. Data Topologi -->
            <a href="{{ route('topologi.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left transition-all {{ request()->routeIs('topologi.*') ? 'bg-blue-600 text-white font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60 font-medium' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <circle cx="12" cy="5" r="3"></circle>
                        <circle cx="5" cy="19" r="3"></circle>
                        <circle cx="19" cy="19" r="3"></circle>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 0l-5 4m5-4l5 4"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs truncate">Data Topologi</div>
                    <div class="text-[10px] truncate text-slate-400">Titik Jalur ODP &amp; OLT</div>
                </div>
            </a>

            <!-- 4. Riwayat Material -->
            <a href="{{ route('logistik.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left transition-all {{ request()->routeIs('logistik.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60 font-medium' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ request()->routeIs('logistik.*') ? 'text-white' : 'text-slate-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs truncate">Riwayat Material</div>
                    <div class="text-[10px] truncate {{ request()->routeIs('logistik.*') ? 'text-emerald-100' : 'text-slate-400' }}">Material Pernah Diterima</div>
                </div>
            </a>
        @else
            <!-- Klien View -->
            <!-- 1. Portal Pelanggan -->
            <a href="{{ route('klien.portal') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left transition-all {{ request()->routeIs('klien.portal') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60 font-medium' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ request()->routeIs('klien.portal') ? 'text-white' : 'text-slate-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs truncate">Portal Pelanggan</div>
                    <div class="text-[10px] truncate {{ request()->routeIs('klien.portal') ? 'text-blue-100' : 'text-slate-400' }}">Lapor Kendala &amp; Status</div>
                </div>
            </a>

            <!-- 2. Riwayat Tiket & Keluhan -->
            <a href="{{ route('klien.riwayat') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left transition-all {{ request()->routeIs('klien.riwayat') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60 font-medium' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 {{ request()->routeIs('klien.riwayat') ? 'text-white' : 'text-slate-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs truncate">Riwayat Tiket &amp; Keluhan</div>
                    <div class="text-[10px] truncate {{ request()->routeIs('klien.riwayat') ? 'text-blue-100' : 'text-slate-400' }}">Linimasa Penanganan</div>
                </div>
            </a>
        @endif
    </div>

    <!-- Bottom Network Status Widget -->
    <div class="p-4 border-t border-slate-800/80 bg-slate-950/40">
        <div class="p-3 rounded-xl bg-slate-800/40 border border-slate-700/40">
            <div class="flex items-center justify-between text-xs text-slate-300 font-semibold mb-2">
                <span class="flex items-center gap-1.5 text-[11px]">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Status Topologi
                </span>
                <span class="text-[10px] font-mono text-emerald-400 bg-emerald-500/10 px-1.5 py-0.5 rounded border border-emerald-500/20">
                    {{ $onlineTopologyCount }}/{{ $totalTopologyCount }} ON
                </span>
            </div>

            <div class="w-full bg-slate-700/60 rounded-full h-1.5 overflow-hidden mb-2">
                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $totalTopologyCount > 0 ? ($onlineTopologyCount / $totalTopologyCount) * 100 : 0 }}%"></div>
            </div>

            <div class="text-[10px] text-slate-400 flex justify-between">
                <span>Kota Banjarmasin</span>
                <span>{{ $totalTopologyCount > 0 ? round(($onlineTopologyCount / $totalTopologyCount) * 100) : 0 }}% Online</span>
            </div>
        </div>
    </div>
</aside>
