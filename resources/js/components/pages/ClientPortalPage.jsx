import React, { useState } from 'react';
import { 
  Wifi, 
  WifiOff, 
  AlertCircle, 
  Clock, 
  CheckCircle2, 
  Plus, 
  PhoneCall, 
  MapPin, 
  MessageSquare, 
  Radio, 
  User, 
  Send,
  HelpCircle,
  ShieldCheck,
  ChevronRight
} from 'lucide-react';

export default function ClientPortalPage({ 
  user, 
  tickets = [], 
  onAddNewTicket 
}) {
  const [showNewReportModal, setShowNewReportModal] = useState(false);
  const [issueCategory, setIssueCategory] = useState('LOS Merah');
  const [customDetail, setCustomDetail] = useState('');
  const [phone, setPhone] = useState('0813-4421-9981');
  const [address, setAddress] = useState('Jl. Gatot Subroto Gang 5 No. 18, Banjarmasin Timur');

  // Filter tickets belonging to this client or all client demo tickets
  const myTickets = tickets.filter(
    t => t.customerUsername === user?.username || 
         t.customerName?.toLowerCase().includes(user?.name?.toLowerCase().split(' ')[0] || '') ||
         t.customerName === 'Haji Sulaiman'
  );

  const pendingOrActiveTickets = myTickets.filter(t => t.status !== 'Solved');
  const solvedTickets = myTickets.filter(t => t.status === 'Solved');

  const isCurrentConnectionIssue = pendingOrActiveTickets.length > 0;

  const handleReportSubmit = (e) => {
    e.preventDefault();
    const issueText = `${issueCategory}: ${customDetail.trim() || 'Lampu indikator modem mengalami gangguan, koneksi internet terputus.'}`;

    const newTkt = {
      id: `TKT-2026-${String(tickets.length + 1).padStart(3, '0')}`,
      customerUsername: user?.username || 'klien_sulaiman',
      customerName: user?.name || 'Haji Sulaiman',
      customerPhone: phone,
      address: address,
      area: 'Banjarmasin',
      odpCode: 'ODP-BJM-03',
      issue: issueText,
      status: 'Pending',
      type: 'ISOLIR',
      priority: 'High',
      reportedAt: new Date().toISOString().replace('T', ' ').substring(0, 16),
      analysisDetails: {
        odpStatus: 'Online',
        affectedUsers: 1,
        recommendation: 'Laporan keluhan baru dari pelanggan via Portal Klien. Menunggu analisis NOC.',
        suspectedCause: issueCategory
      },
      workOrder: null
    };

    onAddNewTicket(newTkt);
    setShowNewReportModal(false);
    setCustomDetail('');
  };

  return (
    <div className="space-y-6">
      {/* Top Welcome Banner */}
      <div className={`p-6 rounded-2xl border text-white relative overflow-hidden shadow-md transition-all ${
        isCurrentConnectionIssue
          ? 'bg-gradient-to-r from-amber-600 via-rose-600 to-red-700 border-rose-500'
          : 'bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-950 border-slate-800'
      }`}>
        <div className="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div className="flex items-center gap-2 mb-2">
              <span className="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-white/20 backdrop-blur-xs border border-white/30 text-white">
                ID Pelanggan: CUST-77492 &bull; Paket 50 Mbps Fiber
              </span>
            </div>
            <h2 className="text-xl sm:text-2xl font-bold tracking-tight">
              Selamat Datang, {user?.name || 'Pelanggan'}
            </h2>
            <p className="text-xs sm:text-sm text-slate-200 mt-1 max-w-xl leading-relaxed">
              {isCurrentConnectionIssue 
                ? 'Kami mendeteksi terdapat 1 tiket keluhan aktif Anda yang sedang dalam penanganan tim teknisi NOC.'
                : 'Layanan internet fiber optik Anda berstatus normal. Jika mengalami kendala, silakan klik lapor gangguan di bawah ini.'}
            </p>
          </div>

          <div className="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
            <button
              onClick={() => setShowNewReportModal(true)}
              className="px-4 py-2.5 rounded-xl bg-white text-slate-900 hover:bg-slate-100 font-bold text-xs shadow-lg flex items-center justify-center gap-2 transition-all active:scale-95"
            >
              <Plus className="w-4 h-4 text-blue-600" />
              <span>Buat Laporan Keluhan</span>
            </button>

            <a
              href="https://wa.me/6281255419921?text=Halo%20NOC%20Borneo%20Fiber,%20saya%20ingin%20menanyakan%20status%20koneksi%20internet"
              target="_blank"
              rel="noreferrer"
              className="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg flex items-center justify-center gap-2 transition-all"
            >
              <PhoneCall className="w-4 h-4" />
              <span>WhatsApp CS NOC</span>
            </a>
          </div>
        </div>
      </div>

      {/* KPI Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div className="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
          <div className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
            Status Koneksi Anda
          </div>
          <div className="mt-2 flex items-center gap-2">
            {isCurrentConnectionIssue ? (
              <>
                <WifiOff className="w-6 h-6 text-amber-500" />
                <div>
                  <div className="text-lg font-bold text-amber-600">Dalam Penanganan</div>
                  <div className="text-[11px] text-slate-400">Teknisi ditugaskan</div>
                </div>
              </>
            ) : (
              <>
                <Wifi className="w-6 h-6 text-emerald-500" />
                <div>
                  <div className="text-lg font-bold text-emerald-600">Koneksi Aktif Normal</div>
                  <div className="text-[11px] text-slate-400">Redaman -18.4 dBm (Bagus)</div>
                </div>
              </>
            )}
          </div>
        </div>

        <div className="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
          <div className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
            Tiket Sedang Diproses
          </div>
          <div className="mt-2 flex items-baseline gap-2">
            <span className="text-2xl font-extrabold text-blue-600">
              {pendingOrActiveTickets.length}
            </span>
            <span className="text-xs text-slate-500">Tiket Aktif</span>
          </div>
          <div className="text-[11px] text-slate-400 mt-1">
            NOC merespons rata-rata dalam 15 menit
          </div>
        </div>

        <div className="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
          <div className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
            Riwayat Tiket Tuntas
          </div>
          <div className="mt-2 flex items-baseline gap-2">
            <span className="text-2xl font-extrabold text-emerald-600">
              {solvedTickets.length}
            </span>
            <span className="text-xs text-slate-500">Keluhan Selesai</span>
          </div>
          <div className="text-[11px] text-slate-400 mt-1">
            Garansi perbaikan 7 hari kerja
          </div>
        </div>
      </div>

      {/* Real-time Tracking Tiket Saya */}
      <div className="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
          <div>
            <h3 className="text-base font-bold text-slate-800">
              Status &amp; Riwayat Keluhan Anda (Live Tracking)
            </h3>
            <p className="text-xs text-slate-500">
              Lacak setiap tahapan investigasi masalah jaringan oleh tim NOC dan penanganan di lapangan.
            </p>
          </div>
          <span className="text-xs font-mono font-semibold px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 self-start sm:self-auto">
            {myTickets.length} Laporan Tercatat
          </span>
        </div>

        {myTickets.length > 0 ? (
          <div className="space-y-4 mt-5">
            {myTickets.map((t) => {
              const isPending = t.status === 'Pending';
              const isInProgress = t.status === 'In Progress';
              const isSolved = t.status === 'Solved';

              return (
                <div
                  key={t.id}
                  className="p-4 sm:p-5 rounded-2xl border border-slate-200/90 bg-slate-50/50 hover:bg-slate-50 transition-all space-y-4"
                >
                  {/* Ticket Header */}
                  <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div className="flex items-center gap-2">
                      <span className="font-mono font-bold text-sm text-slate-900 bg-white px-2.5 py-1 rounded-lg border border-slate-200 shadow-2xs">
                        {t.id}
                      </span>
                      <span className="text-xs text-slate-400">&bull; {t.reportedAt}</span>
                    </div>

                    <div className="flex items-center gap-2">
                      {isPending && (
                        <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                          <Clock className="w-3.5 h-3.5 text-amber-500 animate-spin" />
                          Menunggu Analisis NOC
                        </span>
                      )}
                      {isInProgress && (
                        <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                          <Radio className="w-3.5 h-3.5 text-blue-500 animate-pulse" />
                          Sedang Ditangani Teknisi
                        </span>
                      )}
                      {isSolved && (
                        <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                          <CheckCircle2 className="w-3.5 h-3.5 text-emerald-500" />
                          Selesai (Solved)
                        </span>
                      )}
                    </div>
                  </div>

                  {/* Issue description */}
                  <div className="bg-white p-3.5 rounded-xl border border-slate-200 text-xs text-slate-700">
                    <div className="font-semibold text-slate-900 mb-0.5">Kendala yang dilaporkan:</div>
                    <p className="leading-relaxed">{t.issue}</p>
                    <div className="text-[11px] text-slate-400 mt-2 flex items-center gap-1.5">
                      <MapPin className="w-3.5 h-3.5" />
                      <span>{t.address} ({t.odpCode})</span>
                    </div>
                  </div>

                  {/* Visual Stepper Timeline */}
                  <div className="pt-2">
                    <div className="grid grid-cols-3 gap-2 text-center text-xs">
                      {/* Step 1 */}
                      <div className="flex flex-col items-center">
                        <div className="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-xs shadow-xs mb-1">
                          ✓
                        </div>
                        <span className="font-semibold text-slate-800 text-[11px]">1. Laporan Diterima</span>
                        <span className="text-[10px] text-slate-400">CS &amp; NOC Verifikasi</span>
                      </div>

                      {/* Step 2 */}
                      <div className="flex flex-col items-center">
                        <div className={`w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs shadow-xs mb-1 ${
                          isInProgress || isSolved
                            ? 'bg-emerald-500 text-white'
                            : 'bg-amber-500 text-white animate-pulse'
                        }`}>
                          {isInProgress || isSolved ? '✓' : '2'}
                        </div>
                        <span className="font-semibold text-slate-800 text-[11px]">2. Dispatch SPK</span>
                        <span className="text-[10px] text-slate-400">
                          {t.workOrder?.assignedTechnician || 'Alokasi Teknisi'}
                        </span>
                      </div>

                      {/* Step 3 */}
                      <div className="flex flex-col items-center">
                        <div className={`w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs shadow-xs mb-1 ${
                          isSolved
                            ? 'bg-emerald-500 text-white'
                            : 'bg-slate-200 text-slate-600'
                        }`}>
                          {isSolved ? '✓' : '3'}
                        </div>
                        <span className="font-semibold text-slate-800 text-[11px]">3. Selesai</span>
                        <span className="text-[10px] text-slate-400">
                          {isSolved ? 'Koneksi Normal' : 'Verifikasi Akhir'}
                        </span>
                      </div>
                    </div>
                  </div>

                  {/* Technician Notes If Solved */}
                  {t.workOrder?.techNotes && (
                    <div className="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900">
                      <strong>Laporan Teknisi ({t.workOrder?.assignedTechnician}):</strong> {t.workOrder.techNotes}
                    </div>
                  )}
                </div>
              );
            })}
          </div>
        ) : (
          <div className="py-12 text-center text-slate-400">
            <CheckCircle2 className="w-10 h-10 mx-auto text-emerald-400 mb-2" />
            <p className="text-sm font-semibold text-slate-700">Belum ada riwayat keluhan.</p>
            <p className="text-xs text-slate-400 mt-1">Koneksi internet Anda terpantau aman dan stabil.</p>
          </div>
        )}
      </div>

      {/* MODAL BUAT LAPORAN BARU KLIEN */}
      {showNewReportModal && (
        <div className="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 animate-in fade-in zoom-in-95 duration-150">
            <h3 className="font-bold text-base text-slate-900">
              Formulir Laporan Gangguan Internet
            </h3>
            <p className="text-xs text-slate-500 mt-1">
              Laporan Anda akan langsung masuk ke antrean pemantauan teknisi NOC Borneo Fiber.
            </p>

            <form onSubmit={handleReportSubmit} className="space-y-3.5 mt-4">
              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                  Pilih Gejala / Masalah yang Dialami
                </label>
                <div className="grid grid-cols-2 gap-2 text-xs">
                  {[
                    { id: 'LOS Merah', label: 'Lampu LOS Merah Kedip' },
                    { id: 'Internet Lambat', label: 'Internet Lambat / RTO' },
                    { id: 'Modem Mati', label: 'Modem Mati / Terkena Petir' },
                    { id: 'Kabel Putus', label: 'Kabel Dropcore Putus/Tersangkut' }
                  ].map((item) => (
                    <button
                      type="button"
                      key={item.id}
                      onClick={() => setIssueCategory(item.id)}
                      className={`p-2.5 rounded-xl border text-left font-medium transition-all ${
                        issueCategory === item.id
                          ? 'border-blue-600 bg-blue-50 text-blue-700 font-bold'
                          : 'border-slate-200 hover:border-slate-300 text-slate-700'
                      }`}
                    >
                      {item.label}
                    </button>
                  ))}
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1">
                  Keterangan Tambahan (Opsional)
                </label>
                <textarea
                  rows="2"
                  value={customDetail}
                  onChange={(e) => setCustomDetail(e.target.value)}
                  placeholder="Contoh: Kejadian sejak hujan deras tadi subuh, modem sudah dicoba restart tetap merah..."
                  className="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 resize-none"
                />
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1">
                  Nomor HP / WhatsApp Aktif
                </label>
                <input
                  type="text"
                  required
                  value={phone}
                  onChange={(e) => setPhone(e.target.value)}
                  className="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono focus:outline-none"
                />
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1">
                  Alamat Pemasangan
                </label>
                <input
                  type="text"
                  required
                  value={address}
                  onChange={(e) => setAddress(e.target.value)}
                  className="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none"
                />
              </div>

              <div className="flex items-center justify-end gap-2 pt-3">
                <button
                  type="button"
                  onClick={() => setShowNewReportModal(false)}
                  className="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  className="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition-all flex items-center gap-1.5"
                >
                  <Send className="w-3.5 h-3.5" />
                  <span>Kirim Laporan Keluhan</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
