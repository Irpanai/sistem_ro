import React, { useState } from 'react';
import { 
  Package, 
  Plus, 
  Search, 
  AlertTriangle, 
  CheckCircle2, 
  ArrowUpDown, 
  PlusCircle, 
  Layers, 
  Boxes,
  TrendingDown,
  Warehouse
} from 'lucide-react';

export default function LogistikPage({ materials, onRestockMaterial, onAddMaterial }) {
  const [searchTerm, setSearchTerm] = useState('');
  const [categoryFilter, setCategoryFilter] = useState('Semua');
  const [stockStatusFilter, setStockStatusFilter] = useState('Semua');

  // Restock modal state
  const [activeRestockMat, setActiveRestockMat] = useState(null);
  const [restockQty, setRestockQty] = useState(10);

  // New Material Modal
  const [showAddModal, setShowAddModal] = useState(false);
  const [newCode, setNewCode] = useState('');
  const [newName, setNewName] = useState('');
  const [newCategory, setNewCategory] = useState('Kabel & Fiber');
  const [newStock, setNewStock] = useState(20);
  const [newMinStock, setNewMinStock] = useState(5);
  const [newUnit, setNewUnit] = useState('Pcs');
  const [newLocation, setNewLocation] = useState('Gudang Banjarmasin');

  const lowStockItems = materials.filter(m => m.stock <= m.minStock);
  const healthyStockItems = materials.filter(m => m.stock > m.minStock);

  const categories = ['Semua', ...Array.from(new Set(materials.map(m => m.category)))];

  const filteredMaterials = materials.filter(m => {
    const matchSearch = m.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                        m.code.toLowerCase().includes(searchTerm.toLowerCase()) ||
                        m.category.toLowerCase().includes(searchTerm.toLowerCase());
    const matchCategory = categoryFilter === 'Semua' || m.category === categoryFilter;
    const isLow = m.stock <= m.minStock;
    const matchStatus = stockStatusFilter === 'Semua' ||
                        (stockStatusFilter === 'Menipis' && isLow) ||
                        (stockStatusFilter === 'Aman' && !isLow);
    return matchSearch && matchCategory && matchStatus;
  });

  const handleRestockSubmit = (e) => {
    e.preventDefault();
    if (!activeRestockMat || restockQty <= 0) return;
    onRestockMaterial(activeRestockMat.id, Number(restockQty));
    setActiveRestockMat(null);
    setRestockQty(10);
  };

  const handleAddNewMaterial = (e) => {
    e.preventDefault();
    if (!newCode.trim() || !newName.trim()) return;

    onAddMaterial({
      id: `MAT-${String(materials.length + 1).padStart(3, '0')}`,
      code: newCode.trim().toUpperCase(),
      name: newName.trim(),
      category: newCategory,
      stock: Number(newStock),
      minStock: Number(newMinStock),
      unit: newUnit,
      price: 'Rp -',
      location: newLocation
    });

    setShowAddModal(false);
    setNewCode('');
    setNewName('');
    setNewStock(20);
    setNewMinStock(5);
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
            <Package className="w-5 h-5 text-blue-600" />
            <span>Master Data Logistik Material &amp; Sparepart Jaringan</span>
          </h2>
          <p className="text-xs text-slate-500 mt-1">
            Monitoring inventaris kabel dropcore, modem ONU, fast connector, splitter, dan peralatan teknisi NOC.
          </p>
        </div>

        <button
          onClick={() => setShowAddModal(true)}
          className="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-xs transition-all self-start sm:self-auto"
        >
          <Plus className="w-4 h-4" />
          <span>Tambah Material Baru</span>
        </button>
      </div>

      {/* Metric Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div className="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center justify-between">
          <div>
            <span className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
              Total Jenis Material
            </span>
            <div className="text-2xl font-extrabold text-slate-900 mt-1">
              {materials.length} <span className="text-xs font-normal text-slate-500">SKU</span>
            </div>
            <div className="text-[11px] text-slate-400 mt-1">
              Tercatat di Gudang Pusat Kalsel
            </div>
          </div>
          <div className="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
            <Boxes className="w-5 h-5" />
          </div>
        </div>

        <div className="bg-white p-5 rounded-2xl border border-amber-200/90 shadow-xs flex items-center justify-between">
          <div>
            <span className="text-xs font-semibold text-amber-700 uppercase tracking-wider flex items-center gap-1">
              <AlertTriangle className="w-3.5 h-3.5 text-amber-500" />
              Peringatan Stok Menipis
            </span>
            <div className="text-2xl font-extrabold text-amber-600 mt-1">
              {lowStockItems.length} <span className="text-xs font-normal text-slate-500">Item</span>
            </div>
            <div className="text-[11px] text-amber-700/80 mt-1">
              Perlu segera dibuat PO Restock
            </div>
          </div>
          <div className="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
            <TrendingDown className="w-5 h-5" />
          </div>
        </div>

        <div className="bg-white p-5 rounded-2xl border border-emerald-200/90 shadow-xs flex items-center justify-between">
          <div>
            <span className="text-xs font-semibold text-emerald-700 uppercase tracking-wider flex items-center gap-1">
              <CheckCircle2 className="w-3.5 h-3.5 text-emerald-500" />
              Stok Tersedia Aman
            </span>
            <div className="text-2xl font-extrabold text-emerald-600 mt-1">
              {healthyStockItems.length} <span className="text-xs font-normal text-slate-500">Item</span>
            </div>
            <div className="text-[11px] text-emerald-700/80 mt-1">
              Siap untuk hand-over tiket SPK
            </div>
          </div>
          <div className="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <Warehouse className="w-5 h-5" />
          </div>
        </div>
      </div>

      {/* Material Inventory Table */}
      <div className="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
          <div>
            <h3 className="text-base font-bold text-slate-800">
              Inventaris Material &amp; Sparepart
            </h3>
            <p className="text-xs text-slate-500">
              Stok berkurang otomatis saat admin menerbitkan SPK tiket perbaikan jaringan.
            </p>
          </div>

          <div className="flex flex-wrap items-center gap-2">
            <div className="relative">
              <Search className="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
              <input
                type="text"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                placeholder="Cari kode atau nama barang..."
                className="pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 w-48 sm:w-56"
              />
            </div>

            <select
              value={categoryFilter}
              onChange={(e) => setCategoryFilter(e.target.value)}
              className="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-slate-700 font-medium focus:outline-none"
            >
              {categories.map(c => (
                <option key={c} value={c}>{c}</option>
              ))}
            </select>

            <select
              value={stockStatusFilter}
              onChange={(e) => setStockStatusFilter(e.target.value)}
              className="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-slate-700 font-medium focus:outline-none"
            >
              <option value="Semua">Semua Kondisi Stok</option>
              <option value="Aman">Stok Aman</option>
              <option value="Menipis">Stok Menipis / Kritis</option>
            </select>
          </div>
        </div>

        <div className="overflow-x-auto mt-4">
          <table className="w-full text-left text-xs">
            <thead>
              <tr className="border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                <th className="pb-3 pl-2">Kode SKU</th>
                <th className="pb-3">Nama Material</th>
                <th className="pb-3">Kategori</th>
                <th className="pb-3 text-center">Sisa Stok</th>
                <th className="pb-3 text-center">Min. Stok</th>
                <th className="pb-3 text-center">Status</th>
                <th className="pb-3">Lokasi Rak</th>
                <th className="pb-3 text-right pr-2">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100 text-slate-700">
              {filteredMaterials.map((mat) => {
                const isLow = mat.stock <= mat.minStock;
                const isCritical = mat.stock === 0;

                return (
                  <tr key={mat.id} className="hover:bg-slate-50/80 transition-colors">
                    <td className="py-3 pl-2 font-mono font-bold text-slate-900">
                      {mat.code}
                    </td>

                    <td className="py-3 font-semibold text-slate-800">
                      {mat.name}
                    </td>

                    <td className="py-3">
                      <span className="text-[10px] font-medium px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">
                        {mat.category}
                      </span>
                    </td>

                    <td className="py-3 text-center">
                      <span className="font-mono text-xs font-bold text-slate-900">
                        {mat.stock} {mat.unit}
                      </span>
                    </td>

                    <td className="py-3 text-center font-mono text-slate-500">
                      {mat.minStock} {mat.unit}
                    </td>

                    <td className="py-3 text-center">
                      {isCritical ? (
                        <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-300">
                          Habis
                        </span>
                      ) : isLow ? (
                        <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300 animate-pulse">
                          Menipis
                        </span>
                      ) : (
                        <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                          Aman
                        </span>
                      )}
                    </td>

                    <td className="py-3 text-slate-500 text-[11px]">
                      {mat.location}
                    </td>

                    <td className="py-3 text-right pr-2">
                      <button
                        onClick={() => setActiveRestockMat(mat)}
                        className="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold text-[11px] border border-blue-200 transition-colors"
                      >
                        <PlusCircle className="w-3.5 h-3.5" />
                        <span>Restock</span>
                      </button>
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>

        {filteredMaterials.length === 0 && (
          <div className="py-12 text-center text-slate-400">
            <Package className="w-8 h-8 mx-auto text-slate-300 mb-2" />
            <p className="text-xs">Tidak ada material yang sesuai kriteria pencarian.</p>
          </div>
        )}
      </div>

      {/* RESTOCK MODAL */}
      {activeRestockMat && (
        <div className="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-200 animate-in fade-in zoom-in-95 duration-150">
            <h3 className="font-bold text-base text-slate-900">Restock Material Gudang</h3>
            <p className="text-xs text-slate-500 mt-1">
              Tambahkan kuantitas barang masuk untuk:
            </p>
            <div className="my-3 p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs">
              <div className="font-bold text-slate-800">{activeRestockMat.name}</div>
              <div className="text-[11px] text-slate-500 font-mono mt-0.5">
                SKU: {activeRestockMat.code} &bull; Stok Sekarang: {activeRestockMat.stock} {activeRestockMat.unit}
              </div>
            </div>

            <form onSubmit={handleRestockSubmit} className="space-y-4">
              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1">
                  Jumlah Penambahan ({activeRestockMat.unit})
                </label>
                <input
                  type="number"
                  min="1"
                  value={restockQty}
                  onChange={(e) => setRestockQty(e.target.value)}
                  className="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-xl font-bold font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                />
              </div>

              <div className="flex items-center justify-end gap-2 pt-2">
                <button
                  type="button"
                  onClick={() => setActiveRestockMat(null)}
                  className="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  className="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition-all"
                >
                  Konfirmasi Tambah Stok
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* ADD NEW MATERIAL MODAL */}
      {showAddModal && (
        <div className="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 animate-in fade-in zoom-in-95 duration-150">
            <h3 className="font-bold text-base text-slate-900">Registrasi Material / SKU Baru</h3>
            <p className="text-xs text-slate-500 mt-1">
              Tambahkan item logistik baru ke dalam inventaris gudang pusat.
            </p>

            <form onSubmit={handleAddNewMaterial} className="space-y-3.5 mt-4">
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">
                    Kode SKU
                  </label>
                  <input
                    type="text"
                    required
                    placeholder="Contoh: SFP-10G-LR"
                    value={newCode}
                    onChange={(e) => setNewCode(e.target.value)}
                    className="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">
                    Kategori
                  </label>
                  <select
                    value={newCategory}
                    onChange={(e) => setNewCategory(e.target.value)}
                    className="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none"
                  >
                    <option value="Kabel & Fiber">Kabel &amp; Fiber</option>
                    <option value="CPE Hardware">CPE Hardware</option>
                    <option value="Accessories">Accessories</option>
                    <option value="ODP Material">ODP Material</option>
                    <option value="Tools">Tools</option>
                  </select>
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1">
                  Nama Barang / Deskripsi
                </label>
                <input
                  type="text"
                  required
                  placeholder="Contoh: Modul SFP+ 10G Single Mode 10km"
                  value={newName}
                  onChange={(e) => setNewName(e.target.value)}
                  className="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                />
              </div>

              <div className="grid grid-cols-3 gap-3">
                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">
                    Stok Awal
                  </label>
                  <input
                    type="number"
                    min="0"
                    value={newStock}
                    onChange={(e) => setNewStock(e.target.value)}
                    className="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono focus:outline-none"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">
                    Min. Stok
                  </label>
                  <input
                    type="number"
                    min="1"
                    value={newMinStock}
                    onChange={(e) => setNewMinStock(e.target.value)}
                    className="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono focus:outline-none"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-700 mb-1">
                    Satuan
                  </label>
                  <select
                    value={newUnit}
                    onChange={(e) => setNewUnit(e.target.value)}
                    className="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none"
                  >
                    <option value="Pcs">Pcs</option>
                    <option value="Unit">Unit</option>
                    <option value="Roll">Roll</option>
                    <option value="Box">Box</option>
                    <option value="Meter">Meter</option>
                  </select>
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1">
                  Lokasi Rak Penyimpanan
                </label>
                <input
                  type="text"
                  value={newLocation}
                  onChange={(e) => setNewLocation(e.target.value)}
                  placeholder="Gudang Banjarmasin - Rak E1"
                  className="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none"
                />
              </div>

              <div className="flex items-center justify-end gap-2 pt-3">
                <button
                  type="button"
                  onClick={() => setShowAddModal(false)}
                  className="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  className="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition-all"
                >
                  Simpan Material
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
