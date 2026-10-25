@extends('layouts.app', ['title' => 'Manajemen Pengguna'])

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Manajemen Pengguna (User Management)</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Kelola hak akses akun Admin Kantor (Banjarmasin), tim Teknisi Lapangan, dan portal mandiri Klien / Pelanggan.
            </p>
        </div>

        <button onclick="document.getElementById('modalAddUser').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs sm:text-sm font-semibold shadow-sm shadow-blue-500/20 transition-all hover:scale-[1.01] active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            <span>Tambah Pengguna Baru</span>
        </button>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Akun</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1 font-mono">{{ $totalUsers }} <span class="text-xs font-normal text-slate-500 font-sans">User</span></h3>
                <p class="text-[11px] text-slate-400 mt-1">Terdaftar di sistem database</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-blue-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Admin Kantor</p>
                <h3 class="text-2xl font-extrabold text-blue-700 mt-1 font-mono">{{ $adminCount }} <span class="text-xs font-normal text-slate-500 font-sans">Petugas</span></h3>
                <p class="text-[11px] text-blue-500 font-medium mt-1">Admin Kantor Banjarmasin</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-emerald-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Teknisi Lapangan</p>
                <h3 class="text-2xl font-extrabold text-emerald-700 mt-1 font-mono">{{ $technicianCount }} <span class="text-xs font-normal text-slate-500 font-sans">Personel</span></h3>
                <p class="text-[11px] text-emerald-600 font-medium mt-1">Siap penugasan SPK perbaikan</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-amber-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Klien / Pelanggan</p>
                <h3 class="text-2xl font-extrabold text-amber-700 mt-1 font-mono">{{ $clientCount }} <span class="text-xs font-normal text-slate-500 font-sans">Akun</span></h3>
                <p class="text-[11px] text-amber-600 font-medium mt-1">Pelanggan internet fiber</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-5 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama, username, email, WhatsApp, atau wilayah..."
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div class="sm:col-span-3">
                <select name="role" onchange="this.form.submit()"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                    <option value="Semua">Semua Peran (Role)</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin Kantor (Banjarmasin)</option>
                    <option value="teknisi" {{ request('role') === 'teknisi' ? 'selected' : '' }}>Teknisi Lapangan</option>
                    <option value="klien" {{ request('role') === 'klien' ? 'selected' : '' }}>Klien / Pelanggan</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <select name="area" onchange="this.form.submit()"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                    <option value="Semua">Semua Wilayah</option>
                    <option value="Banjarmasin" {{ request('area') === 'Banjarmasin' ? 'selected' : '' }}>Banjarmasin</option>
                    <option value="Barito Kuala" {{ request('area') === 'Barito Kuala' ? 'selected' : '' }}>Barito Kuala</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-semibold hover:bg-slate-800 transition-colors">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'role', 'area']))
                    <a href="{{ route('users.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-medium transition-colors" title="Reset Filter">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4 sm:px-6">Pengguna</th>
                        <th class="py-3.5 px-4">Role Akses</th>
                        <th class="py-3.5 px-4">Status / Rating</th>
                        <th class="py-3.5 px-4">Kontak &amp; Wilayah</th>
                        <th class="py-3.5 px-4">Terdaftar</th>
                        <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($users as $user)
                        @php
                            $roleBadge = match($user->role) {
                                'admin' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'teknisi' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'klien' => 'bg-amber-50 text-amber-800 border-amber-200',
                            };
                            $roleLabel = match($user->role) {
                                'admin' => 'Admin Kantor (Banjarmasin)',
                                'teknisi' => 'Teknisi Lapangan',
                                'klien' => 'Klien / Pelanggan',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4 sm:px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-slate-700 to-slate-900 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-xs">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 truncate">{{ $user->name }}</p>
                                        <p class="text-slate-400 font-mono text-[11px] truncate">{{ $user->username }} &bull; {{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold border {{ $roleBadge }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->role === 'admin' ? 'bg-blue-600' : ($user->role === 'teknisi' ? 'bg-emerald-600' : 'bg-amber-600') }}"></span>
                                    {{ $roleLabel }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4">
                                @if($user->role === 'teknisi')
                                    <div class="space-y-0.5">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold {{ $user->technician_status === 'Available' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($user->technician_status === 'On Field' ? 'bg-blue-50 text-blue-700 border border-blue-200 animate-pulse' : 'bg-slate-100 text-slate-600') }}">
                                            ● {{ $user->technician_status ?? 'Available' }}
                                        </span>
                                        <div class="text-[10px] text-amber-500 font-semibold flex items-center gap-0.5">
                                            ★ {{ number_format($user->rating ?? 4.9, 1) }} / 5.0
                                        </div>
                                    </div>
                                @elseif($user->role === 'klien')
                                    <span class="text-slate-600 text-[11px] font-medium bg-slate-100 px-2 py-0.5 rounded">
                                        Pelanggan Aktif
                                    </span>
                                @else
                                    <span class="text-blue-700 text-[11px] font-bold bg-blue-50 px-2 py-0.5 rounded">
                                        Super Admin
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4">
                                <p class="text-slate-800 font-medium text-xs">{{ $user->phone ?: '-' }}</p>
                                <p class="text-slate-400 text-[11px] truncate max-w-[180px]">{{ $user->area ?: ($user->address ?: '-') }}</p>
                            </td>

                            <td class="py-3.5 px-4 text-slate-500 text-[11px]">
                                {{ $user->created_at ? $user->created_at->format('d M Y') : 'Pre-seeded' }}
                            </td>

                            <td class="py-3.5 px-4 sm:px-6 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('users.print', $user) }}" target="_blank"
                                       class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-slate-200"
                                       title="Cetak Lembar Registrasi Akun & Pakta Akses (PDF)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                    </a>

                                    <button onclick="openEditModal({{ json_encode($user) }})"
                                            class="p-1.5 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-slate-200"
                                            title="Edit Pengguna">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </button>

                                    @if(auth()->id() !== $user->id)
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors border border-slate-200" title="Hapus Pengguna">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                Tidak ada data pengguna yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal 1: Tambah Pengguna Baru -->
<div id="modalAddUser" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="font-bold text-slate-900 text-base">Tambah Pengguna Baru</h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftarkan akun Admin Kantor, Teknisi Lapangan, atau Klien.</p>
            </div>
            <button onclick="document.getElementById('modalAddUser').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-4 mt-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="name" required placeholder="Contoh: Hendra Pratama"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Username Unik *</label>
                    <input type="text" name="username" required placeholder="hendra_tech"
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email Aktif *</label>
                    <input type="email" name="email" required placeholder="hendra@borneofiber.net"
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Peran Akses (Role) *</label>
                    <select name="role" id="addRoleSelect" onchange="toggleTechStatusField(this.value, 'addTechStatusDiv')" required
                            class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="klien">Klien / Pelanggan Mandiri</option>
                        <option value="teknisi" selected>Teknisi Lapangan</option>
                        <option value="admin">Admin Kantor (Banjarmasin)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Password Default *</label>
                    <input type="password" name="password" required value="password123"
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
            </div>

            <div id="addTechStatusDiv">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Status Kesiapan Teknisi</label>
                <select name="technician_status" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    <option value="Available" selected>Available (Siap Ditugaskan SPK)</option>
                    <option value="On Field">On Field (Sedang di Lapangan)</option>
                    <option value="Off Duty">Off Duty (Libur / Tidak Dinas)</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp / Telp</label>
                    <input type="text" name="phone" placeholder="0812-xxxx-xxxx"
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Wilayah / Area</label>
                    <input type="text" name="area" placeholder="Contoh: Banjarmasin Kota"
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Domisili / Pemasangan</label>
                <input type="text" name="address" placeholder="Jl. Kayutangi No. 12, Banjarmasin Utara"
                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalAddUser').classList.add('hidden')"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-medium transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:scale-[1.01]">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Edit Pengguna -->
<div id="modalEditUser" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="font-bold text-slate-900 text-base">Edit Data Pengguna</h3>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi profil, hak akses, atau kontak.</p>
            </div>
            <button onclick="document.getElementById('modalEditUser').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <form id="formEditUser" action="" method="POST" class="space-y-4 mt-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="name" id="editName" required
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Username Unik *</label>
                    <input type="text" name="username" id="editUsername" required
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email Aktif *</label>
                    <input type="email" name="email" id="editEmail" required
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Peran Akses (Role) *</label>
                    <select name="role" id="editRole" onchange="toggleTechStatusField(this.value, 'editTechStatusDiv')" required
                            class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="klien">Klien / Pelanggan Mandiri</option>
                        <option value="teknisi">Teknisi Lapangan</option>
                        <option value="admin">Admin Kantor (Banjarmasin)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Password Baru (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
            </div>

            <div id="editTechStatusDiv">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Status Kesiapan Teknisi</label>
                <select name="technician_status" id="editTechStatus" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    <option value="Available">Available (Siap Ditugaskan SPK)</option>
                    <option value="On Field">On Field (Sedang di Lapangan)</option>
                    <option value="Off Duty">Off Duty (Libur / Tidak Dinas)</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp / Telp</label>
                    <input type="text" name="phone" id="editPhone"
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Wilayah / Area</label>
                    <input type="text" name="area" id="editArea"
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Domisili / Pemasangan</label>
                <input type="text" name="address" id="editAddress"
                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalEditUser').classList.add('hidden')"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-medium transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:scale-[1.01]">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleTechStatusField(roleValue, divId) {
    const el = document.getElementById(divId);
    if (el) {
        if (roleValue === 'teknisi') {
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    }
}

function openEditModal(user) {
    document.getElementById('formEditUser').action = '/users/' + user.id;
    document.getElementById('editName').value = user.name || '';
    document.getElementById('editUsername').value = user.username || '';
    document.getElementById('editEmail').value = user.email || '';
    document.getElementById('editRole').value = user.role || 'klien';
    document.getElementById('editPhone').value = user.phone || '';
    document.getElementById('editArea').value = user.area || '';
    document.getElementById('editAddress').value = user.address || '';
    if (document.getElementById('editTechStatus')) {
        document.getElementById('editTechStatus').value = user.technician_status || 'Available';
    }
    toggleTechStatusField(user.role, 'editTechStatusDiv');
    document.getElementById('modalEditUser').classList.remove('hidden');
}
</script>
@endsection
