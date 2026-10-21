<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SIPKJ' }} - Sistem Informasi Pengelolaan Keluhan Jaringan</title>
    
    <!-- Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Chart.js for beautiful lightweight native charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <!-- Leaflet.js for Interactive Fiber Network & Satellite GIS Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans min-h-screen selection:bg-blue-600 selection:text-white">

    <div class="flex min-h-screen relative">
        <!-- Sidebar Partial -->
        @include('layouts.sidebar')

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 min-h-screen w-full">
            <!-- Header Partial -->
            @include('layouts.header')

            <!-- Flash Session Alerts (Toasts) -->
            @if(session('success') || session('warning') || session('info') || (isset($errors) && $errors->any()))
                <div class="fixed bottom-6 right-6 z-50 flex flex-col gap-2.5 max-w-md w-full px-4">
                    @if(session('success'))
                        <div class="auto-dismiss flex items-start gap-3 p-4 rounded-2xl bg-white border border-emerald-200 text-emerald-900 shadow-xl shadow-emerald-500/10 transition-all duration-300">
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div class="text-xs">
                                <p class="font-bold text-slate-900">Berhasil Disimpan</p>
                                <p class="text-slate-600 mt-0.5">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="auto-dismiss flex items-start gap-3 p-4 rounded-2xl bg-white border border-amber-200 text-amber-900 shadow-xl shadow-amber-500/10 transition-all duration-300">
                            <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div class="text-xs">
                                <p class="font-bold text-slate-900">Perhatian</p>
                                <p class="text-slate-600 mt-0.5">{{ session('warning') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="auto-dismiss flex items-start gap-3 p-4 rounded-2xl bg-white border border-blue-200 text-blue-900 shadow-xl shadow-blue-500/10 transition-all duration-300">
                            <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="text-xs">
                                <p class="font-bold text-slate-900">Pemberitahuan</p>
                                <p class="text-slate-600 mt-0.5">{{ session('info') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(isset($errors) && $errors->any())
                        <div class="auto-dismiss flex items-start gap-3 p-4 rounded-2xl bg-white border border-rose-200 text-rose-900 shadow-xl shadow-rose-500/10 transition-all duration-300">
                            <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            <div class="text-xs">
                                <p class="font-bold text-slate-900">Terdapat Kesalahan Input:</p>
                                <ul class="list-disc pl-4 mt-1 space-y-0.5 text-slate-600">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Standardized Main Page Content Container (Compact Margin & Full Fluid Width) -->
            <main class="flex-1 px-4 sm:px-6 py-6 w-full space-y-6 pb-16">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
