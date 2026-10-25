@extends('layouts.print')

@section('title', 'Bukti Registrasi Akun ' . $user->username)

@section('content')
    <!-- Document Title & Header Meta -->
    <div class="text-center mb-6">
        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight uppercase">
            LEMBAR BUKTI REGISTRASI AKUN &amp; PAKTA AKSES SISTEM
        </h2>
        <div class="mt-1 flex items-center justify-center gap-2 text-xs font-mono">
            <span class="text-slate-600">ID Pengguna:</span>
            <strong class="text-slate-900 font-bold bg-slate-100 px-2.5 py-0.5 rounded border border-slate-300">
                USR-BJM-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
            </strong>
            <span class="text-slate-300">|</span>
            <span class="text-slate-600">Username:</span>
            <strong class="text-blue-700 font-bold">
                {{ $user->username }}
            </strong>
        </div>
        <p class="text-xs text-slate-500 mt-1">
            Dokumen resmi penetapan hak akses aplikasi Sistem Informasi Pelayanan &amp; Jaringan Fiber Optic.
        </p>
    </div>

    <!-- Metadata Badges -->
    @php
        $roleTitle = match($user->role) {
            'admin' => 'Admin Kantor (Banjarmasin)',
            'teknisi' => 'Teknisi Operasional Lapangan',
            'klien' => 'Klien / Pelanggan Terdaftar',
            default => ucfirst($user->role)
        };
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mb-6 text-xs">
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Peran / Otoritas</span>
            <strong class="font-bold text-blue-700">{{ $roleTitle }}</strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Wilayah Penugasan</span>
            <strong class="font-bold text-slate-900">{{ $user->area ?? 'Kota Banjarmasin' }}</strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Status Keaktifan</span>
            <strong class="font-bold text-emerald-700">● Akun Aktif Terverifikasi</strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Tanggal Pembuatan</span>
            <strong class="font-mono text-slate-800">{{ $user->created_at->format('d/m/Y H:i') }}</strong>
        </div>
    </div>

    <!-- Section A: Data Pengguna -->
    <div class="mb-5">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider bg-slate-100 px-3 py-1.5 rounded-md border-l-4 border-blue-600 mb-2.5">
            A. Identitas Pengguna &amp; Data Kredensial
        </h3>
        <table class="w-full text-xs text-left">
            <tr class="border-b border-slate-100">
                <td class="py-2 text-slate-500 w-1/4">Nama Lengkap</td>
                <td class="py-2 font-bold text-slate-900 w-1/4">{{ $user->name }}</td>
                <td class="py-2 text-slate-500 w-1/4">Username Login</td>
                <td class="py-2 font-mono font-bold text-blue-700 w-1/4">{{ $user->username }}</td>
            </tr>
            <tr class="border-b border-slate-100">
                <td class="py-2 text-slate-500">Alamat Email Resmi</td>
                <td class="py-2 font-semibold text-slate-800">{{ $user->email }}</td>
                <td class="py-2 text-slate-500">No. WhatsApp / Kontak</td>
                <td class="py-2 font-mono text-slate-800">{{ $user->phone ?? '-' }}</td>
            </tr>
            <tr class="border-b border-slate-100">
                <td class="py-2 text-slate-500">Alamat / Lokasi Pemasangan</td>
                <td class="py-2 font-semibold text-slate-800" colspan="3">{{ $user->address ?? 'Kota Banjarmasin' }}</td>
            </tr>
            @if($user->role === 'teknisi')
                <tr class="border-b border-slate-100">
                    <td class="py-2 text-slate-500">Status Kesiapan Tim</td>
                    <td class="py-2 font-bold text-emerald-700">{{ $user->technician_status ?? 'Available' }}</td>
                    <td class="py-2 text-slate-500">Rating Kinerja Awal</td>
                    <td class="py-2 font-mono font-bold text-amber-600">{{ $user->rating ?? '5.0' }} / 5.0</td>
                </tr>
            @endif
        </table>
    </div>

    <!-- Section B: Batas Hak Akses & Fitur -->
    <div class="mb-5">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider bg-slate-100 px-3 py-1.5 rounded-md border-l-4 border-blue-600 mb-2.5">
            B. Cakupan Hak Akses Modul Sistem (SIPKJ)
        </h3>
        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 text-xs">
            @if($user->role === 'admin')
                <p class="text-slate-700 leading-relaxed">
                    <strong>Admin Kantor (Banjarmasin):</strong> Akses penuh terhadap Dashboard Diagnostik, Manajemen Topologi ODP per Kecamatan, Manajemen Pengguna Kantor &amp; Lapangan, Penerbitan SPK &amp; Dispatch Teknisi, Manajemen Gudang Logistik, serta Laporan Bulanan &amp; Audit Stok Opname.
                </p>
            @elseif($user->role === 'teknisi')
                <p class="text-slate-700 leading-relaxed">
                    <strong>Teknisi Operasional Lapangan:</strong> Akses terhadap Portal Penerimaan SPK Lapangan, Riwayat Material yang Diserahkan Gudang, Penyelesaian Berita Acara Perbaikan, serta Rekapitulasi Tiket Selesai.
                </p>
            @else
                <p class="text-slate-700 leading-relaxed">
                    <strong>Klien / Pelanggan:</strong> Akses terhadap Portal Pengaduan Mandiri Gangguan Fiber Optic, Monitoring Status Pengerjaan Tiket secara Real-time, dan Riwayat Tiket Keluhan Pribadi.
                </p>
            @endif
        </div>
    </div>

    <!-- Section C: Pakta Integritas & Kerahasiaan Kredensial -->
    <div class="mb-8 p-3 rounded-lg border border-slate-200 bg-slate-50 text-[11px] text-slate-600 space-y-1">
        <span class="font-bold text-slate-800 block">Pakta Kerahasiaan &amp; Keamanan Akun:</span>
        <p class="leading-relaxed">
            1. Pengguna wajib menjaga kerahasiaan kata sandi (password) dan dilarang membagikan kredensial login kepada pihak lain.<br>
            2. Segala aktivitas transaksi data yang dilakukan melalui akun ini dicatat dalam log sistem (Audit Trail).<br>
            3. Pelanggaran terhadap SOP keamanan sistem dapat berakibat pada penonaktifan akun secara sepihak oleh Administrator.
        </p>
    </div>

    <!-- Sign-off Block (2 Kolom) -->
    <div class="grid grid-cols-2 gap-8 text-center text-xs pt-4 border-t border-slate-200">
        <div>
            <span class="text-slate-500 text-[10px] block">Ditetapkan &amp; Disahkan Oleh:</span>
            <strong class="font-bold text-slate-900 block mt-1">Admin Kantor (Banjarmasin)</strong>
            <div class="h-16 flex items-center justify-center">
                <span class="text-[10px] text-slate-400 font-mono italic">[ Tanda Tangan &amp; Cap ]</span>
            </div>
            <span class="font-bold text-slate-800 block border-t border-slate-300 pt-1">
                {{ auth()->user()?->name ?? 'Admin Kantor Banjarmasin' }}
            </span>
            <span class="text-[10px] text-slate-400">Administrator Sistem Jaringan</span>
        </div>

        <div>
            <span class="text-slate-500 text-[10px] block">Penerima Hak Akses Akun:</span>
            <strong class="font-bold text-slate-900 block mt-1">{{ $roleTitle }}</strong>
            <div class="h-16 flex items-center justify-center">
                <span class="text-[10px] text-slate-400 font-mono italic">[ Tanda Tangan Penerima ]</span>
            </div>
            <span class="font-bold text-slate-800 block border-t border-slate-300 pt-1">
                {{ $user->name }}
            </span>
            <span class="text-[10px] text-slate-400">Pengguna Terdaftar #{{ $user->username }}</span>
        </div>
    </div>
@endsection
