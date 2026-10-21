import React, { useState } from 'react';
import { 
  Wrench, 
  MapPin, 
  Phone, 
  CheckCircle2, 
  Clock, 
  Package, 
  AlertTriangle, 
  FileText, 
  Award, 
  ShieldCheck, 
  Check,
  Send,
  Sparkles
} from 'lucide-react';

export default function TechnicianPortalPage({ 
  user, 
  tickets = [], 
  onResolveTicket 
}) {
  const [selectedTicketForSolve, setSelectedTicketForSolve] = useState(null);
  const [techNoteText, setTechNoteText] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);

  // Filter tickets assigned to this technician or matching technician name
  const myAssignedTickets = tickets.filter(
    t => t.workOrder && (
      t.workOrder.assignedTechnician === user?.name ||
      t.workOrder.assignedTechnician?.toLowerCase().includes(user?.name?.toLowerCase().split(' ')[0] || '') ||
      t.workOrder.assignedTechnician === 'Rizky Ramadhan'
    )
  );

  const activeWorkOrders = myAssignedTickets.filter(t => t.status !== 'Solved');
  const completedWorkOrders = myAssignedTickets.filter(t => t.status === 'Solved');

  const handleOpenSolve = (ticket) => {
    setSelectedTicketForSolve(ticket);
    setTechNoteText(
      ticket.workOrder?.techNotes || 
      'Telah dilakukan pengecekan kabel dropcore dan splicing ulang. Redaman optik akhir -18.5 dBm stabil.'
    );
  };

  const handleConfirmSolve = (e) => {
    e.preventDefault();
    if (!selectedTicketForSolve) return;

    setIsSubmitting(true);
    setTimeout(() => {
      setIsSubmitting(false);
      onResolveTicket(selectedTicketForSolve.id, techNoteText);
      setSelectedTicketForSolve(null);
      setTechNoteText('');
    }, 500);
  };

  return (
    <div className="space-y-6">
      {/* Top Technician Header */}
      <div className="p-6 rounded-2xl bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 text-white border border-slate-700 shadow-md">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div className="flex items-center gap-3.5">
            <div className="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center font-bold text-lg text-white shadow-md">
              <Wrench className="w-6 h-6" />
            </div>
            <div>
              <div className="flex items-center gap-2">
                <h2 className="text-xl font-bold tracking-tight">{user?.name || 'Teknisi Lapangan'}</h2>
                <span className="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                  Status: Siap Tugas
                </span>
              </div>
              <p className="text-xs text-slate-300 mt-0.5">
                Area Operasional: Banjarmasin &amp; Sekitarnya &bull; No. ID: TECH-01
              </p>
            </div>
          </div>

          <div className="flex items-center gap-3 self-start sm:self-auto bg-slate-800/80 px-4 py-2 rounded-xl border border-slate-700">
            <div className="text-right">
              <div className="text-xs text-slate-400">Total Tiket Tuntas</div>
              <div className="text-lg font-extrabold text-emerald-400">28 Selesai</div>
            </div>
            <Award className="w-6 h-6 text-amber-400" />
          </div>
        </div>
      </div>

      {/* Metric Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div className="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
          <div className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
            SPK Aktif Perlu Dikerjakan
          </div>
          <div className="mt-2 flex items-baseline gap-2">
            <span className="text-3xl font-extrabold text-blue-600">
              {activeWorkOrders.length}
            </span>
            <span className="text-xs text-slate-500">Tugas Lapangan</span>
          </div>
          <div className="text-[11px] text-slate-400 mt-1">
            Prioritas penanganan hari ini
          </div>
        </div>

        <div className="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
          <div className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
            Pekerjaan Selesai
          </div>
          <div className="mt-2 flex items-baseline gap-2">
            <span className="text-3xl font-extrabold text-emerald-600">
              {completedWorkOrders.length}
            </span>
            <span className="text-xs text-slate-500">Tiket Solved</span>
          </div>
          <div className="text-[11px] text-slate-400 mt-1">
            Terverifikasi oleh Admin NOC
          </div>
        </div>

        <div className="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
          <div className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
            Rating Kinerja
          </div>
          <div className="mt-2 flex items-baseline gap-2">
            <span className="text-3xl font-extrabold text-amber-500">
              4.9
            </span>
            <span className="text-xs text-slate-500">/ 5.0 (SLA 98%)</span>
          </div>
          <div className="text-[11px] text-slate-400 mt-1">
            Berdasarkan feedback pelanggan
          </div>
        </div>
      </div>

      {/* Active Work Orders List */}
      <div className="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
          <div>
            <h3 className="text-base font-bold text-slate-800">
              Daftar Surat Perintah Kerja (SPK) Ditugaskan
            </h3>
            <p className="text-xs text-slate-500">
              Daftar pekerjaan perbaikan jaringan yang diteruskan oleh Admin NOC ke tim lapangan.
            </p>
          </div>
          <span className="text-xs font-mono font-semibold px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 self-start sm:self-auto">
            {myAssignedTickets.length} Total SPK
          </span>
        </div>

        <div className="space-y-4 mt-5">
          {myAssignedTickets.length > 0 ? (
            myAssignedTickets.map((t) => {
              const isSolved = t.status === 'Solved';

              return (
                <div
                  key={t.id}
                  className={`p-4 sm:p-5 rounded-2xl border transition-all ${
                    isSolved
                      ? 'bg-emerald-50/30 border-emerald-200'
                      : 'bg-white border-blue-200 shadow-xs hover:border-blue-300'
                  }`}
                >
                  <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
                    <div className="flex items-center gap-2">
                      <span className="font-mono font-bold text-xs bg-slate-900 text-white px-2.5 py-1 rounded-lg">
                        {t.workOrder?.spkNumber || t.id}
                      </span>
                      <span className="font-mono text-xs text-slate-400">({t.id})</span>
                      <span className={`text-[10px] font-bold px-2 py-0.5 rounded-md ${
                        t.type === 'OUTAGE'
                          ? 'bg-rose-100 text-rose-700'
                          : 'bg-amber-100 text-amber-800'
                      }`}>
                        {t.type}
                      </span>
                    </div>

                    <div>
                      {isSolved ? (
                        <span className="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                          <CheckCircle2 className="w-3.5 h-3.5 text-emerald-600" />
                          Tuntas (Solved)
                        </span>
                      ) : (
                        <span className="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 animate-pulse">
                          <Wrench className="w-3.5 h-3.5 text-blue-600" />
                          Perlu Ditangani
                        </span>
                      )}
                    </div>
                  </div>

                  {/* Customer Info */}
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-3 my-3 text-xs">
                    <div>
                      <span className="text-slate-400 block text-[11px]">Nama &amp; Kontak Pelanggan:</span>
                      <span className="font-bold text-slate-900 text-sm">{t.customerName}</span>
                      <a
                        href={`tel:${t.customerPhone}`}
                        className="text-blue-600 hover:text-blue-800 flex items-center gap-1 mt-0.5 font-mono"
                      >
                        <Phone className="w-3 h-3" />
                        {t.customerPhone}
                      </a>
                    </div>

                    <div>
                      <span className="text-slate-400 block text-[11px]">Alamat &amp; Titik ODP:</span>
                      <span className="text-slate-800 font-medium">{t.address}</span>
                      <span className="text-[11px] text-indigo-600 font-mono block mt-0.5">
                        Simpul: <strong>{t.odpCode}</strong> ({t.area})
                      </span>
                    </div>
                  </div>

                  {/* Reported Issue */}
                  <div className="p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-xs text-slate-700 mb-3">
                    <strong className="text-slate-900">Keluhan Pelanggan:</strong> {t.issue}
                  </div>

                  {/* Handover Materials */}
                  <div className="p-3 rounded-xl bg-blue-50/50 border border-blue-100 text-xs text-slate-700 mb-3">
                    <div className="font-bold text-blue-900 mb-1.5 flex items-center gap-1.5">
                      <Package className="w-3.5 h-3.5 text-blue-600" />
                      <span>Material Handover Dari Admin NOC:</span>
                    </div>
                    <div className="flex flex-wrap gap-2">
                      {t.workOrder?.allocatedMaterials && t.workOrder.allocatedMaterials.length > 0 ? (
                        t.workOrder.allocatedMaterials.map((m, idx) => (
                          <span
                            key={idx}
                            className="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white border border-blue-200 font-semibold text-slate-800 text-[11px] shadow-2xs"
                          >
                            <span>{m.name}:</span>
                            <span className="font-mono text-blue-600 font-bold">{m.quantity} {m.unit}</span>
                          </span>
                        ))
                      ) : (
                        <span className="text-slate-400 text-[11px]">Standard technician toolkit</span>
                      )}
                    </div>
                  </div>

                  {/* Solved Note or Action Button */}
                  {isSolved ? (
                    <div className="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900">
                      <strong>Catatan Teknis Selesai:</strong> {t.workOrder?.techNotes}
                    </div>
                  ) : (
                    <div className="flex justify-end pt-2">
                      <button
                        onClick={() => handleOpenSolve(t)}
                        className="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm flex items-center gap-1.5 transition-all active:scale-95"
                      >
                        <CheckCircle2 className="w-4 h-4" />
                        <span>Selesaikan Pekerjaan (Mark Solved)</span>
                      </button>
                    </div>
                  )}
                </div>
              );
            })
          ) : (
            <div className="py-12 text-center text-slate-400">
              <CheckCircle2 className="w-10 h-10 mx-auto text-emerald-400 mb-2" />
              <p className="text-sm font-semibold text-slate-700">Tidak ada SPK aktif saat ini.</p>
              <p className="text-xs text-slate-400 mt-1">Semua pekerjaan perbaikan telah diselesaikan dengan baik.</p>
            </div>
          )}
        </div>
      </div>

      {/* RESOLVE WORK ORDER MODAL */}
      {selectedTicketForSolve && (
        <div className="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 animate-in fade-in zoom-in-95 duration-150">
            <h3 className="font-bold text-base text-slate-900">
              Konfirmasi Penyelesaian Pekerjaan (SPK)
            </h3>
            <p className="text-xs text-slate-500 mt-1">
              Tiket <strong>{selectedTicketForSolve.id}</strong> &bull; {selectedTicketForSolve.customerName}
            </p>

            <form onSubmit={handleConfirmSolve} className="space-y-3.5 mt-4">
              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1">
                  Catatan Teknis Lapangan (Material terpakai &amp; hasil redaman) <span className="text-rose-500">*</span>
                </label>
                <textarea
                  rows="3"
                  required
                  value={techNoteText}
                  onChange={(e) => setTechNoteText(e.target.value)}
                  placeholder="Contoh: Kabel dropcore ditarik ulang 40m, redaman -18.2 dBm stabil, pelanggan sudah tanda tangan BAST..."
                  className="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 resize-none font-sans"
                />
              </div>

              <div className="flex items-center justify-end gap-2 pt-3">
                <button
                  type="button"
                  onClick={() => setSelectedTicketForSolve(null)}
                  className="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  disabled={isSubmitting}
                  className="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1.5 disabled:opacity-60"
                >
                  {isSubmitting ? (
                    <>
                      <div className="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                      <span>Menyimpan...</span>
                    </>
                  ) : (
                    <>
                      <CheckCircle2 className="w-4 h-4" />
                      <span>Kirim Laporan &amp; Set Solved</span>
                    </>
                  )}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
