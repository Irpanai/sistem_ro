import React, { useState } from 'react';
import { 
  X, 
  Smartphone, 
  Wifi, 
  Battery, 
  UserCheck, 
  MapPin, 
  Package, 
  CheckCircle2, 
  AlertTriangle, 
  Send, 
  FileText, 
  ChevronDown,
  PhoneCall,
  Clock,
  Sparkles
} from 'lucide-react';

export default function TechnicianSimulationModal({ 
  isOpen, 
  onClose, 
  tickets = [], 
  technicians = [], 
  onResolveTicket 
}) {
  if (!isOpen) return null;

  // Selected technician
  const [selectedTechName, setSelectedTechName] = useState(
    technicians[2]?.name || 'Muhammad Noor'
  );
  
  // Find tickets assigned to this technician that are In Progress or Pending
  const techTickets = tickets.filter(
    t => t.workOrder && t.workOrder.assignedTechnician === selectedTechName
  );

  const [activeTicketId, setActiveTicketId] = useState(
    techTickets.length > 0 ? techTickets[0].id : null
  );

  const selectedTicket = tickets.find(t => t.id === activeTicketId) || techTickets[0] || null;

  // Technician note state
  const [techNotes, setTechNotes] = useState(
    'Telah dilakukan penarikan ulang kabel dropcore 45 meter dan splicing ulang connector. Redaman optik akhir -18.4 dBm. Internet kembali lancar normal.'
  );
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [quickTemplate, setQuickTemplate] = useState('');

  const handleApplyTemplate = (text) => {
    setTechNotes(text);
  };

  const handleSolveSubmit = (e) => {
    e.preventDefault();
    if (!selectedTicket) return;

    setIsSubmitting(true);
    setTimeout(() => {
      setIsSubmitting(false);
      onResolveTicket(selectedTicket.id, techNotes);
    }, 500);
  };

  return (
    <div className="fixed inset-0 z-50 bg-slate-950/75 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
      {/* Mobile Frame Container */}
      <div className="relative w-full max-w-[390px] bg-slate-900 rounded-[44px] p-3.5 shadow-2xl border-[6px] border-slate-700 text-slate-100 flex flex-col h-[780px] max-h-[92vh] overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        {/* Mobile Speaker Notch & Dynamic Island */}
        <div className="flex justify-center items-center h-4 relative">
          <div className="w-24 h-4 bg-black rounded-b-xl flex items-center justify-center gap-1.5 px-3">
            <div className="w-2.5 h-2.5 rounded-full bg-slate-800" />
            <div className="w-10 h-1 bg-slate-800 rounded-full" />
          </div>
        </div>

        {/* Mobile Status Bar */}
        <div className="flex items-center justify-between px-4 py-1 text-[11px] text-slate-300 font-mono">
          <span>09:41</span>
          <div className="flex items-center gap-1.5">
            <span className="text-[10px] font-bold">5G</span>
            <Wifi className="w-3.5 h-3.5" />
            <Battery className="w-4 h-4 text-emerald-400" />
          </div>
        </div>

        {/* App Topbar */}
        <div className="p-3 bg-slate-800/90 rounded-2xl mx-1 mt-1 border border-slate-700/80 flex items-center justify-between">
          <div className="flex items-center gap-2">
            <div className="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-xs">
              <Smartphone className="w-4 h-4" />
            </div>
            <div>
              <div className="text-xs font-bold text-white flex items-center gap-1">
                <span>BFN TechMobile</span>
                <span className="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse" />
              </div>
              <div className="text-[10px] text-slate-400">ISP Field App &bull; v2.2</div>
            </div>
          </div>

          <button
            onClick={onClose}
            className="p-1.5 rounded-lg bg-slate-700/60 hover:bg-slate-700 text-slate-300 transition-colors"
            title="Tutup Simulasi"
          >
            <X className="w-4 h-4" />
          </button>
        </div>

        {/* Technician Switcher */}
        <div className="px-2 pt-2 pb-1">
          <label className="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">
            Masuk Sebagai Teknisi:
          </label>
          <select
            value={selectedTechName}
            onChange={(e) => {
              setSelectedTechName(e.target.value);
              const found = tickets.find(t => t.workOrder?.assignedTechnician === e.target.value && t.status !== 'Solved');
              if (found) setActiveTicketId(found.id);
            }}
            className="w-full px-2.5 py-1.5 text-xs bg-slate-800 border border-slate-700 rounded-xl text-white font-medium focus:outline-none focus:ring-1 focus:ring-blue-500"
          >
            {technicians.map((tech) => (
              <option key={tech.id} value={tech.name}>
                {tech.name} ({tech.area})
              </option>
            ))}
          </select>
        </div>

        {/* Main Content Area (Work Orders & Resolve Form) */}
        <div className="flex-1 overflow-y-auto px-2 py-2 space-y-3">
          {/* Header of Assigned Work Orders */}
          <div className="flex items-center justify-between px-1">
            <span className="text-[11px] font-bold text-slate-300 uppercase tracking-wider">
              Surat Perintah Kerja ({techTickets.length})
            </span>
            <span className="text-[10px] text-sky-400 font-mono">
              GPS Lokasi: Aktif
            </span>
          </div>

          {/* List of assigned tickets pills */}
          {techTickets.length > 0 ? (
            <div className="space-y-2">
              {techTickets.map((t) => {
                const isSelected = selectedTicket?.id === t.id;
                const isSolved = t.status === 'Solved';

                return (
                  <div
                    key={t.id}
                    onClick={() => setActiveTicketId(t.id)}
                    className={`p-3 rounded-2xl border transition-all cursor-pointer ${
                      isSelected
                        ? 'bg-blue-950/60 border-blue-500/80 shadow-md ring-1 ring-blue-500/30'
                        : 'bg-slate-800/60 border-slate-700/60 hover:bg-slate-800'
                    }`}
                  >
                    <div className="flex items-center justify-between text-xs mb-1">
                      <span className="font-mono font-bold text-sky-400">
                        {t.workOrder?.spkNumber || t.id}
                      </span>
                      <span
                        className={`text-[9px] font-bold px-2 py-0.5 rounded-full ${
                          isSolved
                            ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30'
                            : 'bg-amber-500/20 text-amber-400 border border-amber-500/30'
                        }`}
                      >
                        {isSolved ? '✓ SOLVED' : 'IN PROGRESS'}
                      </span>
                    </div>

                    <div className="text-xs font-bold text-white truncate">
                      {t.customerName}
                    </div>

                    <div className="text-[11px] text-slate-400 flex items-center gap-1 mt-1 truncate">
                      <MapPin className="w-3 h-3 text-slate-500 shrink-0" />
                      <span className="truncate">{t.address}</span>
                    </div>

                    <div className="text-[10px] text-slate-400 mt-1 line-clamp-1 bg-slate-900/60 p-1.5 rounded-lg border border-slate-700/40">
                      <strong>Keluhan:</strong> {t.issue}
                    </div>
                  </div>
                );
              })}
            </div>
          ) : (
            <div className="p-6 text-center bg-slate-800/40 border border-slate-700/50 rounded-2xl text-slate-400">
              <CheckCircle2 className="w-8 h-8 mx-auto text-emerald-400 mb-2" />
              <p className="text-xs font-semibold text-slate-300">Semua SPK Selesai!</p>
              <p className="text-[11px] mt-1">Tidak ada tiket aktif yang ditugaskan ke {selectedTechName}.</p>
            </div>
          )}

          {/* ACTIVE WORK ORDER DETAIL & SOLVE FORM */}
          {selectedTicket && (
            <div className="bg-slate-800/90 border border-slate-700 rounded-2xl p-3.5 space-y-3">
              <div className="border-b border-slate-700/80 pb-2">
                <div className="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                  Material Yang Dibawa (Handover Admin):
                </div>
                <div className="mt-1 space-y-1">
                  {selectedTicket.workOrder?.allocatedMaterials && selectedTicket.workOrder.allocatedMaterials.length > 0 ? (
                    selectedTicket.workOrder.allocatedMaterials.map((m, idx) => (
                      <div key={idx} className="flex items-center justify-between text-xs text-slate-200 bg-slate-900/50 px-2 py-1 rounded-lg">
                        <span className="truncate">{m.name}</span>
                        <span className="font-mono font-bold text-sky-400 shrink-0">
                          {m.quantity} {m.unit}
                        </span>
                      </div>
                    ))
                  ) : (
                    <div className="text-[11px] text-slate-400">Standar toolkit fiber optic</div>
                  )}
                </div>
              </div>

              {/* Status & Resolve Section */}
              {selectedTicket.status !== 'Solved' ? (
                <form onSubmit={handleSolveSubmit} className="space-y-3">
                  <div>
                    <label className="block text-[11px] font-bold text-slate-300 mb-1">
                      Catatan Teknis Lapangan (Penyelesaian):
                    </label>
                    <textarea
                      rows="3"
                      required
                      value={techNotes}
                      onChange={(e) => setTechNotes(e.target.value)}
                      placeholder="Contoh: Diganti kabel dropcore 50m, redaman stabil -19 dBm..."
                      className="w-full p-2 text-xs bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 resize-none font-sans"
                    />
                  </div>

                  {/* Quick Preset Buttons */}
                  <div className="space-y-1">
                    <span className="text-[9px] text-slate-400 font-semibold uppercase">Template Cepat:</span>
                    <div className="grid grid-cols-2 gap-1.5">
                      <button
                        type="button"
                        onClick={() => handleApplyTemplate('Penarikan ulang kabel dropcore 40m dan konektor baru. Redaman optik -18.2 dBm normal.')}
                        className="p-1.5 rounded-lg bg-slate-900 hover:bg-slate-700 text-[10px] text-slate-300 text-left border border-slate-700 truncate"
                      >
                        ⚡ Tarik Dropcore 40m
                      </button>
                      <button
                        type="button"
                        onClick={() => handleApplyTemplate('Modem ONU rusak diganti unit baru ZTE F609. Konfigurasi PPPoE selesai, internet lancar.')}
                        className="p-1.5 rounded-lg bg-slate-900 hover:bg-slate-700 text-[10px] text-slate-300 text-left border border-slate-700 truncate"
                      >
                        ⚡ Ganti Modem ONU
                      </button>
                    </div>
                  </div>

                  <button
                    type="submit"
                    disabled={isSubmitting}
                    className="w-full py-2.5 px-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-lg shadow-emerald-600/30 active:scale-[0.98] transition-all flex items-center justify-center gap-2 disabled:opacity-60"
                  >
                    {isSubmitting ? (
                      <>
                        <div className="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                        <span>Menyelesaikan Tiket...</span>
                      </>
                    ) : (
                      <>
                        <CheckCircle2 className="w-4 h-4 text-white" />
                        <span>Tandai Selesai (Set to Solved)</span>
                      </>
                    )}
                  </button>
                </form>
              ) : (
                <div className="p-3 rounded-xl bg-emerald-950/40 border border-emerald-500/40 text-emerald-300 text-xs space-y-1">
                  <div className="flex items-center gap-1.5 font-bold">
                    <CheckCircle2 className="w-4 h-4 text-emerald-400" />
                    <span>Tiket Ini Sudah Ditandai Solved</span>
                  </div>
                  <p className="text-[11px] text-slate-300 leading-normal">
                    {selectedTicket.workOrder?.techNotes || 'Pekerjaan perbaikan di lapangan telah berhasil diselesaikan.'}
                  </p>
                </div>
              )}
            </div>
          )}
        </div>

        {/* Mobile Bottom Navigation Bar Simulation */}
        <div className="mt-1 pt-2 border-t border-slate-800 flex justify-around items-center text-[10px] text-slate-400 px-3">
          <span className="text-sky-400 font-semibold flex items-center gap-1">
            <Smartphone className="w-3.5 h-3.5" /> SPK Tugas
          </span>
          <span>Histori</span>
          <span>Akun</span>
        </div>

        {/* Mobile Home Indicator bar */}
        <div className="w-32 h-1 bg-slate-600 rounded-full mx-auto mt-2" />
      </div>
    </div>
  );
}
