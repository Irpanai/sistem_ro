import React from 'react';
import { 
  BarChart3, 
  Radio, 
  Package, 
  Ticket, 
  FileText, 
  Activity, 
  Wifi, 
  ChevronRight,
  Smartphone,
  ExternalLink,
  X,
  Wrench,
  User,
  HelpCircle,
  ShieldCheck
} from 'lucide-react';

export default function Sidebar({ 
  activePage, 
  setActivePage, 
  pendingTicketsCount = 0,
  topologyStats = { online: 8, total: 10 },
  onOpenTechnicianModal,
  isOpen = false,
  onClose,
  user
}) {
  const userRole = user?.role || 'admin';

  // Role-specific navigation items
  let menuItems = [];

  if (userRole === 'admin') {
    menuItems = [
      {
        id: 'dashboard',
        label: 'Dashboard',
        subtitle: 'Overview & Analytics',
        icon: BarChart3,
        badge: null
      },
      {
        id: 'topologi',
        label: 'Data Topologi',
        subtitle: 'Master Node OLT & ODP',
        icon: Radio,
        badge: null
      },
      {
        id: 'logistik',
        label: 'Logistik Material',
        subtitle: 'Master Gudang & Stok',
        icon: Package,
        badge: null
      },
      {
        id: 'tiket',
        label: 'Keluhan & Tiket',
        subtitle: 'Ticketing & Dispatch SPK',
        icon: Ticket,
        badge: pendingTicketsCount > 0 ? `${pendingTicketsCount} Baru` : null,
        badgeColor: 'bg-rose-500 text-white'
      },
      {
        id: 'laporan',
        label: 'Laporan',
        subtitle: 'Pengeluaran Material',
        icon: FileText,
        badge: null
      }
    ];
  } else if (userRole === 'teknisi') {
    menuItems = [
      {
        id: 'teknisi-portal',
        label: 'Tugas SPK Saya',
        subtitle: 'Work Order Lapangan',
        icon: Wrench,
        badge: 'Aktif',
        badgeColor: 'bg-emerald-500 text-white'
      },
      {
        id: 'topologi',
        label: 'Data Topologi',
        subtitle: 'Cek Jalur ODP & Titik Tiang',
        icon: Radio,
        badge: null
      },
      {
        id: 'logistik',
        label: 'Cek Stok Logistik',
        subtitle: 'Material & Sparepart',
        icon: Package,
        badge: null
      }
    ];
  } else {
    // Klien / Pelanggan
    menuItems = [
      {
        id: 'klien-portal',
        label: 'Portal Pelanggan',
        subtitle: 'Status Layanan & Tiket',
        icon: User,
        badge: null
      }
    ];
  }

  const handleMenuClick = (pageId) => {
    setActivePage(pageId);
    if (onClose) onClose();
  };

  return (
    <>
      {/* Mobile Backdrop Overlay */}
      {isOpen && (
        <div 
          onClick={onClose}
          className="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-xs lg:hidden transition-opacity"
        />
      )}

      {/* Sidebar Drawer */}
      <aside 
        className={`fixed lg:sticky top-0 left-0 h-screen w-64 bg-slate-900 border-r border-slate-800 flex flex-col shrink-0 select-none z-50 transition-transform duration-300 ease-in-out ${
          isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
        }`}
      >
        {/* Brand Header */}
        <div className="p-4 sm:p-5 border-b border-slate-800/80 flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-sky-400 p-0.5 shadow-md shadow-blue-500/20 flex items-center justify-center shrink-0">
              <div className="w-full h-full bg-slate-900 rounded-[10px] flex items-center justify-center">
                <Activity className="w-5 h-5 text-sky-400" />
              </div>
            </div>
            <div className="min-w-0">
              <span className="font-bold text-white tracking-wide text-sm sm:text-base block truncate">
                BORNEO FIBER
              </span>
              <p className="text-[10px] sm:text-[11px] text-slate-400 truncate font-medium">
                SIPKJ &bull; Regional Kalsel
              </p>
            </div>
          </div>

          {/* Close button on mobile */}
          <button
            onClick={onClose}
            className="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* User Role Badge in Sidebar */}
        <div className="px-4 py-2 bg-slate-950/40 border-b border-slate-800/60 flex items-center justify-between">
          <div className="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">
            Mode Akses:
          </div>
          <span className={`text-[10px] font-bold px-2 py-0.5 rounded-md ${
            userRole === 'admin'
              ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30'
              : userRole === 'teknisi'
              ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30'
              : 'bg-amber-500/20 text-amber-300 border border-amber-500/30'
          }`}>
            {userRole === 'admin' ? '🛡️ Admin NOC' : userRole === 'teknisi' ? '🔧 Teknisi' : '👤 Klien'}
          </span>
        </div>

        {/* Navigation Links */}
        <div className="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto">
          <div className="px-3 pb-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">
            Menu Navigasi
          </div>

          {menuItems.map((item) => {
            const Icon = item.icon;
            const isActive = activePage === item.id;

            return (
              <button
                key={item.id}
                onClick={() => handleMenuClick(item.id)}
                className={`w-full flex items-center gap-3 px-3.5 py-3 rounded-xl text-left transition-all group relative ${
                  isActive
                    ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/25 font-semibold'
                    : 'text-slate-300 hover:text-white hover:bg-slate-800/70 font-medium'
                }`}
              >
                <Icon
                  className={`w-5 h-5 shrink-0 transition-transform duration-200 group-hover:scale-110 ${
                    isActive ? 'text-white' : 'text-slate-400 group-hover:text-blue-400'
                  }`}
                />
                <div className="flex-1 min-w-0">
                  <div className="text-sm truncate">{item.label}</div>
                  <div
                    className={`text-[10px] truncate ${
                      isActive ? 'text-blue-100' : 'text-slate-400'
                    }`}
                  >
                    {item.subtitle}
                  </div>
                </div>

                {item.badge && (
                  <span
                    className={`text-[10px] font-bold px-2 py-0.5 rounded-full ${
                      item.badgeColor || 'bg-slate-700 text-slate-300'
                    }`}
                  >
                    {item.badge}
                  </span>
                )}

                {isActive && (
                  <ChevronRight className="w-4 h-4 text-blue-200 shrink-0 opacity-80" />
                )}
              </button>
            );
          })}
        </div>

        {/* Bottom Network Status Widget */}
        <div className="p-3.5 border-t border-slate-800/80 bg-slate-950/40">
          <div className="p-3 rounded-xl bg-slate-800/50 border border-slate-700/50">
            <div className="flex items-center justify-between text-xs text-slate-300 font-semibold mb-2">
              <span className="flex items-center gap-1.5 text-[11px]">
                <Wifi className="w-3.5 h-3.5 text-emerald-400" />
                Status Topologi
              </span>
              <span className="text-[10px] font-mono text-emerald-400 bg-emerald-500/10 px-1.5 py-0.5 rounded border border-emerald-500/20">
                {topologyStats.online}/{topologyStats.total} ON
              </span>
            </div>

            <div className="w-full bg-slate-700/60 rounded-full h-1.5 overflow-hidden mb-2">
              <div
                className="bg-emerald-500 h-1.5 rounded-full transition-all duration-500"
                style={{ width: `${(topologyStats.online / topologyStats.total) * 100}%` }}
              />
            </div>

            <div className="text-[10px] text-slate-400 flex justify-between">
              <span>Banjarmasin &amp; BTL</span>
              <span>{Math.round((topologyStats.online / topologyStats.total) * 100)}% Online</span>
            </div>
          </div>

          {/* Quick Simulation Trigger */}
          {userRole === 'admin' && (
            <button
              onClick={() => {
                if (onClose) onClose();
                onOpenTechnicianModal();
              }}
              className="mt-3 w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl bg-slate-800 hover:bg-slate-700/80 text-sky-400 hover:text-sky-300 border border-slate-700 text-xs font-medium transition-all"
            >
              <Smartphone className="w-3.5 h-3.5 text-sky-400" />
              <span>Simulasi App Teknisi</span>
              <ExternalLink className="w-3 h-3 opacity-60 ml-auto" />
            </button>
          )}
        </div>
      </aside>
    </>
  );
}
