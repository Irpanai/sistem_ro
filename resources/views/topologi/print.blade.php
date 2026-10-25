@extends('layouts.print')

@section('title', 'Sertifikat Node Topologi ' . $node->name)

@section('content')
    <!-- Document Title & Header Meta -->
    <div class="text-center mb-6">
        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight uppercase">
            BERITA ACARA REGISTRASI &amp; SERTIFIKAT SIMPUL DISTRIBUSI (ODP)
        </h2>
        <div class="mt-1 flex items-center justify-center gap-2 text-xs font-mono">
            <span class="text-slate-600">No. Registrasi Node:</span>
            <strong class="text-slate-900 font-bold bg-slate-100 px-2.5 py-0.5 rounded border border-slate-300">
                BA-REG-{{ $node->node_id ?? ('NODE-' . $node->id) }}
            </strong>
            <span class="text-slate-300">|</span>
            <span class="text-slate-600">Kode Simpul:</span>
            <strong class="text-blue-700 font-bold">
                {{ $node->name }}
            </strong>
        </div>
        <p class="text-xs text-slate-500 mt-1">
            Dokumen resmi penetapan titik simpul distribusi jaringan fiber optic Kota Banjarmasin.
        </p>
    </div>

    <!-- Metadata Badges -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mb-6 text-xs">
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Tipe Simpul</span>
            <strong class="font-bold text-blue-700">{{ $node->type }} (Optical Distribution Point)</strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Wilayah Kecamatan</span>
            <strong class="font-bold text-slate-900">{{ $node->area }}</strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Status Operasional</span>
            <strong class="font-bold {{ $node->status === 'Online' ? 'text-emerald-700' : 'text-rose-700' }}">
                ● {{ $node->status }}
            </strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Tanggal Pendaftaran</span>
            <strong class="font-mono text-slate-800">{{ $node->created_at->format('d/m/Y') }}</strong>
        </div>
    </div>

    <!-- Section A: Data Spesifikasi Fisik & Geografis -->
    <div class="mb-5">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider bg-slate-100 px-3 py-1.5 rounded-md border-l-4 border-blue-600 mb-2.5">
            A. Spesifikasi Fisik &amp; Koordinat Lapangan
        </h3>
        <table class="w-full text-xs text-left">
            <tr class="border-b border-slate-100">
                <td class="py-2 text-slate-500 w-1/4">Nama Identitas Simpul</td>
                <td class="py-2 font-bold font-mono text-slate-900 w-1/4">{{ $node->name }}</td>
                <td class="py-2 text-slate-500 w-1/4">Kode Node ID</td>
                <td class="py-2 font-mono text-slate-800 w-1/4">{{ $node->node_id ?? ('NODE-' . $node->id) }}</td>
            </tr>
            <tr class="border-b border-slate-100">
                <td class="py-2 text-slate-500">Lokasi Tiang / Alamat</td>
                <td class="py-2 font-semibold text-slate-800" colspan="3">{{ $node->location }}</td>
            </tr>
            <tr class="border-b border-slate-100">
                <td class="py-2 text-slate-500">Wilayah Administratif</td>
                <td class="py-2 font-semibold text-slate-800">{{ $node->area }}</td>
                <td class="py-2 text-slate-500">Koordinat GPS (Lat, Long)</td>
                <td class="py-2 font-mono font-bold text-blue-700">{{ $node->coordinates ?? '-3.3200, 114.6000' }}</td>
            </tr>
        </table>
    </div>

    <!-- Section B: Kapasitas Port & Utilisasi -->
    <div class="mb-5">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider bg-slate-100 px-3 py-1.5 rounded-md border-l-4 border-blue-600 mb-2.5">
            B. Kapasitas Splitter &amp; Alokasi Port Pelanggan
        </h3>
        @php
            $utilization = $node->total_ports > 0 ? round(($node->used_ports / $node->total_ports) * 100, 1) : 0;
            $idlePorts = max(0, $node->total_ports - $node->used_ports);
        @endphp
        <div class="grid grid-cols-4 gap-3 text-center mb-3">
            <div class="p-3 rounded-lg border border-slate-200 bg-slate-50">
                <span class="block text-[10px] text-slate-500 uppercase font-semibold">Total Port</span>
                <span class="text-xl font-bold font-mono text-slate-900">{{ $node->total_ports }}</span>
                <span class="block text-[10px] text-slate-400">Port Splitter</span>
            </div>
            <div class="p-3 rounded-lg border border-slate-200 bg-slate-50">
                <span class="block text-[10px] text-slate-500 uppercase font-semibold">Port Terpakai</span>
                <span class="text-xl font-bold font-mono text-blue-700">{{ $node->used_ports }}</span>
                <span class="block text-[10px] text-slate-400">Pelanggan Aktif</span>
            </div>
            <div class="p-3 rounded-lg border border-slate-200 bg-slate-50">
                <span class="block text-[10px] text-slate-500 uppercase font-semibold">Port Cadangan</span>
                <span class="text-xl font-bold font-mono text-emerald-700">{{ $idlePorts }}</span>
                <span class="block text-[10px] text-slate-400">Kapasitas Idle</span>
            </div>
            <div class="p-3 rounded-lg border border-slate-200 bg-slate-50">
                <span class="block text-[10px] text-slate-500 uppercase font-semibold">Rasio Utilisasi</span>
                <span class="text-xl font-bold font-mono text-slate-800">{{ $utilization }}%</span>
                <span class="block text-[10px] text-slate-400">Load Factor</span>
            </div>
        </div>
    </div>

    <!-- Section C: Hasil Uji Optik Lapangan -->
    <div class="mb-8 p-3 rounded-lg border border-slate-200 bg-slate-50 text-xs">
        <h4 class="font-bold text-slate-800 uppercase tracking-wide mb-2">
            C. Parameter Uji Kualitas Optik (Optical Power Baseline):
        </h4>
        <div class="grid grid-cols-3 gap-3 text-xs">
            <div class="bg-white p-2.5 rounded border border-slate-200">
                <span class="block text-[10px] text-slate-500">Optical Redaman Input:</span>
                <span class="font-mono font-bold text-emerald-700">-17.8 dBm (Standar Prima)</span>
            </div>
            <div class="bg-white p-2.5 rounded border border-slate-200">
                <span class="block text-[10px] text-slate-500">Konektor Adaptor:</span>
                <span class="font-mono font-bold text-slate-800">SC/UPC Blue Simplex</span>
            </div>
            <div class="bg-white p-2.5 rounded border border-slate-200">
                <span class="block text-[10px] text-slate-500">Kabel Feeder Uplink:</span>
                <span class="font-mono font-bold text-slate-800">Core 12 FO G.652D</span>
            </div>
        </div>
    </div>

    <!-- Sign-off Block (3 Kolom) -->
    <div class="grid grid-cols-3 gap-4 text-center text-xs pt-4 border-t border-slate-200">
        <div>
            <span class="text-slate-500 text-[10px] block">Petugas Surveyor:</span>
            <strong class="font-bold text-slate-900 block mt-1">Surveyor Jaringan BJM</strong>
            <div class="h-16 flex items-center justify-center">
                <span class="text-[10px] text-slate-400 font-mono italic">[ Tanda Tangan ]</span>
            </div>
            <span class="font-bold text-slate-800 block border-t border-slate-300 pt-1">
                Tim Survey Lapangan
            </span>
            <span class="text-[10px] text-slate-400">Divisi Perencanaan Jaringan</span>
        </div>

        <div>
            <span class="text-slate-500 text-[10px] block">Field Splicer / Engineer:</span>
            <strong class="font-bold text-slate-900 block mt-1">Teknisi Pengesah ODP</strong>
            <div class="h-16 flex items-center justify-center">
                <span class="text-[10px] text-slate-400 font-mono italic">[ Tanda Tangan ]</span>
            </div>
            <span class="font-bold text-slate-800 block border-t border-slate-300 pt-1">
                Rizky Ramadhan
            </span>
            <span class="text-[10px] text-slate-400">Lead Splicer Wilayah Banjarmasin</span>
        </div>

        <div>
            <span class="text-slate-500 text-[10px] block">Mengetahui &amp; Memverifikasi:</span>
            <strong class="font-bold text-slate-900 block mt-1">Admin Kantor (Banjarmasin)</strong>
            <div class="h-16 flex items-center justify-center">
                <span class="text-[10px] text-slate-400 font-mono italic">[ Tanda Tangan &amp; Cap ]</span>
            </div>
            <span class="font-bold text-slate-800 block border-t border-slate-300 pt-1">
                {{ auth()->user()?->name ?? 'Admin Kantor Banjarmasin' }}
            </span>
            <span class="text-[10px] text-slate-400">Kepala Administrasi Operasional</span>
        </div>
    </div>
@endsection
