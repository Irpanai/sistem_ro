import React, { useState, useEffect, useRef } from 'react';
import { 
  Bell, 
  LogOut, 
  User, 
  Clock, 
  Smartphone, 
  AlertCircle, 
  CheckCircle2, 
  Radio, 
  ShieldCheck,
  ChevronDown,
  Menu,
  Repeat,
  Wrench
} from 'lucide-react';
import { DEMO_ACCOUNTS } from '../data/initialData';

export default function Header({ 
  user, 
  onLogout, 
  onOpenTechnicianModal, 
  onToggleSidebar,
  onSwitchRole,
  unreadNotifications = [],
  onClearNotifications,
  activeWorkOrdersCount = 0
}) {
  const [showNotifications, setShowNotifications] = useState(false);
  const [showRoleMenu, setShowRoleMenu] = useState(false);
  const [time, setTime] = useState(new Date());
  
  const notifRef = useRef(null);
  const roleRef = useRef(null);

  useEffect(() => {
    const timer = setInterval(() => setTime(new Date()), 1000);
    return () => clearInterval(timer);
  }, []);

  // Click outside to close dropdowns
  useEffect(() => {
    function handleClickOutside(event) {
      if (notifRef.current && !notifRef.current.contains(event.target)) {
        setShowNotifications(false);
      }
      if (roleRef.current && !roleRef.current.contains(event.target)) {
        setShowRoleMenu(false);
      }
    }
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  const formattedTime = time.toLocaleTimeString('id-ID', {
    hour: '2-digit',
    minute: '2-digit',
    timeZoneName: 'short'
  });

  return (
    <header className="h-16 bg-white border-b border-slate-200/80 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
      {/* Left: Mobile Hamburger Button & Welcome */}
      <div className="flex items-center gap-3">
        {/* Hamburger Menu Toggle (Mobile only) */}
        <button
          onClick={onToggleSidebar}
          className="lg:hidden p-2 rounded-xl text-slate-700 hover:text-slate-900 hover:bg-slate-100 transition-colors focus:outline-none"
          title="Buka Menu"
        >
          <Menu className="w-5 h-5" />
        </button>

        <div>
          <div className="flex items-center gap-2">
            <h1 className="text-sm sm:text-base font-bold text-slate-900 tracking-tight truncate max-w-[150px] sm:max-w-none">
              Welcome, {user?.name?.split(' ')[0] || 'User'}
            </h1>

            {/* Role Badge */}
            <span className={`inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] sm:text-[11px] font-semibold border ${
              user?.role === 'admin'
                ? 'bg-blue-50 text-blue-700 border-blue-200'
                : user?.role === 'teknisi'
                ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                : 'bg-amber-50 text-amber-700 border-amber-200'
            }`}>
              <span className={`w-1.5 h-1.5 rounded-full ${
                user?.role === 'admin' ? 'bg-blue-600 animate-pulse' : user?.role === 'teknisi' ? 'bg-emerald-600' : 'bg-amber-600'
              }`} />
              <span className="capitalize">{user?.role || 'Admin'}</span>
            </span>
          </div>

          <div className="hidden sm:flex items-center gap-1.5 text-xs text-slate-400 mt-0.5">
            <Clock className="w-3 h-3 text-slate-400" />
            <span>{formattedTime} &bull; Regional Kalsel &amp; BTL</span>
          </div>
        </div>
      </div>

      {/* Right: Actions, Role Switcher, Notifications & Profile */}
      <div className="flex items-center gap-2 sm:gap-3">
        {/* Switch Role Quick Dropdown */}
        <div className="relative" ref={roleRef}>
          <button
            onClick={() => setShowRoleMenu(!showRoleMenu)}
            className="flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold transition-all"
            title="Ganti Role Akun Demo (Admin, Teknisi, Klien)"
          >
            <Repeat className="w-3.5 h-3.5 text-blue-600" />
            <span className="hidden md:inline">Ganti Role</span>
            <ChevronDown className="w-3 h-3 opacity-60" />
          </button>

          {showRoleMenu && (
            <div className="absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-slate-200 p-2 z-50 animate-in fade-in slide-in-from-top-2 duration-150">
              <div className="px-3 py-2 border-b border-slate-100">
                <span className="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                  Simulasikan Peran Pengguna:
                </span>
                <span className="text-xs text-slate-600">Pilih salah satu peran untuk menguji tampilan:</span>
              </div>

              <div className="p-1 space-y-1">
                {DEMO_ACCOUNTS.map((acc) => {
                  const isCurrent = user?.username === acc.username;

                  return (
                    <button
                      key={acc.username}
                      onClick={() => {
                        onSwitchRole(acc);
                        setShowRoleMenu(false);
                      }}
                      className={`w-full text-left p-2.5 rounded-xl text-xs flex items-start gap-2.5 transition-all ${
                        isCurrent
                          ? 'bg-blue-50 text-blue-900 font-bold border border-blue-200'
                          : 'hover:bg-slate-50 text-slate-700'
                      }`}
                    >
                      <div className="w-7 h-7 rounded-lg bg-slate-100 text-slate-800 flex items-center justify-center shrink-0 mt-0.5">
                        {acc.role === 'admin' ? '🛡️' : acc.role === 'teknisi' ? '🔧' : '👤'}
                      </div>
                      <div className="flex-1 min-w-0">
                        <div className="flex items-center justify-between">
                          <span className="font-bold truncate">{acc.roleLabel}</span>
                          {isCurrent && <CheckCircle2 className="w-3.5 h-3.5 text-blue-600 shrink-0" />}
                        </div>
                        <div className="text-[11px] text-slate-500 truncate">{acc.name}</div>
                        <div className="text-[10px] text-slate-400 font-mono">{acc.username}</div>
                      </div>
                    </button>
                  );
                })}
              </div>
            </div>
          )}
        </div>

        {/* Simulate Technician App Button (if role is admin) */}
        {user?.role === 'admin' && (
          <button
            onClick={onOpenTechnicianModal}
            className="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold shadow-xs transition-all"
            title="Buka simulasi mobile app teknisi"
          >
            <Smartphone className="w-3.5 h-3.5 text-sky-400" />
            <span className="hidden xl:inline">Simulate</span> App
            {activeWorkOrdersCount > 0 && (
              <span className="bg-blue-600 text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full">
                {activeWorkOrdersCount}
              </span>
            )}
          </button>
        )}

        {/* Notifications Dropdown */}
        <div className="relative" ref={notifRef}>
          <button
            onClick={() => setShowNotifications(!showNotifications)}
            className="relative p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors focus:outline-none"
            title="Pemberitahuan Sistem"
          >
            <Bell className="w-4 h-4 sm:w-5 sm:h-5" />
            {unreadNotifications.length > 0 && (
              <span className="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white animate-pulse" />
            )}
          </button>

          {showNotifications && (
            <div className="absolute right-0 mt-2 w-72 sm:w-88 bg-white rounded-2xl shadow-xl border border-slate-200 py-3 z-50 animate-in fade-in slide-in-from-top-2 duration-150">
              <div className="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                <div>
                  <h3 className="font-semibold text-xs sm:text-sm text-slate-800">Notifikasi Jaringan</h3>
                  <p className="text-[10px] text-slate-500">Live feed OLT, ODP &amp; Tiket</p>
                </div>
                {unreadNotifications.length > 0 && (
                  <button
                    onClick={onClearNotifications}
                    className="text-[10px] text-blue-600 hover:text-blue-800 font-medium"
                  >
                    Tandai dibaca
                  </button>
                )}
              </div>

              <div className="max-h-72 overflow-y-auto divide-y divide-slate-100">
                {unreadNotifications.length === 0 ? (
                  <div className="py-6 text-center text-slate-400">
                    <CheckCircle2 className="w-6 h-6 mx-auto mb-1 text-emerald-500/70" />
                    <p className="text-xs">Tidak ada notifikasi baru</p>
                  </div>
                ) : (
                  unreadNotifications.map((n) => (
                    <div key={n.id} className="p-3 hover:bg-slate-50/80 transition-colors flex items-start gap-2.5">
                      <div className="shrink-0 mt-0.5">
                        {n.type === 'outage' && (
                          <div className="w-6 h-6 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center">
                            <AlertCircle className="w-3.5 h-3.5" />
                          </div>
                        )}
                        {n.type === 'ticket' && (
                          <div className="w-6 h-6 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                            <Radio className="w-3.5 h-3.5" />
                          </div>
                        )}
                        {n.type === 'info' && (
                          <div className="w-6 h-6 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                            <ShieldCheck className="w-3.5 h-3.5" />
                          </div>
                        )}
                      </div>
                      <div className="flex-1 min-w-0">
                        <p className="text-xs font-semibold text-slate-800 leading-tight">{n.title}</p>
                        <p className="text-[10px] text-slate-500 mt-0.5 leading-relaxed">{n.message}</p>
                        <p className="text-[9px] text-slate-400 mt-1 font-mono">{n.time}</p>
                      </div>
                    </div>
                  ))
                )}
              </div>
            </div>
          )}
        </div>

        {/* Separator */}
        <div className="h-6 w-px bg-slate-200" />

        {/* User Info & Profile */}
        <div className="flex items-center gap-2">
          <div className="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-bold ring-2 ring-slate-100 shadow-xs shrink-0">
            {user?.name ? user.name.slice(0, 2).toUpperCase() : 'AD'}
          </div>
          <div className="hidden lg:block text-left max-w-[110px]">
            <div className="text-xs font-semibold text-slate-800 leading-tight truncate">
              {user?.name || 'Administrator'}
            </div>
            <div className="text-[10px] text-slate-500 truncate">
              {user?.username || 'admin_noc'}
            </div>
          </div>
        </div>

        {/* Logout Button */}
        <button
          onClick={onLogout}
          className="flex items-center gap-1 px-2.5 py-1.5 rounded-xl border border-rose-200/80 bg-rose-50/70 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition-colors"
          title="Keluar dari sesi"
        >
          <LogOut className="w-3.5 h-3.5" />
          <span className="hidden md:inline">Keluar</span>
        </button>
      </div>
    </header>
  );
}
