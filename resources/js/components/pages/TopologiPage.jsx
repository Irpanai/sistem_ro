import React, { useState } from 'react';
import { 
  Radio, 
  Plus, 
  Search, 
  MapPin, 
  Activity, 
  CheckCircle2, 
  AlertTriangle, 
  Server, 
  SlidersHorizontal,
  Trash2,
  RefreshCw
} from 'lucide-react';

export default function TopologiPage({ topology, onAddNode, onToggleNodeStatus, onDeleteNode }) {
  // Form State
  const [name, setName] = useState('');
  const [type, setType] = useState('ODP');
  const [area, setArea] = useState('Banjarmasin');
  const [location, setLocation] = useState('');
  const [coordinates, setCoordinates] = useState('');
  const [totalPorts, setTotalPorts] = useState(16);
  const [status, setStatus] = useState('Online');
  const [formError, setFormError] = useState('');
  const [showAddForm, setShowAddForm] = useState(true);

  // Search & Filter
  const [searchTerm, setSearchTerm] = useState('');
  const [filterArea, setFilterArea] = useState('Semua');
  const [filterType, setFilterType] = useState('Semua');

  const handleSubmit = (e) => {
    e.preventDefault();
    if (!name.trim()) {
      setFormError('Nama node wajib diisi (contoh: ODP-BJM-05).');
      return;
    }
    if (!location.trim()) {
      setFormError('Alamat atau lokasi node wajib diisi.');
      return;
    }

    setFormError('');

    const newNode = {
      id: `NODE-${String(topology.length + 1).padStart(2, '0')}`,
      name: name.trim().toUpperCase(),
      type: type,
      area: area,
      location: location.trim(),
      coordinates: coordinates.trim() || '-3.3190, 114.5910',
      totalPorts: Number(totalPorts),
      usedPorts: 0,
      status: status,
      connectedUsers: 0,
      connectedOdp: type === 'OLT' ? 0 : undefined,
      lastCheck: 'Baru saja ditambahkan'
    };

    onAddNode(newNode);

    // Reset Form
    setName('');
    setLocation('');
    setCoordinates('');
    setFormError('');
  };

  // Filter list
  const filteredList = topology.filter(node => {
    const matchSearch = node.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                        node.location.toLowerCase().includes(searchTerm.toLowerCase()) ||
                        node.area.toLowerCase().includes(searchTerm.toLowerCase());
    const matchArea = filterArea === 'Semua' || node.area === filterArea;
    const matchType = filterType === 'Semua' || node.type === filterType;
    return matchSearch && matchArea && matchType;
  });

  return (
    <div className="space-y-6">
      {/* Page Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
            <Radio className="w-5 h-5 text-blue-600" />
            <span>Master Data Topologi Jaringan Fiber Optic</span>
          </h2>
          <p className="text-xs text-slate-500 mt-1">
            Manajemen persebaran titik simpul OLT (Optical Line Terminal) &amp; ODP (Optical Distribution Point) di Kalsel.
          </p>
        </div>

        <button
          onClick={() => setShowAddForm(!showAddForm)}
          className="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-xs transition-all self-start sm:self-auto"
        >
          <Plus className="w-4 h-4" />
          <span>{showAddForm ? 'Tutup Formulir Node' : 'Tambah Node Baru'}</span>
        </button>
      </div>

      {/* FORM INPUT NODE BARU (Input 2) */}
      {showAddForm && (
        <div className="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs animate-in fade-in slide-in-from-top-2 duration-200">
          <div className="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
            <div>
              <h3 className="text-sm font-bold text-slate-800">
                Formulir Tambah Simpul Baru (OLT / ODP)
              </h3>
              <p className="text-xs text-slate-500">
                Lengkapi parameter teknis untuk mendaftarkan node ke dalam sistem monitoring.
              </p>
            </div>
            <span className="text-[11px] font-semibold px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-200">
              Input 2: Registrasi Node
            </span>
          </div>

          {formError && (
            <div className="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-center gap-2">
              <AlertTriangle className="w-4 h-4 text-rose-500 shrink-0" />
              <span>{formError}</span>
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-4">
            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
              {/* ODP/OLT Name */}
              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                  Nama Node / ODP / OLT <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  value={name}
                  onChange={(e) => setName(e.target.value)}
                  placeholder="Contoh: ODP-BJM-05"
                  className="w-full px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-mono"
                />
              </div>

              {/* Node Type */}
              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                  Tipe Perangkat
                </label>
                <select
                  value={type}
                  onChange={(e) => setType(e.target.value)}
                  className="w-full px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                >
                  <option value="ODP">ODP (Optical Distribution Point)</option>
                  <option value="OLT">OLT (Optical Line Terminal)</option>
                </select>
              </div>

              {/* Area / Wilayah */}
              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                  Wilayah / Area
                </label>
                <select
                  value={area}
                  onChange={(e) => setArea(e.target.value)}
                  className="w-full px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                >
                  <option value="Banjarmasin">Banjarmasin</option>
                  <option value="Barito Kuala">Barito Kuala (BTL)</option>
                  <option value="Banjarbaru">Banjarbaru</option>
                </select>
              </div>

              {/* Status */}
              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                  Status Operasional
                </label>
                <select
                  value={status}
                  onChange={(e) => setStatus(e.target.value)}
                  className="w-full px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                >
                  <option value="Online">Online (Aktif Normal)</option>
                  <option value="Offline">Offline (Gangguan / Cut)</option>
                </select>
              </div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
              {/* Coordinates / Location */}
              <div className="sm:col-span-2">
                <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                  Alamat Lengkap / Patokan Tiang <span className="text-rose-500">*</span>
                </label>
                <input
                  type="text"
                  value={location}
                  onChange={(e) => setLocation(e.target.value)}
                  placeholder="Contoh: Jl. Pangeran Hidayatullah Tiang PLN No. 4, Banjarmasin"
                  className="w-full px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                />
              </div>

              {/* Coordinates Lat, Long */}
              <div>
                <label className="block text-xs font-semibold text-slate-700 mb-1.5">
                  Koordinat GPS (Lat, Long)
                </label>
                <input
                  type="text"
                  value={coordinates}
                  onChange={(e) => setCoordinates(e.target.value)}
                  placeholder="-3.3190, 114.5910"
                  className="w-full px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-mono"
                />
              </div>
            </div>

            <div className="flex items-center justify-between pt-2">
              <div className="text-xs text-slate-500 flex items-center gap-1.5">
                <MapPin className="w-3.5 h-3.5 text-slate-400" />
                <span>Node yang ditambahkan akan langsung terhubung ke dashboard diagnostic.</span>
              </div>

              <div className="flex items-center gap-2">
                <button
                  type="button"
                  onClick={() => {
                    setName('');
                    setLocation('');
                    setCoordinates('');
                    setFormError('');
                  }}
                  className="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors"
                >
                  Reset
                </button>

                <button
                  type="submit"
                  className="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition-all"
                >
                  Simpan Node Topologi
                </button>
              </div>
            </div>
          </form>
        </div>
      )}

      {/* TABLE LISTING CURRENT TOPOLOGY DATA */}
      <div className="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
          <div>
            <h3 className="text-base font-bold text-slate-800">
              Daftar Node Jaringan Terdaftar
            </h3>
            <p className="text-xs text-slate-500">
              Menampilkan {filteredList.length} dari total {topology.length} node jaringan OLT &amp; ODP.
            </p>
          </div>

          {/* Search & Area Filters */}
          <div className="flex flex-wrap items-center gap-2">
            <div className="relative">
              <Search className="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
              <input
                type="text"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                placeholder="Cari node, alamat..."
                className="pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 w-48 sm:w-56"
              />
            </div>

            <select
              value={filterArea}
              onChange={(e) => setFilterArea(e.target.value)}
              className="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-slate-700 font-medium focus:outline-none"
            >
              <option value="Semua">Semua Area</option>
              <option value="Banjarmasin">Banjarmasin</option>
              <option value="Barito Kuala">Barito Kuala</option>
            </select>

            <select
              value={filterType}
              onChange={(e) => setFilterType(e.target.value)}
              className="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-slate-700 font-medium focus:outline-none"
            >
              <option value="Semua">Semua Tipe</option>
              <option value="ODP">ODP Saja</option>
              <option value="OLT">OLT Saja</option>
            </select>
          </div>
        </div>

        {/* The Table */}
        <div className="overflow-x-auto mt-4">
          <table className="w-full text-left text-xs">
            <thead>
              <tr className="border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                <th className="pb-3 pl-2">Node Name &amp; ID</th>
                <th className="pb-3">Tipe</th>
                <th className="pb-3">Wilayah &amp; Lokasi</th>
                <th className="pb-3">Koordinat GPS</th>
                <th className="pb-3 text-center">Port Usage</th>
                <th className="pb-3 text-center">Status</th>
                <th className="pb-3 text-right pr-2">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100 text-slate-700">
              {filteredList.map((node) => {
                const isOnline = node.status === 'Online';
                const isOlt = node.type === 'OLT';
                const usage = Math.round((node.usedPorts / node.totalPorts) * 100);

                return (
                  <tr key={node.id} className="hover:bg-slate-50/80 transition-colors">
                    <td className="py-3.5 pl-2 font-mono">
                      <div className="font-bold text-slate-900">{node.name}</div>
                      <div className="text-[10px] text-slate-400">{node.id}</div>
                    </td>

                    <td className="py-3.5">
                      <span
                        className={`text-[10px] font-bold px-2 py-0.5 rounded-md ${
                          isOlt
                            ? 'bg-indigo-100 text-indigo-800 border border-indigo-200'
                            : 'bg-sky-100 text-sky-800 border border-sky-200'
                        }`}
                      >
                        {node.type}
                      </span>
                    </td>

                    <td className="py-3.5 max-w-xs">
                      <div className="font-semibold text-slate-800">{node.area}</div>
                      <div className="text-[11px] text-slate-500 truncate" title={node.location}>
                        {node.location}
                      </div>
                    </td>

                    <td className="py-3.5 font-mono text-[11px] text-slate-500">
                      {node.coordinates}
                    </td>

                    <td className="py-3.5 text-center">
                      <div className="font-mono text-xs font-semibold text-slate-800">
                        {node.usedPorts} / {node.totalPorts}
                      </div>
                      <div className="w-20 mx-auto bg-slate-100 rounded-full h-1 mt-1 overflow-hidden">
                        <div
                          className={`h-1 rounded-full ${
                            !isOnline ? 'bg-rose-500' : 'bg-blue-600'
                          }`}
                          style={{ width: `${usage}%` }}
                        />
                      </div>
                    </td>

                    <td className="py-3.5 text-center">
                      <span
                        className={`inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold ${
                          isOnline
                            ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                            : 'bg-rose-50 text-rose-700 border border-rose-200 animate-pulse'
                        }`}
                      >
                        <span
                          className={`w-1.5 h-1.5 rounded-full ${
                            isOnline ? 'bg-emerald-500' : 'bg-rose-500'
                          }`}
                        />
                        {node.status}
                      </span>
                    </td>

                    <td className="py-3.5 text-right pr-2">
                      <div className="inline-flex items-center gap-1.5">
                        <button
                          onClick={() => onToggleNodeStatus(node.id)}
                          className={`px-2.5 py-1 rounded-lg text-[10px] font-semibold border transition-all ${
                            isOnline
                              ? 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100'
                              : 'bg-emerald-50 border-emerald-200 text-emerald-700 hover:bg-emerald-100'
                          }`}
                          title="Simulasikan ganti status Online/Offline"
                        >
                          {isOnline ? 'Set Offline' : 'Set Online'}
                        </button>

                        <button
                          onClick={() => onDeleteNode(node.id)}
                          className="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                          title="Hapus Node"
                        >
                          <Trash2 className="w-3.5 h-3.5" />
                        </button>
                      </div>
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>

        {filteredList.length === 0 && (
          <div className="py-12 text-center text-slate-400">
            <Radio className="w-8 h-8 mx-auto text-slate-300 mb-2" />
            <p className="text-xs">Tidak ada data topologi yang cocok dengan pencarian.</p>
          </div>
        )}
      </div>
    </div>
  );
}
