<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dokumen Resmi') - PT Borneo Fiber Banjarmasin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: #0f172a !important;
                font-size: 11pt !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .print-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
            }
            @page {
                size: A4 portrait;
                margin: 15mm 15mm 15mm 15mm;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f1f5f9;
        }

        .mono {
            font-family: 'JetBrains Mono', monospace;
        }

        .double-line {
            border-top: 3px solid #0f172a;
            border-bottom: 1px solid #0f172a;
            height: 4px;
            margin: 12px 0 20px 0;
        }
    </style>
</head>
<body class="text-slate-800 antialiased min-h-screen py-8">

    <!-- Top Action Bar (Screen Only) -->
    <div class="no-print max-w-4xl mx-auto mb-6 px-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <button onclick="window.close(); if(!window.closed) { window.history.back(); }" 
                    class="px-4 py-2 rounded-xl bg-white border border-slate-300 text-slate-700 text-xs font-semibold hover:bg-slate-50 transition-colors shadow-xs flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </button>
            <span class="text-xs text-slate-500 font-medium hidden sm:inline">
                Mode Pratinjau Dokumen Cetak (A4 Standard)
            </span>
        </div>

        <div class="flex items-center gap-2.5">
            <button onclick="window.print()" 
                    class="px-5 py-2 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 transition-all shadow-sm hover:shadow flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Dokumen / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Paper Sheet Canvas (A4 Dimensions) -->
    <main class="print-container max-w-4xl mx-auto bg-white border border-slate-200/90 shadow-lg rounded-2xl p-8 sm:p-12 transition-all">
        
        <!-- Official KOP Surat Resmi -->
        <header class="mb-4">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-700 via-indigo-700 to-blue-900 text-white flex items-center justify-center font-black text-2xl shadow-sm tracking-tight shrink-0 border border-blue-800">
                        BF
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-tight uppercase font-mono">
                            PT BORNEO FIBER MEDIA UTAMA
                        </h1>
                        <p class="text-xs sm:text-sm font-bold text-blue-700 uppercase tracking-wider">
                            Kantor Operasional Banjarmasin &bull; Divisi Layanan Jaringan Fiber Optic
                        </p>
                        <p class="text-[11px] text-slate-500 mt-0.5 leading-tight">
                            Jl. Ahmad Yani Km. 4.5 No. 88, Banjarmasin Selatan, Kota Banjarmasin, Kalimantan Selatan 70234
                        </p>
                        <p class="text-[10px] text-slate-400 mt-0.5 leading-tight">
                            Call Center: (0511) 7894561 &bull; Hotline / WA: 0812-5000-8899 &bull; Web: www.borneofiber.net
                        </p>
                    </div>
                </div>

                <div class="text-right hidden sm:block shrink-0">
                    <div class="inline-block text-center border-2 border-slate-900 rounded-lg px-3 py-1.5 bg-slate-50">
                        <span class="block text-[9px] font-bold uppercase tracking-widest text-slate-600">Dokumen Resmi</span>
                        <span class="block text-xs font-mono font-black text-slate-900">BORNEO-BJM</span>
                    </div>
                </div>
            </div>

            <!-- Double separator line standard official surat -->
            <div class="double-line"></div>
        </header>

        <!-- Document Content -->
        @yield('content')

        <!-- Footer / Legal Disclaimer -->
        <footer class="mt-12 pt-6 border-t border-slate-200 text-[10px] text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                Dicetak otomatis melalui <strong>Sistem Terpadu Layanan &amp; Jaringan Fiber Optic (SIPKJ)</strong> Kantor Banjarmasin.
            </div>
            <div class="font-mono text-slate-500">
                Waktu Cetak: {{ now()->translatedFormat('d F Y, H:i:s') }} WITA
            </div>
        </footer>

    </main>

</body>
</html>
