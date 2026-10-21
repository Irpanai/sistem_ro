@php
    $user = auth()->user();
    $role = $user?->role ?? 'admin';
    $activeSpkCount = \App\Models\WorkOrder::where('status', 'In Progress')->count();
@endphp

<header class="h-16 bg-white border-b border-slate-200/80 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
    <!-- Left: Hamburger & Greeting -->
    <div class="flex items-center gap-3 sm:gap-4">
        <!-- Mobile Drawer Toggle -->
        <button id="mobileMenuBtn" class="lg:hidden p-2 rounded-xl text-slate-700 hover:text-slate-900 hover:bg-slate-100 transition-colors focus:outline-none" title="Buka Menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight truncate max-w-[150px] sm:max-w-none">
                    Welcome, {{ explode(' ', $user?->name ?? 'User')[0] }}
                </h1>

                <!-- Role Badge -->
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-semibold border {{ $role === 'admin' ? 'bg-blue-50 text-blue-700 border-blue-200' : ($role === 'teknisi' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200') }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $role === 'admin' ? 'bg-blue-600 animate-pulse' : ($role === 'teknisi' ? 'bg-emerald-600' : 'bg-amber-600') }}"></span>
                    <span class="capitalize">{{ $role }}</span>
                </span>
            </div>

            <div class="hidden sm:flex items-center gap-1.5 text-[11px] text-slate-400 mt-0.5">
                <span>{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                <span>&bull; Regional Kalsel &amp; BTL</span>
            </div>
        </div>
    </div>

    <!-- Right: Role Switcher & Profile -->
    <div class="flex items-center gap-2 sm:gap-3">
        <!-- Switch Role Dropdown -->
        <div class="relative" id="roleDropdownWrapper">
            <button onclick="document.getElementById('roleMenu').classList.toggle('hidden')" class="flex items-center gap-2 px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold transition-all">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                <span class="hidden sm:inline">Ganti Role</span>
                <svg class="w-3 h-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <div id="roleMenu" class="hidden absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-slate-200 p-2 z-50">
                <div class="px-3 py-2 border-b border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                        Pilih Peran Pengguna (Demo):
                    </span>
                    <span class="text-xs text-slate-600">Simulasikan alur kerja dari sudut pandang:</span>
                </div>

                <div class="p-1 space-y-1">
                    <!-- Admin -->
                    <a href="{{ route('switch.role', ['user' => 'admin_noc']) }}" class="w-full text-left p-2.5 rounded-xl text-xs flex items-start gap-2.5 transition-all {{ $role === 'admin' ? 'bg-blue-50 text-blue-900 font-bold border border-blue-200' : 'hover:bg-slate-50 text-slate-700' }}">
                        <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold">Admin NOC &amp; Dispatcher</div>
                            <div class="text-[11px] text-slate-500">admin_noc (Menerima &amp; Teruskan)</div>
                        </div>
                    </a>

                    <!-- Teknisi -->
                    <a href="{{ route('switch.role', ['user' => 'teknisi_rizky']) }}" class="w-full text-left p-2.5 rounded-xl text-xs flex items-start gap-2.5 transition-all {{ $role === 'teknisi' ? 'bg-emerald-50 text-emerald-900 font-bold border border-emerald-200' : 'hover:bg-slate-50 text-slate-700' }}">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold">Teknisi Lapangan</div>
                            <div class="text-[11px] text-slate-500">teknisi_rizky (Pengerjaan SPK)</div>
                        </div>
                    </a>

                    <!-- Klien -->
                    <a href="{{ route('switch.role', ['user' => 'klien_sulaiman']) }}" class="w-full text-left p-2.5 rounded-xl text-xs flex items-start gap-2.5 transition-all {{ $role === 'klien' ? 'bg-amber-50 text-amber-900 font-bold border border-amber-200' : 'hover:bg-slate-50 text-slate-700' }}">
                        <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold">Klien / Pelanggan</div>
                            <div class="text-[11px] text-slate-500">klien_sulaiman (Buat Laporan)</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Separator -->
        <div class="h-5 w-px bg-slate-200"></div>

        <!-- User Profile -->
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-slate-900 text-white flex items-center justify-center text-xs font-bold ring-2 ring-slate-100 shrink-0">
                {{ strtoupper(substr($user?->name ?? 'U', 0, 2)) }}
            </div>
            <div class="hidden lg:block text-left max-w-[110px]">
                <div class="text-xs font-semibold text-slate-800 leading-tight truncate">
                    {{ $user?->name ?? 'User' }}
                </div>
                <div class="text-[10px] text-slate-500 truncate">
                    {{ $user?->username ?? 'user' }}
                </div>
            </div>
        </div>

        <!-- Logout Form -->
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-rose-200/80 bg-rose-50/70 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition-colors" title="Keluar">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span class="hidden md:inline">Keluar</span>
            </button>
        </form>
    </div>
</header>
