import React, { useState, useEffect } from 'react';
import { 
  X, 
  AlertOctagon, 
  UserX, 
  Radio, 
  Package, 
  UserCheck, 
  FileText, 
  Plus, 
  Trash2, 
  Check, 
  AlertTriangle,
  ArrowRight,
  ShieldCheck,
  MapPin,
  Cpu
} from 'lucide-react';

export default function DispatchModal({ 
  isOpen, 
  onClose, 
  ticket, 
  materials = [], 
  technicians = [], 
  onGenerateSpk 
}) {
  if (!isOpen || !ticket) return null;

  // Modal State
  const [ticketType, setTicketType] = useState(ticket.type || 'ISOLIR');
  const [selectedTech, setSelectedTech] = useState(
    technicians.length > 0 ? technicians[0].name : 'Rizky Ramadhan'
  );
  
  // Handover Material Items: list of { materialId, name, quantity, unit, maxStock }
  const [handoverItems, setHandoverItems] = useState([
    {
      materialId: materials[0]?.id || 'MAT-001',
      name: materials[0]?.name || 'Kabel Dropcore 1 Core (1000m)',
      quantity: ticket.type === 'OUTAGE' ? 2 : 1,
      unit: materials[0]?.unit || 'Roll',
      maxStock: materials[0]?.stock || 10
    }
  ]);

  const [selectedMaterialToAdd, setSelectedMaterialToAdd] = useState(
    materials[1]?.id || 'MAT-004'
  );
  const [addQty, setAddQty] = useState(2);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [validationError, setValidationError] = useState('');

  // Auto recommend ticket type based on ticket analysis
  useEffect(() => {
    if (ticket.analysisDetails?.affectedUsers > 1 || ticket.analysisDetails?.odpStatus === 'Offline') {
      setTicketType('OUTAGE');
    } else {
      setTicketType('ISOLIR');
    }
  }, [ticket]);

  const handleAddItem = () => {
    const mat = materials.find(m => m.id === selectedMaterialToAdd);
    if (!mat) return;

    if (addQty <= 0) {
      setValidationError('Jumlah barang minimal 1.');
      return;
    }

    if (addQty > mat.stock) {
      setValidationError(`Stok ${mat.name} tidak mencukupi (sisa ${mat.stock} ${mat.unit}).`);
      return;
    }

    setValidationError('');

    // Check if already in list
    const existingIndex = handoverItems.findIndex(i => i.materialId === mat.id);
    if (existingIndex >= 0) {
      const updated = [...handoverItems];
      updated[existingIndex].quantity = Number(updated[existingIndex].quantity) + Number(addQty);
      setHandoverItems(updated);
    } else {
      setHandoverItems([
        ...handoverItems,
        {
          materialId: mat.id,
          name: mat.name,
          quantity: Number(addQty),
          unit: mat.unit,
          maxStock: mat.stock
        }
      ]);
    }
  };

  const handleRemoveItem = (index) => {
    setHandoverItems(handoverItems.filter((_, i) => i !== index));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (handoverItems.length === 0) {
      setValidationError('Pilih minimal satu material yang diserahterimakan ke teknisi.');
      return;
    }

    setIsSubmitting(true);
    setValidationError('');

    setTimeout(() => {
      setIsSubmitting(false);
      onGenerateSpk({
        ticketId: ticket.id,
        classificationType: ticketType,
        technicianName: selectedTech,
        materials: handoverItems
      });
      onClose();
    }, 600);
  };

  return (
    <div className="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-5 overflow-y-auto">
      <div className="bg-white rounded-2xl max-w-2xl w-full my-auto shadow-2xl border border-slate-200 overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        {/* Modal Header */}
        <div className="bg-gradient-to-r from-slate-900 to-slate-800 text-white p-5 flex items-start justify-between">
          <div>
            <div className="flex items-center gap-2">
              <span className="text-xs font-mono font-semibold px-2 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30">
                {ticket.id}
              </span>
              <span className="text-xs text-slate-300">
                Simpul: <strong>{ticket.odpCode}</strong>
              </span>
            </div>
            <h3 className="text-lg font-bold text-white mt-1">
              Analyze &amp; Dispatch Work Order (SPK)
            </h3>
            <p className="text-xs text-slate-300 mt-0.5">
              Pelanggan: <strong className="text-white">{ticket.customerName}</strong> &bull; {ticket.address}
            </p>
          </div>

          <button
            onClick={onClose}
            className="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700/60 transition-colors"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* Modal Body Form */}
        <form onSubmit={handleSubmit} className="p-6 space-y-6 max-h-[80vh] overflow-y-auto">
          {validationError && (
            <div className="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-center gap-2">
              <AlertTriangle className="w-4 h-4 text-rose-500 shrink-0" />
              <span>{validationError}</span>
            </div>
          )}

          {/* SECTION A: Network Analysis Result */}
          <div className="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
            <div className="flex items-center justify-between">
              <div className="flex items-center gap-2">
                <Cpu className="w-4 h-4 text-blue-600" />
                <h4 className="text-xs font-bold text-slate-800 uppercase tracking-wider">
                  Section A: Hasil Analisis Jaringan Otomatis (NOC AI Diagnostic)
                </h4>
              </div>
              <span className={`text-[10px] font-bold px-2 py-0.5 rounded-md ${
                ticket.analysisDetails?.odpStatus === 'Offline'
                  ? 'bg-rose-100 text-rose-700 border border-rose-200'
                  : 'bg-emerald-100 text-emerald-700 border border-emerald-200'
              }`}>
                Node {ticket.odpCode}: {ticket.analysisDetails?.odpStatus || 'Active'}
              </span>
            </div>

            <div className="p-3 rounded-lg bg-white border border-slate-200/80 text-xs text-slate-700 leading-relaxed">
              <div className="font-semibold text-slate-900 mb-1 flex items-center gap-1.5">
                {ticket.analysisDetails?.odpStatus === 'Offline' ? (
                  <AlertOctagon className="w-4 h-4 text-rose-600 shrink-0" />
                ) : (
                  <Check className="w-4 h-4 text-emerald-600 shrink-0" />
                )}
                <span>Diagnostik Telemetri:</span>
              </div>
              <p>
                {ticket.analysisDetails?.recommendation || 
                 `Warning: Terdeteksi keluhan pada simpul ${ticket.odpCode}. Pengukuran redaman optik memerlukan inspeksi lapangan.`}
              </p>
              <div className="mt-2 pt-2 border-t border-slate-100 text-[11px] text-slate-500 flex flex-wrap gap-4">
                <span>Pelanggan Terdampak: <strong>{ticket.analysisDetails?.affectedUsers || 1} User</strong></span>
                <span>Dugaan Penyebab: <strong>{ticket.analysisDetails?.suspectedCause || 'Gangguan Fisik Kabel'}</strong></span>
              </div>
            </div>
          </div>

          {/* SECTION B: Radio Buttons to Classify Ticket */}
          <div>
            <div className="flex items-center justify-between mb-2">
              <h4 className="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                <Radio className="w-4 h-4 text-blue-600" />
                <span>Section B: Klasifikasi Insiden Jaringan</span>
              </h4>
              <span className="text-[11px] text-slate-400 font-medium">*Pilih salah satu</span>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
              {/* ISOLIR Option */}
              <label
                className={`p-3.5 rounded-xl border-2 cursor-pointer transition-all flex items-start gap-3 ${
                  ticketType === 'ISOLIR'
                    ? 'border-amber-500 bg-amber-50/50 shadow-xs'
                    : 'border-slate-200 hover:border-slate-300 bg-white'
                }`}
              >
                <input
                  type="radio"
                  name="ticketType"
                  value="ISOLIR"
                  checked={ticketType === 'ISOLIR'}
                  onChange={() => setTicketType('ISOLIR')}
                  className="mt-1 w-4 h-4 text-amber-600 border-slate-300 focus:ring-amber-500"
                />
                <div className="flex-1">
                  <div className="flex items-center gap-1.5">
                    <UserX className="w-4 h-4 text-amber-600" />
                    <span className="font-bold text-xs text-slate-900">ISOLIR (Individual)</span>
                  </div>
                  <p className="text-[11px] text-slate-500 mt-1 leading-normal">
                    Gangguan lokal terisolasi pada 1 pelanggan (contoh: kabel dropcore putus, connector kotor, modem mati).
                  </p>
                </div>
              </label>

              {/* OUTAGE Option */}
              <label
                className={`p-3.5 rounded-xl border-2 cursor-pointer transition-all flex items-start gap-3 ${
                  ticketType === 'OUTAGE'
                    ? 'border-rose-500 bg-rose-50/50 shadow-xs'
                    : 'border-slate-200 hover:border-slate-300 bg-white'
                }`}
              >
                <input
                  type="radio"
                  name="ticketType"
                  value="OUTAGE"
                  checked={ticketType === 'OUTAGE'}
                  onChange={() => setTicketType('OUTAGE')}
                  className="mt-1 w-4 h-4 text-rose-600 border-slate-300 focus:ring-rose-500"
                />
                <div className="flex-1">
                  <div className="flex items-center gap-1.5">
                    <AlertOctagon className="w-4 h-4 text-rose-600" />
                    <span className="font-bold text-xs text-slate-900">OUTAGE (Massal)</span>
                  </div>
                  <p className="text-[11px] text-slate-500 mt-1 leading-normal">
                    Gangguan berdampak banyak pelanggan (contoh: kabel feeder putus tertabrak truk, ODP offline total, OLT power cut).
                  </p>
                </div>
              </label>
            </div>
          </div>

          {/* TECHNICIAN SELECTION */}
          <div>
            <label className="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2 flex items-center gap-2">
              <UserCheck className="w-4 h-4 text-blue-600" />
              <span>Pilih Teknisi Lapangan (Assignment)</span>
            </label>
            <select
              value={selectedTech}
              onChange={(e) => setSelectedTech(e.target.value)}
              className="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
            >
              {technicians.map((tech) => (
                <option key={tech.id} value={tech.name}>
                  {tech.name} ({tech.area}) - Status: {tech.status} &bull; {tech.ticketsSolved} Solved
                </option>
              ))}
            </select>
          </div>

          {/* SECTION C: Material Handover (Serah Terima Material) */}
          <div>
            <div className="flex items-center justify-between mb-2">
              <h4 className="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                <Package className="w-4 h-4 text-blue-600" />
                <span>Section C: Material Handover (Alokasi Sparepart)</span>
              </h4>
              <span className="text-[11px] text-slate-400">
                Stok gudang akan terpotong otomatis
              </span>
            </div>

            {/* Allocated Items List */}
            <div className="space-y-2 mb-3">
              {handoverItems.map((item, idx) => (
                <div
                  key={idx}
                  className="p-2.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs"
                >
                  <div className="flex items-center gap-2">
                    <span className="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-[10px] font-bold">
                      {idx + 1}
                    </span>
                    <div>
                      <span className="font-semibold text-slate-800">{item.name}</span>
                      <span className="text-[10px] text-slate-400 block font-mono">
                        Alokasi: {item.quantity} {item.unit}
                      </span>
                    </div>
                  </div>

                  <button
                    type="button"
                    onClick={() => handleRemoveItem(idx)}
                    className="p-1 text-slate-400 hover:text-rose-600 transition-colors"
                    title="Hapus barang"
                  >
                    <Trash2 className="w-3.5 h-3.5" />
                  </button>
                </div>
              ))}
            </div>

            {/* Quick Add Another Material */}
            <div className="p-3 rounded-xl bg-slate-50/80 border border-slate-200/80 flex flex-col sm:flex-row items-center gap-2">
              <div className="flex-1 w-full">
                <select
                  value={selectedMaterialToAdd}
                  onChange={(e) => setSelectedMaterialToAdd(e.target.value)}
                  className="w-full px-3 py-1.5 text-xs bg-white border border-slate-200 rounded-lg text-slate-700 focus:outline-none"
                >
                  {materials.map((m) => (
                    <option key={m.id} value={m.id} disabled={m.stock <= 0}>
                      {m.name} (Tersedia: {m.stock} {m.unit})
                    </option>
                  ))}
                </select>
              </div>

              <div className="w-24 shrink-0">
                <input
                  type="number"
                  min="1"
                  value={addQty}
                  onChange={(e) => setAddQty(e.target.value)}
                  placeholder="Qty"
                  className="w-full px-3 py-1.5 text-xs bg-white border border-slate-200 rounded-lg text-center font-mono focus:outline-none"
                />
              </div>

              <button
                type="button"
                onClick={handleAddItem}
                className="w-full sm:w-auto px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold flex items-center justify-center gap-1 transition-colors"
              >
                <Plus className="w-3.5 h-3.5" />
                <span>Tambahkan</span>
              </button>
            </div>
          </div>

          {/* Action Footer */}
          <div className="pt-4 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div className="text-xs text-slate-500">
              Work Order (SPK) akan dikirim ke aplikasi mobile teknisi.
            </div>

            <div className="flex items-center gap-2 w-full sm:w-auto justify-end">
              <button
                type="button"
                onClick={onClose}
                className="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors"
              >
                Batal
              </button>

              <button
                type="submit"
                disabled={isSubmitting}
                className="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-semibold shadow-md shadow-blue-500/20 flex items-center gap-2 transition-all disabled:opacity-60"
              >
                {isSubmitting ? (
                  <>
                    <div className="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                    <span>Menerbitkan SPK...</span>
                  </>
                ) : (
                  <>
                    <FileText className="w-4 h-4" />
                    <span>Generate Work Order (SPK)</span>
                  </>
                )}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  );
}
