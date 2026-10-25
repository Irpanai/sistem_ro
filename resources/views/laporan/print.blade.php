@extends('layouts.print')

@section('title', 'Cetak Laporan ' . ($tab === 'stok_opname' ? 'Audit Stok Opname' : 'Pengeluaran Material'))

@section('content')
    <!-- Document Title & Header Meta -->
    <div class="text-center mb-6">
        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight uppercase">
            {{ $tab === 'stok_opname' ? 'BERITA ACARA AUDIT REKONSILIASI STOK OPNAME FISIK GUDANG' : 'LAPORAN REKAPITULASI PENGELUARAN LOGISTIK & MATERIAL BULANAN' }}
        </h2>
        <div class="mt-1 flex items-center justify-center gap-2 text-xs font-mono">
            <span class="text-slate-600">Periode:</span>
            <strong class="text-slate-900 font-bold bg-slate-100 px-2.5 py-0.5 rounded border border-slate-300">
                {{ $periodLabel }}
            </strong>
            <span class="text-slate-300">|</span>
            <span class="text-slate-600">Unit:</span>
            <strong class="text-blue-700 font-bold">
                Sentral Logistik Gudang Banjarmasin
            </strong>
        </div>
        <p class="text-xs text-slate-500 mt-1">
            {{ $tab === 'stok_opname' ? 'Hasil audit pencocokan stok fisik material gudang dengan saldo sistem.' : 'Daftar penyerahan material operasional teknisi untuk penanganan gangguan lapangan.' }}
        </p>
    </div>

    <!-- Metadata Badges -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mb-6 text-xs">
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Total Transaksi / SKU</span>
            <strong class="font-bold font-mono text-slate-900 text-sm">{{ $totalCount }} Item</strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Total Unit Barang</span>
            <strong class="font-bold font-mono text-blue-700 text-sm">{{ $totalQty }} Unit</strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Status Kategori</span>
            <strong class="font-bold text-slate-800">{{ $tab === 'stok_opname' ? 'Audit Gudang' : 'Distribusi Lapangan' }}</strong>
        </div>
        <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50">
            <span class="block text-[10px] text-slate-500 font-semibold uppercase">Wilayah</span>
            <strong class="font-bold text-slate-800">Banjarmasin</strong>
        </div>
    </div>

    <!-- Table Content -->
    @if($tab === 'stok_opname')
        <div class="mb-8">
            <table class="w-full text-xs text-left border border-slate-200 rounded-lg overflow-hidden">
                <thead class="bg-slate-100 text-slate-600 text-[10px] uppercase font-semibold">
                    <tr>
                        <th class="p-2 border-b w-8 text-center">No</th>
                        <th class="p-2 border-b">Kode SKU</th>
                        <th class="p-2 border-b">Nama Material</th>
                        <th class="p-2 border-b">Kategori</th>
                        <th class="p-2 border-b">Lokasi Rak</th>
                        <th class="p-2 border-b text-center">Stok Sistem</th>
                        <th class="p-2 border-b text-center">Batas Buffer</th>
                        <th class="p-2 border-b text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-800">
                    @foreach($items as $idx => $m)
                        @php $isLow = $m->stock <= $m->min_stock; @endphp
                        <tr>
                            <td class="p-2 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                            <td class="p-2 font-mono font-bold text-blue-700">{{ $m->code }}</td>
                            <td class="p-2 font-bold">{{ $m->name }}</td>
                            <td class="p-2 text-slate-600">{{ $m->category }}</td>
                            <td class="p-2 text-slate-500">{{ $m->location ?? 'Gudang Utama' }}</td>
                            <td class="p-2 text-center font-mono font-bold {{ $isLow ? 'text-rose-700' : 'text-emerald-700' }}">{{ $m->stock }} {{ $m->unit }}</td>
                            <td class="p-2 text-center font-mono text-slate-500">{{ $m->min_stock }} {{ $m->unit }}</td>
                            <td class="p-2 text-center">
                                <span class="text-[9px] px-1.5 py-0.5 rounded font-semibold {{ $isLow ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                    {{ $isLow ? 'Buffer' : 'Aman' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="mb-8">
            <table class="w-full text-xs text-left border border-slate-200 rounded-lg overflow-hidden">
                <thead class="bg-slate-100 text-slate-600 text-[10px] uppercase font-semibold">
                    <tr>
                        <th class="p-2 border-b w-8 text-center">No</th>
                        <th class="p-2 border-b">Kode SPMB</th>
                        <th class="p-2 border-b">No. Tiket &amp; SPK</th>
                        <th class="p-2 border-b">Pelanggan</th>
                        <th class="p-2 border-b">Teknisi Penerima</th>
                        <th class="p-2 border-b">Nama Barang</th>
                        <th class="p-2 border-b text-center">Qty</th>
                        <th class="p-2 border-b">Waktu Penyerahan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-800">
                    @foreach($items as $idx => $r)
                        <tr>
                            <td class="p-2 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                            <td class="p-2 font-mono font-bold text-slate-800">{{ $r->outbound_code }}</td>
                            <td class="p-2 font-mono text-[11px] text-blue-700">
                                <div>{{ $r->ticket_number }}</div>
                                <div class="text-[10px] text-slate-400">{{ $r->spk_number }}</div>
                            </td>
                            <td class="p-2 font-semibold text-slate-900">{{ $r->customer_name }}</td>
                            <td class="p-2 font-medium text-slate-700">{{ $r->technician_name }}</td>
                            <td class="p-2 font-bold">{{ $r->material_name }}</td>
                            <td class="p-2 text-center font-mono font-bold">{{ $r->quantity }} {{ $r->unit }}</td>
                            <td class="p-2 font-mono text-slate-500 text-[10px]">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Sign-off Block (2 Kolom) -->
    <div class="grid grid-cols-2 gap-8 text-center text-xs pt-4 border-t border-slate-200">
        <div>
            <span class="text-slate-500 text-[10px] block">Dibuat &amp; Diaudit Oleh:</span>
            <strong class="font-bold text-slate-900 block mt-1">Petugas Gudang &amp; Inventaris</strong>
            <div class="h-16 flex items-center justify-center">
                <span class="text-[10px] text-slate-400 font-mono italic">[ Tanda Tangan ]</span>
            </div>
            <span class="font-bold text-slate-800 block border-t border-slate-300 pt-1">
                Tim Logistik Gudang Banjarmasin
            </span>
            <span class="text-[10px] text-slate-400">Sentral Logistik Regional</span>
        </div>

        <div>
            <span class="text-slate-500 text-[10px] block">Mengetahui &amp; Menyetujui:</span>
            <strong class="font-bold text-slate-900 block mt-1">Admin Kantor (Banjarmasin)</strong>
            <div class="h-16 flex items-center justify-center">
                <span class="text-[10px] text-slate-400 font-mono italic">[ Tanda Tangan &amp; Cap ]</span>
            </div>
            <span class="font-bold text-slate-800 block border-t border-slate-300 pt-1">
                {{ auth()->user()?->name ?? 'Admin Kantor Banjarmasin' }}
            </span>
            <span class="text-[10px] text-slate-400">Kepala Administrasi Kantor</span>
        </div>
    </div>
@endsection
