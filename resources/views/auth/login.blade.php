<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SIPKJ Borneo Fiber Net</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-900 flex flex-col justify-center items-center p-4 sm:p-6 relative overflow-x-hidden font-sans antialiased selection:bg-blue-600 selection:text-white">

    <!-- Background glowing radial effects -->
    <div class="absolute top-[-10%] left-[-10%] w-[400px] sm:w-[500px] h-[400px] sm:h-[500px] bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[400px] sm:w-[550px] h-[400px] sm:h-[550px] bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative w-full max-w-lg z-10 my-6">
        <!-- Top Logo -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-sky-400 p-0.5 shadow-xl shadow-blue-500/25 mb-3">
                <div class="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white flex items-center justify-center gap-2">
                <span>SIPKJ BORNEO</span>
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-400 border border-blue-500/30">
                    Laravel Blade Native
                </span>
            </h1>
            <p class="text-xs sm:text-sm font-medium text-slate-400 mt-1">
                Sistem Informasi Pengelolaan Keluhan Jaringan (ISP)
            </p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-800/95 backdrop-blur-xl border border-slate-700/80 rounded-2xl shadow-2xl p-6 sm:p-7 text-slate-100">
            <div class="mb-5">
                <h2 class="text-base sm:text-lg font-bold text-white">
                    Masuk ke Sistem Informasi
                </h2>
                <p class="text-xs text-slate-400 mt-0.5 leading-relaxed">
                    Sistem ini mendukung 3 peran: <strong>Klien</strong> (lapor keluhan), <strong>Admin</strong> (analisis &amp; dispatch SPK), dan <strong>Teknisi</strong> (perbaikan lapangan).
                </p>
            </div>

            <!-- Quick 1-Click Role Logins -->
            <div class="mb-5">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">
                    Kredensial Siap Pakai (1-Click Auto Fill):
                </span>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                    <!-- Admin -->
                    <button type="button" onclick="selectRole('admin_noc', 'password123')" class="p-3 rounded-xl border bg-slate-900/60 border-slate-700/70 hover:border-blue-500 hover:bg-blue-600/10 text-left transition-all group">
                        <div class="flex items-center gap-1.5 mb-1">
                            <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span class="text-xs font-bold text-white group-hover:text-blue-400">Admin NOC</span>
                        </div>
                        <div class="text-[10px] text-slate-400 font-mono truncate">admin_noc</div>
                        <div class="text-[9px] text-slate-500 mt-0.5">Admin &amp; Dispatcher</div>
                    </button>

                    <!-- Teknisi -->
                    <button type="button" onclick="selectRole('teknisi_rizky', 'password123')" class="p-3 rounded-xl border bg-slate-900/60 border-slate-700/70 hover:border-emerald-500 hover:bg-emerald-600/10 text-left transition-all group">
                        <div class="flex items-center gap-1.5 mb-1">
                            <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-xs font-bold text-white group-hover:text-emerald-400">Teknisi</span>
                        </div>
                        <div class="text-[10px] text-slate-400 font-mono truncate">teknisi_rizky</div>
                        <div class="text-[9px] text-slate-500 mt-0.5">Pengerjaan SPK</div>
                    </button>

                    <!-- Klien -->
                    <button type="button" onclick="selectRole('klien_sulaiman', 'password123')" class="p-3 rounded-xl border bg-slate-900/60 border-slate-700/70 hover:border-amber-500 hover:bg-amber-600/10 text-left transition-all group">
                        <div class="flex items-center gap-1.5 mb-1">
                            <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-xs font-bold text-white group-hover:text-amber-400">Klien</span>
                        </div>
                        <div class="text-[10px] text-slate-400 font-mono truncate">klien_sulaiman</div>
                        <div class="text-[9px] text-slate-500 mt-0.5">Pelanggan Lapor</div>
                    </button>
                </div>
            </div>

            @if(isset($errors) && $errors->any())
                <div class="mb-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-4 p-3 rounded-xl bg-blue-500/10 border border-blue-500/30 text-blue-400 text-xs">
                    {{ session('info') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-1.5">
                        Username / Akun Pengguna
                    </label>
                    <div class="relative">
                        <input type="text" id="usernameInput" name="username" value="{{ old('username', 'admin_noc') }}" required placeholder="admin_noc / teknisi_rizky / klien_sulaiman" class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-xs sm:text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all font-mono">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-medium text-slate-300">
                            Password (Default: password123)
                        </label>
                    </div>
                    <div class="relative">
                        <input type="password" id="passwordInput" name="password" value="password123" required placeholder="password123" class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-xs sm:text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all">
                    </div>
                </div>

                <div class="flex items-center justify-between text-[11px] sm:text-xs text-slate-400 pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" checked class="w-3.5 h-3.5 rounded border-slate-700 bg-slate-900 text-blue-600 focus:ring-blue-500/50">
                        <span>Ingat sesi masuk</span>
                    </label>
                    <span class="text-slate-500 text-[11px]">MySQL Connected</span>
                </div>

                <button type="submit" class="w-full mt-2 py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-blue-600/30 active:scale-[0.99] transition-all flex items-center justify-center gap-2">
                    <span>Masuk ke Dashboard</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center text-[11px] text-slate-500 mt-4">
            &copy; 2026 Borneo Fiber Net &bull; Sistem Pengelolaan Keluhan Jaringan
        </p>
    </div>

    <script>
        function selectRole(u, p) {
            document.getElementById('usernameInput').value = u;
            document.getElementById('passwordInput').value = p;
        }
    </script>
</body>
</html>
