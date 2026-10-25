@extends('layouts.print')

@section('title', 'Tanda Terima Pengaduan ' . $ticket->ticket_number)

@section('content')
    <!-- Document Title & Header Meta -->
    <div class="text-center mb-6">
        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight uppercase">
            TANDA TERIMA &amp; LEMBAR BUKTI PENGADUAN GANGGUAN
        </h2>
        <div class="mt-1 flex items-center justify-center gap-2 text-xs font-mono">
            <span class="text-slate-600">No. Tiket Pengaduan:</span>
            <strong class="text-blue-700 font-bold bg-blue-50 px-2.5 py-0.5 rounded border border-blue-200">
                {{ $ticket->ticket_number }}
            </strong>
        </div>
        <p class="text-xs text-slate-500 mt-1">
            Simpan lembar bukti ini sebagai tanda registrasi laporan pengaduan gangguan koneksi internet.
        </p>
    </div>

    <!-- Metadata Badges -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mb-6 text-xs">
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Waktu Pengaduan</span>
            <strong class="font-mono text-slate-800">{{ $ticket->created_at->format('d/m/Y H:i') }} WITA</strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Kanal Laporan</span>
            <strong class="font-bold text-slate-900">Portal Mandiri Pelanggan</strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Target SLA Respon</span>
            <strong class="font-bold text-emerald-700">&lt; 3.0 Jam Kerja</strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Status Penanganan</span>
            <strong class="font-bold {{ $ticket->status === 'Solved' ? 'text-emerald-700' : 'text-blue-700' }}">
                {{ $ticket->status }}
            </strong>
        </div>
    </div>

    <!-- Section A: Data Pelanggan -->
    <div class="mb-5">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider bg-slate-100 px-3 py-1.5 rounded-md border-l-4 border-blue-600 mb-2.5">
            A. Identitas Pelanggan &amp; Layanan Terdaftar
        </h3>
        <table class="w-full text-xs text-left">
            <tr class="border-b border-slate-100">
                <td class="py-2 text-slate-500 w-1/4">Nama Pelanggan</td>
                <td class="py-2 font-bold text-slate-900 w-1/4">{{ $ticket->customer_name }}</td>
                <td class="py-2 text-slate-500 w-1/4">No. Kontak / WA</td>
                <td class="py-2 font-mono text-slate-800 w-1/4">{{ $ticket->customer_phone ?? '-' }}</td>
            </tr>
            <tr class="border-b border-slate-100">
                <td class="py-2 text-slate-500">Alamat Pemasangan</td>
                <td class="py-2 font-semibold text-slate-800" colspan="3">{{ $ticket->address }}</td>
            </tr>
            <tr class="border-b border-slate-100">
                <td class="py-2 text-slate-500">Simpul ODP Terhubung</td>
                <td class="py-2 font-mono font-bold text-blue-700">{{ $ticket->odp_code }}</td>
                <td class="py-2 text-slate-500">Wilayah Layanan</td>
                <td class="py-2 font-semibold text-slate-800">{{ $ticket->area }}</td>
            </tr>
        </table>
    </div>

    <!-- Section B: Rincian Gangguan -->
    <div class="mb-5">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider bg-slate-100 px-3 py-1.5 rounded-md border-l-4 border-blue-600 mb-2.5">
            B. Uraian Gangguan yang Dilaporkan
        </h3>
        <div class="bg-slate-50 rounded-lg p-3.5 border border-slate-200 text-xs">
            <span class="font-bold text-slate-800 block mb-1">Deskripsi Keluhan:</span>
            <p class="text-slate-700 leading-relaxed italic bg-white p-2.5 rounded border border-slate-200">
                "{{ $ticket->issue }}"
            </p>

            <div class="mt-3 pt-2.5 border-t border-slate-200 flex items-center justify-between text-[11px] text-slate-500">
                <span>Klasifikasi Sistem: <strong>{{ $ticket->type === 'OUTAGE' ? 'Insiden Massal (Feeder / Jalur ODP)' : 'Insiden Lokal (Jalur Dropcore / Modem)' }}</strong></span>
                <span>Prioritas Penanganan: <strong class="text-slate-800">{{ $ticket->priority }}</strong></span>
            </div>
        </div>
    </div>

    <!-- Section C: Ketentuan Standar Layanan SLA -->
    <div class="mb-8 p-3 rounded-lg border border-blue-200 bg-blue-50/60 text-xs text-blue-900 space-y-1.5">
        <div class="font-bold flex items-center gap-1.5 text-blue-950">
            <svg class="w-4 h-4 text-blue-700 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            Standar Operasional Penanganan Gangguan Pelanggan (SLA):
        </div>
        <p class="text-[11px] leading-relaxed text-blue-800">
            1. Tim Helpdesk &amp; Admin Kantor Banjarmasin memproses pengaduan dalam waktu maksimal 15 menit.<br>
            2. Tim Teknisi Lapangan disiagakan dengan target penyelesaian (MTTR) maksimal 3.0 jam sejak tiket diterbitkan.<br>
            3. Pelanggan dapat memantau status perkembangan tiket secara berkala melalui Portal Mandiri Pelanggan.
        </p>
    </div>

    <!-- Sign-off Block (2 Kolom) -->
    <div class="grid grid-cols-2 gap-8 text-center text-xs pt-4 border-t border-slate-200">
        <div>
            <span class="text-slate-500 text-[10px] block">Petugas Layanan / Helpdesk:</span>
            <strong class="font-bold text-slate-900 block mt-1">Kantor Operasional Banjarmasin</strong>
            <div class="h-16 flex items-center justify-center">
                <span class="text-[10px] text-slate-400 font-mono italic">[ Tanda Tangan &amp; Validasi Sistem ]</span>
            </div>
            <span class="font-bold text-slate-800 block border-t border-slate-300 pt-1">
                Customer Care &amp; Support BJM
            </span>
            <span class="text-[10px] text-slate-400">Divisi Pelayanan Pelanggan</span>
        </div>

        <div>
            <span class="text-slate-500 text-[10px] block">Pelanggan Pelapor:</span>
            <strong class="font-bold text-slate-900 block mt-1">Pemohon / Klien Terdaftar</strong>
            <div class="h-16 flex items-center justify-center">
                <span class="text-[10px] text-slate-400 font-mono italic">[ Tanda Tangan Pelanggan ]</span>
            </div>
            <span class="font-bold text-slate-800 block border-t border-slate-300 pt-1">
                {{ $ticket->customer_name }}
            </span>
            <span class="text-[10px] text-slate-400">ID Pelanggan: #{{ $ticket->user_id ?? $ticket->id }}</span>
        </div>
    </div>
@endsection
