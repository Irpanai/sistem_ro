import React, { useState } from 'react';
import { 
  FileText, 
  Search, 
  Download, 
  Printer, 
  Calendar, 
  Package, 
  UserCheck, 
  ArrowUpRight,
  Filter,
  CheckCircle2,
  Clock
} from 'lucide-react';

export default function LaporanPage({ reports = [], technicians = [] }) {
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedTech, setSelectedTech] = useState('Semua');

  // Filtered reports
  const filteredReports = reports.filter((r) => {
    const matchSearch = r.ticketId.toLowerCase().includes(searchTerm.toLowerCase()) ||
                        r.spkNumber?.toLowerCase().includes(searchTerm.toLowerCase()) ||
                        r.technicianName.toLowerCase().includes(searchTerm.toLowerCase()) ||
                        r.materialName.toLowerCase().includes(searchTerm.toLowerCase()) ||
                        r.customerName?.toLowerCase().includes(searchTerm.toLowerCase());
    const matchTech = selectedTech === 'Semua' || r.technicianName === selectedTech;
    return matchSearch && matchTech;
  });

  // Export CSV function
  const handleExportCSV = () => {
    const headers = ['Tanggal', 'No Tiket', 'No SPK', 'Pelanggan', 'Teknisi', 'Material', 'Jumlah', 'Satuan', 'Disetujui Oleh'];
    const rows = filteredReports.map(r => [
      `"${r.date}"`,
      `"${r.ticketId}"`,
      `"${r.spkNumber || '-'}"`,
      `"${r.customerName || '-'}"`,
      `"${r.technicianName}"`,
      `"${r.materialName}"`,
      r.quantity,
      `"${r.unit}"`,
      `"${r.approvedBy}"`
    ]);

    const csvContent = 'data:text/csv;charset=utf-8,' + [headers.join(','), ...rows.map(e => e.join(','))].join('\n');
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', `Laporan_Pengeluaran_Material_SIPKJ_${new Date().toISOString().slice(0,10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  const handlePrint = () => {
    window.print();
  };

  // Quick stats
  const totalItemsOut = reports.reduce((acc, curr) => acc + Number(curr.quantity), 0);
  const totalDropcore = reports
    .filter(r => r.materialName.toLowerCase().includes('dropcore'))
    .reduce((acc, curr) => acc + Number(curr.quantity), 0);
  const totalModem = reports
    .filter(r => r.materialName.toLowerCase().includes('modem'))
    .reduce((acc, curr) => acc + Number(curr.quantity), 0);

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
            <FileText className="w-5 h-5 text-blue-600" />
            <span>Laporan Pengeluaran Material (Material Outbound Report)</span>
          </h2>
          <p className="text-xs text-slate-500 mt-1">
            Rekapitulasi resmi pengeluaran kabel, ONU, dan sparepart yang diserahkan Admin NOC ke teknisi lapangan.
          </p>
        </div>

        <div className="flex items-center gap-2 self-start sm:self-auto">
          <button
            onClick={handleExportCSV}
            className="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:border-slate-300 text-slate-700 text-xs font-semibold shadow-xs hover:bg-slate-50 transition-all"
          >
            <Download className="w-3.5 h-3.5 text-blue-600" />
            <span>Export CSV</span>
          </button>

          <button
            onClick={handlePrint}
            className="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-xs transition-all"
          >
            <Printer className="w-3.5 h-3.5" />
            <span>Cetak Laporan</span>
          </button>
        </div>
      </div>

      {/* Summary KPI Cards for Outbound */}
      <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div className="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
          <span className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
            Total Handover Logistik
          </span>
          <div className="text-2xl font-extrabold text-slate-900 mt-1">
            {reports.length} <span className="text-xs font-normal text-slate-500">Transaksi SPK</span>
          </div>
          <div className="text-[11px] text-slate-400 mt-1">
            Total {totalItemsOut} unit/pcs barang dikeluarkan
          </div>
        </div>

        <div className="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
          <span className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
            Dropcore Terdistribusi
          </span>
          <div className="text-2xl font-extrabold text-blue-600 mt-1">
            {totalDropcore} <span className="text-xs font-normal text-slate-500">Roll (1000m)</span>
          </div>
          <div className="text-[11px] text-slate-400 mt-1">
            Penarikan kabel baru &amp; pergantian putus
          </div>
        </div>

        <div className="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
          <span className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
            Modem ONU Diberikan
          </span>
          <div className="text-2xl font-extrabold text-indigo-600 mt-1">
            {totalModem} <span className="text-xs font-normal text-slate-500">Unit</span>
          </div>
          <div className="text-[11px] text-slate-400 mt-1">
            Penggantian perangkat rusak (ZTE / Huawei)
          </div>
        </div>
      </div>

      {/* Main Table: Material Outbound Report */}
      <div className="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
          <div>
            <h3 className="text-base font-bold text-slate-800">
              Material Outbound Log (Output 3)
            </h3>
            <p className="text-xs text-slate-500">
              Menampilkan {filteredReports.length} riwayat pengeluaran barang logistik dari gudang NOC.
            </p>
          </div>

          <div className="flex flex-wrap items-center gap-2">
            <div className="relative">
              <Search className="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
              <input
                type="text"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                placeholder="Cari SPK, teknisi, material..."
                className="pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 w-48 sm:w-56"
              />
            </div>

            <select
              value={selectedTech}
              onChange={(e) => setSelectedTech(e.target.value)}
              className="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-slate-700 font-medium focus:outline-none"
            >
              <option value="Semua">Semua Teknisi</option>
              {technicians.map((t) => (
                <option key={t.id} value={t.name}>{t.name}</option>
              ))}
            </select>
          </div>
        </div>

        {/* Detailed Table */}
        <div className="overflow-x-auto mt-4">
          <table className="w-full text-left text-xs">
            <thead>
              <tr className="border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                <th className="pb-3 pl-2">Tanggal &amp; Waktu</th>
                <th className="pb-3">Ticket ID &amp; No. SPK</th>
                <th className="pb-3">Customer / Lokasi</th>
                <th className="pb-3">Teknisi Lapangan</th>
                <th className="pb-3">Material yang Diberikan</th>
                <th className="pb-3 text-center">Kuantitas</th>
                <th className="pb-3">Diberikan Oleh</th>
                <th className="pb-3 text-right pr-2">Status</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100 text-slate-700">
              {filteredReports.map((r) => {
                const isCompleted = r.status?.includes('Completed') || r.status === 'Solved';

                return (
                  <tr key={r.id} className="hover:bg-slate-50/80 transition-colors">
                    <td className="py-3.5 pl-2 font-mono text-slate-600">
                      {r.date}
                    </td>

                    <td className="py-3.5 font-mono">
                      <div className="font-bold text-slate-900">{r.ticketId}</div>
                      <div className="text-[10px] text-blue-600 font-semibold">{r.spkNumber || 'SPK Pending'}</div>
                    </td>

                    <td className="py-3.5 max-w-xs font-semibold text-slate-800">
                      {r.customerName || '-'}
                    </td>

                    <td className="py-3.5">
                      <div className="font-bold text-slate-900 flex items-center gap-1.5">
                        <UserCheck className="w-3.5 h-3.5 text-blue-500" />
                        <span>{r.technicianName}</span>
                      </div>
                    </td>

                    <td className="py-3.5 font-medium text-slate-800">
                      <span className="inline-flex items-center gap-1.5">
                        <Package className="w-3.5 h-3.5 text-slate-400" />
                        {r.materialName}
                      </span>
                    </td>

                    <td className="py-3.5 text-center font-mono font-bold text-slate-900">
                      <span className="px-2 py-0.5 rounded bg-slate-100 text-slate-800">
                        {r.quantity} {r.unit}
                      </span>
                    </td>

                    <td className="py-3.5 text-slate-600 font-mono text-[11px]">
                      {r.approvedBy || 'Admin NOC'}
                    </td>

                    <td className="py-3.5 text-right pr-2">
                      {isCompleted ? (
                        <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                          <CheckCircle2 className="w-3 h-3 text-emerald-600" />
                          Terpakai &amp; Selesai
                        </span>
                      ) : (
                        <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                          <Clock className="w-3 h-3 text-blue-500" />
                          Dibawa Teknisi
                        </span>
                      )}
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>

        {filteredReports.length === 0 && (
          <div className="py-12 text-center text-slate-400">
            <FileText className="w-8 h-8 mx-auto text-slate-300 mb-2" />
            <p className="text-xs">Tidak ada riwayat pengeluaran material yang sesuai.</p>
          </div>
        )}
      </div>
    </div>
  );
}
