import React, { useState } from 'react';
import { 
  AlertOctagon, 
  UserX, 
  CheckCircle2, 
  Radio, 
  Activity, 
  Server, 
  RefreshCw, 
  MapPin, 
  Wifi, 
  WifiOff, 
  Award, 
  ArrowUpRight,
  Filter,
  Check,
  TrendingUp,
  Clock
} from 'lucide-react';
import { 
  PieChart, 
  Pie, 
  Cell, 
  ResponsiveContainer, 
  Tooltip, 
  Legend 
} from 'recharts';

export default function DashboardPage({ 
  tickets, 
  topology, 
  technicians, 
  onNavigateToTiket,
  onRefreshDiagnostic 
}) {
  const [selectedArea, setSelectedArea] = useState('Semua');
  const [selectedStatus, setSelectedStatus] = useState('Semua');
  const [isRefreshing, setIsRefreshing] = useState(false);

  // 1. Calculations for Summary Cards
  const activeTickets = tickets.filter(t => t.status !== 'Solved');
  const outageIncidents = tickets.filter(t => t.type === 'OUTAGE' && t.status !== 'Solved');
  const isolirIncidents = tickets.filter(t => t.type === 'ISOLIR' && t.status !== 'Solved');
  const solvedTickets = tickets.filter(t => t.status === 'Solved');

  // 2. Filter topology nodes
  const filteredTopology = topology.filter(node => {
    const areaMatch = selectedArea === 'Semua' || node.area === selectedArea;
    const statusMatch = selectedStatus === 'Semua' || node.status === selectedStatus;
    return areaMatch && statusMatch;
  });

  const onlineNodesCount = topology.filter(n => n.status === 'Online').length;
  const offlineNodesCount = topology.filter(n => n.status === 'Offline').length;

  // 3. Data for Outage vs Isolir Pie Chart
  const totalOutageCount = tickets.filter(t => t.type === 'OUTAGE').length;
  const totalIsolirCount = tickets.filter(t => t.type === 'ISOLIR').length;
  
  const pieData = [
    { name: 'Outage (Massal)', value: totalOutageCount, color: '#EF4444' },
    { name: 'Isolir (Individu)', value: totalIsolirCount, color: '#F97316' }
  ];

  const handleRefresh = () => {
    setIsRefreshing(true);
    if (onRefreshDiagnostic) onRefreshDiagnostic();
    setTimeout(() => {
      setIsRefreshing(false);
    }, 700);
  };

  const CustomTooltip = ({ active, payload }) => {
    if (active && payload && payload.length) {
      const data = payload[0];
      const total = totalOutageCount + totalIsolirCount;
      const percentage = total > 0 ? ((data.value / total) * 100).toFixed(1) : 0;
      return (
        <div className="bg-slate-900 text-white p-3 rounded-xl shadow-xl border border-slate-700 text-xs">
          <p className="font-semibold text-slate-200">{data.name}</p>
          <p className="text-sm font-bold mt-1" style={{ color: data.payload.color }}>
            {data.value} Kasus ({percentage}%)
          </p>
          <p className="text-[10px] text-slate-400 mt-0.5">Klasifikasi Insiden Jaringan</p>
        </div>
      );
    }
    return null;
  };

  return (
    <div className="space-y-6">
      {/* Page Title & Breadcrumb */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
            <span>Network Operations Center (NOC) Dashboard</span>
          </h2>
          <p className="text-xs text-slate-500 mt-1">
            Real-time monitoring keluhan jaringan, status transmisi OLT/ODP, dan penugasan teknisi lapangan.
          </p>
        </div>

        <div className="flex items-center gap-2">
          <button
            onClick={handleRefresh}
            disabled={isRefreshing}
            className="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:border-slate-300 text-slate-700 text-xs font-semibold shadow-xs hover:bg-slate-50 transition-all disabled:opacity-50"
          >
            <RefreshCw className={`w-3.5 h-3.5 ${isRefreshing ? 'animate-spin text-blue-600' : ''}`} />
            <span>{isRefreshing ? 'Memindai Ulang...' : 'Segarkan Data'}</span>
          </button>

          <button
            onClick={onNavigateToTiket}
            className="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm hover:shadow transition-all"
          >
            <span>Buka Antrean Tiket</span>
            <ArrowUpRight className="w-3.5 h-3.5" />
          </button>
        </div>
      </div>

      {/* TOP SECTION: Summary Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {/* Card 1: Total Active Tickets */}
        <div className="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs relative overflow-hidden group hover:shadow-md transition-all">
          <div className="flex items-center justify-between">
            <span className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
              Total Active Tickets
            </span>
            <div className="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
              <Activity className="w-5 h-5" />
            </div>
          </div>
          <div className="mt-3 flex items-baseline gap-2">
            <span className="text-3xl font-extrabold text-slate-900 tracking-tight">
              {activeTickets.length}
            </span>
            <span className="text-xs font-medium text-slate-500">Tiket Berjalan</span>
          </div>
          <div className="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <span className="text-amber-600 font-medium">
              {tickets.filter(t => t.status === 'Pending').length} Pending
            </span>
            <span className="text-blue-600 font-medium">
              {tickets.filter(t => t.status === 'In Progress').length} Dispatched
            </span>
          </div>
        </div>

        {/* Card 2: Outage Incidents (Red) */}
        <div className="bg-white p-5 rounded-2xl border border-rose-200/90 shadow-xs relative overflow-hidden group hover:shadow-md transition-all">
          <div className="absolute top-0 left-0 right-0 h-1 bg-rose-500" />
          <div className="flex items-center justify-between">
            <span className="text-xs font-semibold text-rose-600 uppercase tracking-wider flex items-center gap-1.5">
              <span className="w-2 h-2 rounded-full bg-rose-500 animate-ping" />
              Outage Incidents
            </span>
            <div className="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform">
              <AlertOctagon className="w-5 h-5" />
            </div>
          </div>
          <div className="mt-3 flex items-baseline gap-2">
            <span className="text-3xl font-extrabold text-rose-600 tracking-tight">
              {outageIncidents.length}
            </span>
            <span className="text-xs font-semibold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700">
              Massal / Feeder
            </span>
          </div>
          <div className="mt-3 pt-3 border-t border-rose-50 flex items-center justify-between text-xs text-slate-500">
            <span>Dampak: Multi-User / ODP</span>
            <span className="text-rose-600 font-semibold">Prioritas Utama</span>
          </div>
        </div>

        {/* Card 3: Isolir Incidents (Orange) */}
        <div className="bg-white p-5 rounded-2xl border border-amber-200/90 shadow-xs relative overflow-hidden group hover:shadow-md transition-all">
          <div className="absolute top-0 left-0 right-0 h-1 bg-amber-500" />
          <div className="flex items-center justify-between">
            <span className="text-xs font-semibold text-amber-600 uppercase tracking-wider">
              Isolir Incidents
            </span>
            <div className="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
              <UserX className="w-5 h-5" />
            </div>
          </div>
          <div className="mt-3 flex items-baseline gap-2">
            <span className="text-3xl font-extrabold text-amber-600 tracking-tight">
              {isolirIncidents.length}
            </span>
            <span className="text-xs font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">
              Individu
            </span>
          </div>
          <div className="mt-3 pt-3 border-t border-amber-50 flex items-center justify-between text-xs text-slate-500">
            <span>Redaman tinggi / Modem rusak</span>
            <span className="text-amber-700 font-medium">1 Pelanggan</span>
          </div>
        </div>

        {/* Card 4: Solved Tickets (Green) */}
        <div className="bg-white p-5 rounded-2xl border border-emerald-200/90 shadow-xs relative overflow-hidden group hover:shadow-md transition-all">
          <div className="absolute top-0 left-0 right-0 h-1 bg-emerald-500" />
          <div className="flex items-center justify-between">
            <span className="text-xs font-semibold text-emerald-600 uppercase tracking-wider">
              Solved Tickets
            </span>
            <div className="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
              <CheckCircle2 className="w-5 h-5" />
            </div>
          </div>
          <div className="mt-3 flex items-baseline gap-2">
            <span className="text-3xl font-extrabold text-emerald-600 tracking-tight">
              {solvedTickets.length}
            </span>
            <span className="text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
              Tuntas
            </span>
          </div>
          <div className="mt-3 pt-3 border-t border-emerald-50 flex items-center justify-between text-xs text-slate-500">
            <span>SLA Penyelesaian: 94.2%</span>
            <span className="text-emerald-700 font-medium">Clear</span>
          </div>
        </div>
      </div>

      {/* MIDDLE SECTION: Network Diagnostic View (Real-time OLT & ODP status) */}
      <div className="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
          <div>
            <div className="flex items-center gap-2">
              <div className="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse" />
              <h3 className="text-base font-bold text-slate-800 tracking-tight">
                Network Diagnostic View (Real-Time OLT &amp; ODP Status)
              </h3>
            </div>
            <p className="text-xs text-slate-500 mt-1">
              Live telemetry node transmisi fiber optic wilayah Banjarmasin dan Barito Kuala (BTL).
            </p>
          </div>

          {/* Area & Status Filter Controls */}
          <div className="flex flex-wrap items-center gap-2">
            {/* Area Filter Buttons */}
            <div className="inline-flex rounded-xl bg-slate-100 p-1 border border-slate-200/80">
              {['Semua', 'Banjarmasin', 'Barito Kuala'].map((area) => (
                <button
                  key={area}
                  onClick={() => setSelectedArea(area)}
                  className={`px-3 py-1 rounded-lg text-xs font-medium transition-all ${
                    selectedArea === area
                      ? 'bg-white text-slate-900 shadow-xs font-semibold'
                      : 'text-slate-600 hover:text-slate-900'
                  }`}
                >
                  {area}
                </button>
              ))}
            </div>

            {/* Status Filter Dropdown */}
            <select
              value={selectedStatus}
              onChange={(e) => setSelectedStatus(e.target.value)}
              className="text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
            >
              <option value="Semua">Semua Status ({topology.length})</option>
              <option value="Online">Online Saja ({onlineNodesCount})</option>
              <option value="Offline">Offline Saja ({offlineNodesCount})</option>
            </select>
          </div>
        </div>

        {/* Nodes Grid Cards */}
        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3.5 mt-5">
          {filteredTopology.map((node) => {
            const isOnline = node.status === 'Online';
            const isOlt = node.type === 'OLT';
            const utilizationRate = Math.round((node.usedPorts / node.totalPorts) * 100);

            return (
              <div
                key={node.id}
                className={`p-4 rounded-xl border transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md relative flex flex-col justify-between ${
                  isOnline
                    ? 'bg-white border-slate-200 hover:border-slate-300'
                    : 'bg-rose-50/40 border-rose-200 hover:border-rose-300'
                }`}
              >
                <div>
                  {/* Card Header */}
                  <div className="flex items-center justify-between gap-1 mb-2">
                    <span
                      className={`text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider ${
                        isOlt
                          ? 'bg-indigo-100 text-indigo-700 border border-indigo-200'
                          : 'bg-sky-100 text-sky-800 border border-sky-200'
                      }`}
                    >
                      {node.type}
                    </span>

                    <span
                      className={`inline-flex items-center gap-1 text-[11px] font-semibold px-2 py-0.5 rounded-full ${
                        isOnline
                          ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                          : 'bg-rose-100 text-rose-700 border border-rose-300 animate-pulse'
                      }`}
                    >
                      <span
                        className={`w-1.5 h-1.5 rounded-full ${
                          isOnline ? 'bg-emerald-500' : 'bg-rose-500'
                        }`}
                      />
                      {node.status}
                    </span>
                  </div>

                  {/* Node Name */}
                  <h4 className="font-bold text-sm text-slate-800 font-mono tracking-tight truncate" title={node.name}>
                    {node.name}
                  </h4>

                  {/* Location Area */}
                  <div className="flex items-center gap-1 text-slate-500 text-[11px] mt-1 truncate" title={node.location}>
                    <MapPin className="w-3 h-3 shrink-0 text-slate-400" />
                    <span className="truncate">{node.area}</span>
                  </div>

                  {/* Port Capacity / Active Users */}
                  <div className="mt-3 pt-2.5 border-t border-slate-100">
                    <div className="flex items-center justify-between text-[11px] text-slate-600 mb-1">
                      <span>Port Terpakai</span>
                      <span className="font-mono font-semibold">
                        {node.usedPorts}/{node.totalPorts} ({utilizationRate}%)
                      </span>
                    </div>
                    <div className="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                      <div
                        className={`h-1.5 rounded-full ${
                          !isOnline
                            ? 'bg-rose-500'
                            : utilizationRate > 85
                            ? 'bg-amber-500'
                            : 'bg-blue-600'
                        }`}
                        style={{ width: `${utilizationRate}%` }}
                      />
                    </div>
                  </div>

                  {/* Offline Warning banner if any */}
                  {!isOnline && node.issue && (
                    <div className="mt-2.5 p-2 rounded-lg bg-rose-100/90 border border-rose-200 text-[10px] text-rose-800 font-medium leading-tight">
                      <strong>Insiden:</strong> {node.issue}
                    </div>
                  )}
                </div>

                <div className="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400">
                  <span className="flex items-center gap-1 font-mono">
                    <Clock className="w-3 h-3" />
                    {node.lastCheck}
                  </span>
                  <span className="font-semibold text-slate-600">
                    {node.connectedUsers ? `${node.connectedUsers} User` : `${node.connectedOdp} ODP`}
                  </span>
                </div>
              </div>
            );
          })}
        </div>

        {filteredTopology.length === 0 && (
          <div className="py-12 text-center text-slate-400">
            <Radio className="w-10 h-10 mx-auto text-slate-300 mb-2" />
            <p className="text-sm font-medium">Tidak ada node yang cocok dengan kriteria filter.</p>
          </div>
        )}
      </div>

      {/* BOTTOM SECTION: Split into Two
          Left: Pie Chart (Recharts) Outage vs Isolir Statistic
          Right: Technician Performance Table */}
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {/* Left: Pie Chart */}
        <div className="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
          <div>
            <div className="flex items-center justify-between mb-1">
              <h3 className="font-bold text-slate-800 text-sm tracking-tight">
                Outage vs Isolir Statistic
              </h3>
              <span className="text-[11px] px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-medium">
                Semua Tiket
              </span>
            </div>
            <p className="text-xs text-slate-500">
              Perbandingan frekuensi gangguan massal (Outage) vs gangguan individu pelanggan (Isolir).
            </p>
          </div>

          <div className="h-64 my-2 flex items-center justify-center">
            {totalOutageCount + totalIsolirCount > 0 ? (
              <ResponsiveContainer width="100%" height="100%">
                <PieChart>
                  <Pie
                    data={pieData}
                    cx="50%"
                    cy="50%"
                    innerRadius={55}
                    outerRadius={85}
                    paddingAngle={4}
                    dataKey="value"
                  >
                    {pieData.map((entry, index) => (
                      <Cell key={`cell-${index}`} fill={entry.color} stroke="#ffffff" strokeWidth={2} />
                    ))}
                  </Pie>
                  <Tooltip content={<CustomTooltip />} />
                  <Legend 
                    verticalAlign="bottom" 
                    height={36} 
                    iconType="circle"
                    formatter={(value) => <span className="text-xs font-medium text-slate-700">{value}</span>}
                  />
                </PieChart>
              </ResponsiveContainer>
            ) : (
              <div className="text-center text-slate-400 text-xs">Belum ada data tiket.</div>
            )}
          </div>

          <div className="grid grid-cols-2 gap-3 pt-3 border-t border-slate-100">
            <div className="p-3 rounded-xl bg-rose-50/70 border border-rose-100">
              <div className="text-[11px] font-semibold text-rose-700">Outage (Massal)</div>
              <div className="text-xl font-bold text-rose-600 mt-0.5">
                {totalOutageCount} <span className="text-xs font-normal text-slate-500">Tiket</span>
              </div>
              <div className="text-[10px] text-slate-500 mt-0.5">
                {totalOutageCount + totalIsolirCount > 0
                  ? `${Math.round((totalOutageCount / (totalOutageCount + totalIsolirCount)) * 100)}% dari total keluhan`
                  : '0%'}
              </div>
            </div>

            <div className="p-3 rounded-xl bg-orange-50/70 border border-orange-100">
              <div className="text-[11px] font-semibold text-orange-700">Isolir (Individu)</div>
              <div className="text-xl font-bold text-orange-600 mt-0.5">
                {totalIsolirCount} <span className="text-xs font-normal text-slate-500">Tiket</span>
              </div>
              <div className="text-[10px] text-slate-500 mt-0.5">
                {totalOutageCount + totalIsolirCount > 0
                  ? `${Math.round((totalIsolirCount / (totalOutageCount + totalIsolirCount)) * 100)}% dari total keluhan`
                  : '0%'}
              </div>
            </div>
          </div>
        </div>

        {/* Right: Technician Performance Table */}
        <div className="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
          <div>
            <div className="flex items-center justify-between mb-1">
              <div className="flex items-center gap-2">
                <Award className="w-4 h-4 text-amber-500" />
                <h3 className="font-bold text-slate-800 text-sm tracking-tight">
                  Technician Performance &amp; SLA
                </h3>
              </div>
              <span className="text-[11px] font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">
                Tim Lapangan Aktif
              </span>
            </div>
            <p className="text-xs text-slate-500">
              Daftar produktivitas penyelesaian tiket per teknisi lapangan regional Kalsel.
            </p>
          </div>

          <div className="overflow-x-auto mt-4">
            <table className="w-full text-left text-xs">
              <thead>
                <tr className="border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                  <th className="pb-3 pl-2">Nama Teknisi</th>
                  <th className="pb-3">Wilayah Tugas</th>
                  <th className="pb-3 text-center">Status</th>
                  <th className="pb-3 text-center">Tiket Selesai</th>
                  <th className="pb-3 text-right pr-2">Rating</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100 text-slate-700">
                {technicians.map((tech, index) => {
                  const isAvailable = tech.status === 'Available';
                  return (
                    <tr key={tech.id} className="hover:bg-slate-50/80 transition-colors">
                      <td className="py-3 pl-2">
                        <div className="flex items-center gap-2.5">
                          <div className="w-7 h-7 rounded-full bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-[11px] border border-slate-200">
                            {index + 1}
                          </div>
                          <div>
                            <div className="font-bold text-slate-900">{tech.name}</div>
                            <div className="text-[10px] text-slate-400 font-mono">{tech.id}</div>
                          </div>
                        </div>
                      </td>

                      <td className="py-3 text-slate-600">
                        <span className="inline-flex items-center gap-1">
                          <MapPin className="w-3 h-3 text-slate-400" />
                          {tech.area}
                        </span>
                      </td>

                      <td className="py-3 text-center">
                        <span
                          className={`inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold ${
                            isAvailable
                              ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                              : 'bg-blue-50 text-blue-700 border border-blue-200'
                          }`}
                        >
                          <span
                            className={`w-1.5 h-1.5 rounded-full ${
                              isAvailable ? 'bg-emerald-500' : 'bg-blue-500 animate-pulse'
                            }`}
                          />
                          {tech.status}
                        </span>
                      </td>

                      <td className="py-3 text-center font-bold text-slate-900 font-mono">
                        <span className="px-2 py-1 rounded-lg bg-slate-100 font-semibold text-slate-800">
                          {tech.ticketsSolved} Solved
                        </span>
                      </td>

                      <td className="py-3 text-right pr-2 font-semibold text-amber-600">
                        ⭐ {tech.rating}
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>

          <div className="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
            <span>Teknisi siap diterjunkan untuk dispatch Work Order (SPK) baru.</span>
            <span className="font-semibold text-slate-700">Total: {technicians.length} Petugas</span>
          </div>
        </div>
      </div>
    </div>
  );
}
