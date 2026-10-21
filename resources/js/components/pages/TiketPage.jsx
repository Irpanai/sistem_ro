import React, { useState } from 'react';
import { 
  Ticket, 
  Search, 
  Filter, 
  Plus, 
  AlertOctagon, 
  UserX, 
  CheckCircle2, 
  Clock, 
  Wrench, 
  FileText, 
  MapPin, 
  Phone, 
  Radio,
  ExternalLink,
  ChevronRight
} from 'lucide-react';

export default function TiketPage({ 
  tickets, 
  topology, 
  onOpenDispatchModal, 
  onAddNewTicket 
}) {
  const [searchTerm, setSearchTerm] = useState('');
  const [statusFilter, setStatusFilter] = useState('Semua');
  const [typeFilter, setTypeFilter] = useState('Semua');

  // New ticket modal
  const [showNewModal, setShowNewModal] = useState(false);
  const [custName, setCustName] = useState('');
  const [custPhone, setCustPhone] = useState('');
  const [address, setAddress] = useState('');
  const [area, setArea] = useState('Banjarmasin');
  const [odpCode, setOdpCode] = useState('ODP-BJM-01');
  const [issue, setIssue] = useState('');
  const [priority, setPriority] = useState('High');

  const filteredTickets = tickets.filter(t => {
    const matchSearch = t.customerName.toLowerCase().includes(searchTerm.toLowerCase()) ||
                        t.address.toLowerCase().includes(searchTerm.toLowerCase()) ||
                        t.id.toLowerCase().includes(searchTerm.toLowerCase()) ||
                        t.odpCode.toLowerCase().includes(searchTerm.toLowerCase()) ||
                        t.issue.toLowerCase().includes(searchTerm.toLowerCase());
    const matchStatus = statusFilter === 'Semua' || t.status === statusFilter;
    const matchType = typeFilter === 'Semua' || t.type === typeFilter;
    return matchSearch && matchStatus && matchType;
  });

  const handleCreateTicket = (e) => {
    e.preventDefault();
    if (!custName.trim() || !address.trim() || !issue.trim()) return;

    // Check ODP status from topology
    const odpNode = topology.find(n => n.name === odpCode);
    const isOdpOffline = odpNode?.status === 'Offline';

    const newTkt = {
      id: `TKT-2026-${String(tickets.length + 1).padStart(3, '0')}`,
      customerName: custName.trim(),
      customerPhone: custPhone.trim() || '0812-XXXX-XXXX',
      address: address.trim(),
      area: area,
      odpCode: odpCode,
      issue: issue.trim(),
      status: 'Pending',
      type: isOdpOffline ? 'OUTAGE' : 'ISOLIR',
      priority: priority,
      reportedAt: new Date().toISOString().replace('T', ' ').substring(0, 16),
      analysisDetails: {
        odpStatus: odpNode?.status || 'Online',
        affectedUsers: isOdpOffline ? (odpNode?.connectedUsers || 8) : 1,
        recommendation: isOdpOffline 
          ? `Perhatian: ${odpCode} berstatus Offline. Indikasi gangguan massal pada feeder.`
          : `ODP ${odpCode} berstatus Online normal. Gangguan lokal pada pelanggan.`,
        suspectedCause: isOdpOffline ? 'Outage Massal ODP' : 'Isolir Lokal Pelanggan'
      },
      workOrder: null
    };

    onAddNewTicket(newTkt);
    setShowNewModal(false);
    setCustName('');
    setCustPhone('');
    setAddress('');
    setIssue('');
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
            <Ticket className="w-5 h-5 text-blue-600" />
            <span>Keluhan Pelanggan &amp; Ticketing System</span>
          </h2>
          <p className="text-xs text-slate-500 mt-1">
            Data incoming keluhan, klasifikasi cerdas (Outage / Isolir), dan penerbitan Surat Perintah Kerja (SPK).
          </p>
        </div>

        <button
          onClick={() => setShowNewModal(true)}
          className="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-xs transition-all self-start sm:self-auto"
        >
          <Plus className="w-4 h-4" />
          <span>Input Keluhan Baru</span>
        </button>
      </div>

      {/* Main Data Table */}
      <div className="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
          <div>
            <h3 className="text-base font-bold text-slate-800">
              Antrean Keluhan Pelanggan (Incoming Tickets)
            </h3>
            <p className="text-xs text-slate-500">
              Klik <strong>"Analyze &amp; Dispatch"</strong> pada tiket untuk mengklasifikasikan insiden dan menugaskan teknisi dengan material.
            </p>
          </div>

          <div className="flex flex-wrap items-center gap-2">
            <div className="relative">
              <Search className="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
              <input
                type="text"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                placeholder="Cari nama, tiket, alamat..."
                className="pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 w-48 sm:w-56"
              />
            </div>

            <select
              value={statusFilter}
              onChange={(e) => setStatusFilter(e.target.value)}
              className="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-slate-700 font-medium focus:outline-none"
            >
              <option value="Semua">Semua Status</option>
              <option value="Pending">Pending (Menunggu SPK)</option>
              <option value="In Progress">In Progress (Di Lapangan)</option>
              <option value="Solved">Solved (Selesai)</option>
            </select>

            <select
              value={typeFilter}
              onChange={(e) => setTypeFilter(e.target.value)}
              className="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-slate-700 font-medium focus:outline-none"
            >
              <option value="Semua">Semua Tipe</option>
              <option value="OUTAGE">OUTAGE (Massal)</option>
              <option value="ISOLIR">ISOLIR (Individu)</option>
            </select>
          </div>
        </div>

        {/* The Table */}
        <div className="overflow-x-auto mt-4">
          <table className="w-full text-left text-xs">
            <thead>
              <tr className="border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                <th className="pb-3 pl-2">ID &amp; Tanggal</th>
                <th className="pb-3">Customer Name</th>
                <th className="pb-3">Address &amp; ODP</th>
                <th className="pb-3">Deskripsi Gangguan</th>
                <th className="pb-3 text-center">Tipe Insiden</th>
                <th className="pb-3 text-center">Status</th>
                <th className="pb-3 text-right pr-2">Aksi Dispatch</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100 text-slate-700">
              {filteredTickets.map((t) => {
                const isPending = t.status === 'Pending';
                const isInProgress = t.status === 'In Progress';
                const isSolved = t.status === 'Solved';
                const isOutage = t.type === 'OUTAGE';

                return (
                  <tr key={t.id} className="hover:bg-slate-50/80 transition-colors">
                    <td className="py-3.5 pl-2 font-mono">
                      <div className="font-bold text-slate-900">{t.id}</div>
                      <div className="text-[10px] text-slate-400">{t.reportedAt}</div>
                    </td>

                    <td className="py-3.5">
                      <div className="font-bold text-slate-900">{t.customerName}</div>
                      <div className="text-[11px] text-slate-500 flex items-center gap-1">
                        <Phone className="w-3 h-3 text-slate-400" />
                        <span>{t.customerPhone}</span>
                      </div>
                    </td>

                    <td className="py-3.5 max-w-xs">
                      <div className="text-slate-800 line-clamp-1" title={t.address}>
                        {t.address}
                      </div>
                      <div className="text-[11px] text-blue-600 font-mono font-semibold flex items-center gap-1 mt-0.5">
                        <Radio className="w-3 h-3 text-blue-500" />
                        <span>{t.odpCode}</span>
                        <span className="text-slate-400 font-normal">({t.area})</span>
                      </div>
                    </td>

                    <td className="py-3.5 max-w-xs">
                      <p className="text-slate-700 line-clamp-2 leading-relaxed" title={t.issue}>
                        {t.issue}
                      </p>
                    </td>

                    <td className="py-3.5 text-center">
                      {isOutage ? (
                        <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-300">
                          <AlertOctagon className="w-3 h-3 text-rose-600" />
                          OUTAGE (Massal)
                        </span>
                      ) : (
                        <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                          <UserX className="w-3 h-3 text-amber-600" />
                          ISOLIR (Individu)
                        </span>
                      )}
                    </td>

                    <td className="py-3.5 text-center">
                      {isPending && (
                        <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                          <Clock className="w-3 h-3 text-amber-500" />
                          Pending
                        </span>
                      )}
                      {isInProgress && (
                        <div className="inline-flex flex-col items-center">
                          <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                            <Wrench className="w-3 h-3 text-blue-600 animate-spin" />
                            In Progress
                          </span>
                          <span className="text-[9px] text-slate-400 font-mono mt-0.5">
                            {t.workOrder?.assignedTechnician?.split(' ')[0]}
                          </span>
                        </div>
                      )}
                      {isSolved && (
                        <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                          <CheckCircle2 className="w-3 h-3 text-emerald-600" />
                          Solved
                        </span>
                      )}
                    </td>

                    <td className="py-3.5 text-right pr-2">
                      {isPending ? (
                        <button
                          onClick={() => onOpenDispatchModal(t)}
                          className="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold text-xs shadow-xs hover:shadow transition-all"
                        >
                          <Wrench className="w-3.5 h-3.5" />
                          <span>Analyze &amp; Dispatch</span>
                        </button>
                      ) : isInProgress ? (
                        <button
                          onClick={() => onOpenDispatchModal(t)}
                          className="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs border border-slate-200 transition-colors"
                        >
                          <FileText className="w-3.5 h-3.5 text-blue-600" />
                          <span>Detail SPK</span>
                        </button>
                      ) : (
                        <span className="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">
                          <CheckCircle2 className="w-3 h-3" />
                          Tuntas
                        </span>
                      )}
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>

        {filteredTickets.length === 0 && (
          <div className="py-12 text-center text-slate-400">
            <Ticket className="w-8 h-8 mx-auto text-slate-300 mb-2" />
            <p className="text-xs">Tidak ada tiket yang cocok dengan filter pencarian.</p>
          </div>
        )}
      </div>

      {/* MODAL INPUT KELUHAN BARU */}
      {showNewModal && (
        <div className="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 animate-in fade-in zoom-in-95 duration-150">
            <h3 className="font-bold text-base text-slate-900">
              Input Keluhan Pelanggan Baru (CS / Call Center)
            </h3>
            <p className="text-xs text-slate-500 mt-1">
              Catat laporan gangguan dari pelanggan untuk dimasukkan ke antrean dispatch NOC.
            </p>

            <form onSubmit={handleCreateTicket} className="space-y-3.5 mt-4">
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">
                    Nama Pelanggan <span className="text-rose-500">*</span>
                  </label>
                  <input
                    type="text"
                    required
                    placeholder="Contoh: Bpk. Kurniawan"
                    value={custName}
                    onChange={(e) => setCustName(e.target.value)}
                    className="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">
                    No. Handphone / WhatsApp
                  </label>
                  <input
                    type="text"
                    placeholder="0812-XXXX-XXXX"
                    value={custPhone}
                    onChange={(e) => setCustPhone(e.target.value)}
                    className="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                  />
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1">
                  Alamat Lengkap Rumah / Kantor <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  placeholder="Jl. Pramuka Komplek Rahayu No. 15, Banjarmasin"
                  value={address}
                  onChange={(e) => setAddress(e.target.value)}
                  className="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                />
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">
                    Wilayah Layanan
                  </label>
                  <select
                    value={area}
                    onChange={(e) => setArea(e.target.value)}
                    className="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none"
                  >
                    <option value="Banjarmasin">Banjarmasin</option>
                    <option value="Barito Kuala">Barito Kuala</option>
                  </select>
                </div>

                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">
                    Simpul ODP Terdekat
                  </label>
                  <select
                    value={odpCode}
                    onChange={(e) => setOdpCode(e.target.value)}
                    className="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono focus:outline-none"
                  >
                    {topology.map((node) => (
                      <option key={node.id} value={node.name}>
                        {node.name} ({node.area}) - {node.status}
                      </option>
                    ))}
                  </select>
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1">
                  Uraian Masalah / Gejala Gangguan <span className="text-rose-500">*</span>
                </label>
                <textarea
                  rows="3"
                  required
                  placeholder="Jelaskan kondisi lampu indikator modem (LOS merah, PON kedip), atau kabel putus..."
                  value={issue}
                  onChange={(e) => setIssue(e.target.value)}
                  className="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 resize-none"
                />
              </div>

              <div className="flex items-center justify-end gap-2 pt-3">
                <button
                  type="button"
                  onClick={() => setShowNewModal(false)}
                  className="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  className="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition-all"
                >
                  Kirim Tiket ke Antrean
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
