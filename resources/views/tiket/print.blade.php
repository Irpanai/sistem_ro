@extends('layouts.print')

@section('title', 'Cetak SPK ' . ($ticket->workOrder?->spk_number ?? $ticket->ticket_number))

@section('content')
    <!-- Document Title & Header Meta -->
    <div class="text-center mb-6">
        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight uppercase">
            SURAT PERINTAH KERJA (SPK) &amp; BERITA ACARA PERBAIKAN
        </h2>
        <div class="mt-1 flex items-center justify-center gap-2 text-xs font-mono">
            <span class="text-slate-600">No. SPK:</span>
            <strong class="text-slate-900 font-bold bg-slate-100 px-2 py-0.5 rounded border border-slate-300">
                {{ $ticket->workOrder?->spk_number ?? 'SPK-REG-' . $ticket->id }}
            </strong>
            <span class="text-slate-300">|</span>
            <span class="text-slate-600">ID Tiket:</span>
            <strong class="text-blue-700 font-bold">
                {{ $ticket->ticket_number }}
            </strong>
        </div>
    </div>

    <!-- Metadata Badges -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mb-6 text-xs">
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Klasifikasi</span>
            <strong class="font-bold {{ $ticket->type === 'OUTAGE' ? 'text-rose-700' : 'text-amber-700' }}">
                {{ $ticket->type }} ({{ $ticket->type === 'OUTAGE' ? 'Gangguan Massal' : 'Gangguan Individu' }})
            </strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Prioritas</span>
            <strong class="font-bold text-slate-900">{{ $ticket->priority }} Priority</strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Waktu Lapor</span>
            <strong class="font-mono text-slate-800">{{ $ticket->created_at->format('d/m/Y H:i') }} WITA</strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Status Dokumen</span>
            <strong class="font-bold {{ $ticket->status === 'Solved' ? 'text-emerald-700' : ($ticket->status === 'In Progress' ? 'text-blue-700' : 'text-amber-700') }}">
                {{ $ticket->status }}
            </strong>
        </div>
    </div>

    <!-- Section A: Data Pelanggan -->
    <div class="mb-5">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider bg-slate-100 px-3 py-1.5 rounded-md border-l-4 border-blue-600 mb-2.5">
            A. Identitas Pelanggan &amp; Titik Lokasi
        </h3>
        <table class="w-full text-xs text-left">
            <tr class="border-b border-slate-100">
                <td class="py-1.5 text-slate-500 w-1/4">Nama Pelanggan</td>
                <td class="py-1.5 font-bold text-slate-900 w-1/4">{{ $ticket->customer_name }}</td>
                <td class="py-1.5 text-slate-500 w-1/4">No. Telepon / WA</td>
                <td class="py-1.5 font-mono text-slate-800 w-1/4">{{ $ticket->customer_phone ?? '-' }}</td>
            </tr>
            <tr class="border-b border-slate-100">
                <td class="py-1.5 text-slate-500">Alamat Pemasangan</td>
                <td class="py-1.5 font-semibold text-slate-800" colspan="3">{{ $ticket->address }}</td>
            </tr>
            <tr class="border-b border-slate-100">
                <td class="py-1.5 text-slate-500">Wilayah / Area</td>
                <td class="py-1.5 font-semibold text-slate-800">{{ $ticket->area }}</td>
                <td class="py-1.5 text-slate-500">Simpul ODP Terhubung</td>
                <td class="py-1.5 font-mono font-bold text-blue-700">{{ $ticket->odp_code }}</td>
            </tr>
        </table>
    </div>

    <!-- Section B: Gejala Gangguan & Analisis NOC -->
    <div class="mb-5">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider bg-slate-100 px-3 py-1.5 rounded-md border-l-4 border-blue-600 mb-2.5">
            B. Uraian Gangguan &amp; Diagnosa Kantor Operasional
        </h3>
        <div class="bg-slate-50 rounded-lg p-3 border border-slate-200 text-xs space-y-2">
            <div>
                <span class="font-bold text-slate-700 block">Gejala yang Dilaporkan:</span>
                <p class="text-slate-800 italic mt-0.5">"{{ $ticket->issue }}"</p>
            </div>
            <div class="pt-2 border-t border-slate-200/80">
                <span class="font-bold text-slate-700 block">Diagnosa Teknis Sistem:</span>
                <p class="text-slate-600 mt-0.5 font-mono text-[11px]">{{ $ticket->analysis_details ?: 'Pemeriksaan fisik kabel dropcore dan optical power meter di lokasi ODP.' }}</p>
            </div>
        </div>
    </div>

    <!-- Section C: Penugasan Tim Teknisi Lapangan -->
    <div class="mb-5">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider bg-slate-100 px-3 py-1.5 rounded-md border-l-4 border-blue-600 mb-2.5">
            C. Tim Teknisi Lapangan yang Ditugaskan
        </h3>
        @if($ticket->workOrder && $ticket->workOrder->technicians->isNotEmpty())
            <table class="w-full text-xs text-left border border-slate-200 rounded-lg overflow-hidden">
                <thead class="bg-slate-100 text-slate-600 text-[10px] uppercase font-semibold">
                    <tr>
                        <th class="p-2 border-b">Peran Tim</th>
                        <th class="p-2 border-b">Nama Teknisi</th>
                        <th class="p-2 border-b">No. Kontak</th>
                        <th class="p-2 border-b">Status Lapangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-800">
                    @foreach($ticket->workOrder->technicians as $tech)
                        <tr>
                            <td class="p-2 font-semibold {{ $tech->role_in_team === 'Team Lead' ? 'text-blue-700' : 'text-slate-600' }}">
                                {{ $tech->role_in_team }}
                            </td>
                            <td class="p-2 font-bold">{{ $tech->technician_name }}</td>
                            <td class="p-2 font-mono text-slate-600">{{ $tech->technician_phone ?? '-' }}</td>
                            <td class="p-2">
                                <span class="text-[10px] px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-medium">Siap / Bertugas</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-3 border border-dashed border-slate-300 rounded-lg text-slate-500 text-xs">
                <strong>Penugasan Lapangan:</strong> {{ $ticket->workOrder?->technician_name ?? 'Menunggu Dispatch Tim Teknisi oleh Admin Kantor Banjarmasin' }}
            </div>
        @endif
    </div>

    <!-- Section D: Logistik & Material yang Digunakan -->
    <div class="mb-5">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider bg-slate-100 px-3 py-1.5 rounded-md border-l-4 border-blue-600 mb-2.5">
            D. Alokasi Material &amp; Suku Cadang Lapangan
        </h3>
        @if($ticket->workOrder && $ticket->workOrder->materialsOutbound->isNotEmpty())
            <table class="w-full text-xs text-left border border-slate-200 rounded-lg overflow-hidden">
                <thead class="bg-slate-100 text-slate-600 text-[10px] uppercase font-semibold">
                    <tr>
                        <th class="p-2 border-b">Kode SPMB</th>
                        <th class="p-2 border-b">Nama Material / Barang</th>
                        <th class="p-2 border-b text-center">Jumlah Diserahkan</th>
                        <th class="p-2 border-b">Status Pengeluaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-800">
                    @foreach($ticket->workOrder->materialsOutbound as $mat)
                        <tr>
                            <td class="p-2 font-mono text-[11px] font-bold text-slate-700">{{ $mat->outbound_code }}</td>
                            <td class="p-2 font-semibold">{{ $mat->material_name }}</td>
                            <td class="p-2 text-center font-bold font-mono">{{ $mat->quantity }} {{ $mat->unit }}</td>
                            <td class="p-2 text-[10px] text-slate-600">{{ $mat->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-2.5 border border-slate-200 bg-slate-50 rounded-lg text-slate-500 text-xs italic">
                Pekerjaan penanganan tanpa pengeluaran material khusus gudang (re-splicing / alignment konfigurasi).
            </div>
        @endif
    </div>

    <!-- Section E: Form Pengukuran Redaman & Berita Acara Lapangan -->
    <div class="mb-8 p-3 rounded-lg border border-slate-200 bg-slate-50/50">
        <h4 class="text-[11px] font-bold text-slate-800 uppercase tracking-wide mb-2">
            E. Verifikasi Hasil Uji Fisik Lapangan (Optical Power Meter):
        </h4>
        <div class="grid grid-cols-3 gap-3 text-xs">
            <div class="bg-white p-2 rounded border border-slate-200">
                <span class="block text-[10px] text-slate-500">Redaman Sebelum:</span>
                <span class="font-mono font-bold text-slate-800">-28.5 dBm (Loss Tinggi)</span>
            </div>
            <div class="bg-white p-2 rounded border border-slate-200">
                <span class="block text-[10px] text-slate-500">Redaman Sesudah Normal:</span>
                <span class="font-mono font-bold text-emerald-700">-18.2 dBm (Sesuai Standar)</span>
            </div>
            <div class="bg-white p-2 rounded border border-slate-200">
                <span class="block text-[10px] text-slate-500">SLA Waktu Selesai:</span>
                <span class="font-mono font-bold text-slate-800">{{ $ticket->resolved_at ? $ticket->resolved_at->format('d/m/Y H:i') : '< 3.0 Jam Standar' }}</span>
            </div>
        </div>
    </div>

    <!-- Signature Sign-off Block (3 Kolom) -->
    <div class="grid grid-cols-3 gap-4 text-center text-xs pt-4 border-t border-slate-200">
        <div>
            <span class="text-slate-500 text-[10px] block">Diterbitkan Oleh:</span>
            <strong class="font-bold text-slate-900 block mt-1">Admin Kantor (Banjarmasin)</strong>
            <div class="h-16 flex items-center justify-center">
                <span class="text-[10px] text-slate-400 font-mono italic">[ Tanda Tangan &amp; Cap ]</span>
            </div>
            <span class="font-bold text-slate-800 block border-t border-slate-300 pt-1">
                {{ auth()->user()?->name ?? 'Admin Kantor Banjarmasin' }}
            </span>
            <span class="text-[10px] text-slate-400">NIP. 2024-BJM-ADM01</span>
        </div>

        <div>
            <span class="text-slate-500 text-[10px] block">Pelaksana Perbaikan:</span>
            <strong class="font-bold text-slate-900 block mt-1">Koordinator Tim Teknisi</strong>
            <div class="h-16 flex items-center justify-center">
                <span class="text-[10px] text-slate-400 font-mono italic">[ Tanda Tangan Teknisi ]</span>
            </div>
            <span class="font-bold text-slate-800 block border-t border-slate-300 pt-1">
                {{ $ticket->workOrder?->technician_name ?? 'Tim Teknisi Lapangan' }}
            </span>
            <span class="text-[10px] text-slate-400">Tim Operasional Lapangan</span>
        </div>

        <div>
            <span class="text-slate-500 text-[10px] block">Mengetahui &amp; Menerima:</span>
            <strong class="font-bold text-slate-900 block mt-1">Pelanggan / Klien</strong>
            <div class="h-16 flex items-center justify-center">
                <span class="text-[10px] text-slate-400 font-mono italic">[ Tanda Tangan Pelanggan ]</span>
            </div>
            <span class="font-bold text-slate-800 block border-t border-slate-300 pt-1">
                {{ $ticket->customer_name }}
            </span>
            <span class="text-[10px] text-slate-400">Pelanggan Borneo Fiber</span>
        </div>
    </div>
@endsection
