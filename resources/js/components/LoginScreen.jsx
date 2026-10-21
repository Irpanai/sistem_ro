import React, { useState } from 'react';
import { Activity, ShieldCheck, Lock, User, Eye, EyeOff, ArrowRight, Check, Wrench, Users } from 'lucide-react';
import { DEMO_ACCOUNTS } from '../data/initialData';

export default function LoginScreen({ onLogin }) {
  const [username, setUsername] = useState('admin_noc');
  const [password, setPassword] = useState('password123');
  const [showPassword, setShowPassword] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [rememberMe, setRememberMe] = useState(true);
  const [error, setError] = useState('');

  const handleSelectDemoAccount = (acc) => {
    setUsername(acc.username);
    setPassword(acc.password);
    setError('');
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (!username.trim() || !password.trim()) {
      setError('Harap masukkan username dan password.');
      return;
    }
    setError('');
    setIsLoading(true);

    setTimeout(() => {
      setIsLoading(false);
      // Determine role based on selected or typed account
      const found = DEMO_ACCOUNTS.find(a => a.username.toLowerCase() === username.trim().toLowerCase());
      
      if (found) {
        onLogin({
          username: found.username,
          name: found.name,
          role: found.role,
          roleLabel: found.roleLabel,
          avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=250'
        });
      } else {
        // Fallback default admin
        onLogin({
          username: username,
          name: username.includes('tek') ? 'Rizky Ramadhan (Teknisi)' : username.includes('kli') ? 'Haji Sulaiman (Pelanggan)' : 'Admin NOC Pusat',
          role: username.includes('tek') ? 'teknisi' : username.includes('kli') ? 'klien' : 'admin',
          avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=250'
        });
      }
    }, 500);
  };

  return (
    <div className="min-h-screen bg-slate-900 flex flex-col justify-center items-center p-3 sm:p-6 relative overflow-x-hidden">
      {/* Background glowing effects */}
      <div className="absolute top-[-10%] left-[-10%] w-[400px] sm:w-[500px] h-[400px] sm:h-[500px] bg-blue-600/20 rounded-full blur-3xl pointer-events-none" />
      <div className="absolute bottom-[-10%] right-[-10%] w-[400px] sm:w-[550px] h-[400px] sm:h-[550px] bg-indigo-600/20 rounded-full blur-3xl pointer-events-none" />

      {/* Main Container */}
      <div className="relative w-full max-w-lg z-10 my-4">
        {/* Top Logo & Header */}
        <div className="text-center mb-6">
          <div className="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-tr from-blue-700 via-indigo-600 to-sky-400 p-0.5 shadow-xl shadow-blue-500/25 mb-3">
            <div className="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center">
              <Activity className="w-7 h-7 sm:w-8 sm:h-8 text-sky-400 animate-pulse" />
            </div>
          </div>
          <h1 className="text-xl sm:text-2xl font-bold tracking-tight text-white flex items-center justify-center gap-2">
            <span>SIPKJ BORNEO</span>
            <span className="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-400 border border-blue-500/30">
              v2.4
            </span>
          </h1>
          <p className="text-xs sm:text-sm font-medium text-slate-400 mt-1">
            Sistem Informasi Pengelolaan Keluhan Jaringan (ISP)
          </p>
        </div>

        {/* Login Card */}
        <div className="bg-slate-800/95 backdrop-blur-xl border border-slate-700/80 rounded-2xl shadow-2xl p-5 sm:p-7 text-slate-100">
          <div className="mb-5">
            <h2 className="text-base sm:text-lg font-bold text-white">
              Pilih Peran &amp; Masuk ke Sistem
            </h2>
            <p className="text-xs text-slate-400 mt-0.5 leading-relaxed">
              Sistem ini mendukung 3 peran: <strong>Klien</strong> (lapor keluhan), <strong>Admin</strong> (analisis &amp; dispatch SPK), dan <strong>Teknisi</strong> (perbaikan lapangan).
            </p>
          </div>

          {/* Quick 1-Click Role Switcher Buttons */}
          <div className="mb-5">
            <span className="text-[11px] font-bold text-slate-300 uppercase tracking-wider block mb-2">
              Kredensial Siap Pakai (1-Click Login):
            </span>
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-2">
              {DEMO_ACCOUNTS.map((acc) => {
                const isSelected = username === acc.username;

                return (
                  <button
                    key={acc.username}
                    type="button"
                    onClick={() => handleSelectDemoAccount(acc)}
                    className={`p-3 rounded-xl border text-left transition-all relative ${
                      isSelected
                        ? 'bg-blue-600/25 border-blue-500 text-white shadow-md ring-1 ring-blue-500/40'
                        : 'bg-slate-900/60 border-slate-700/70 hover:bg-slate-700/50 text-slate-300'
                    }`}
                  >
                    <div className="flex items-center justify-between mb-1">
                      <span className="text-xs font-bold truncate">
                        {acc.role === 'admin' ? '🛡️ Admin NOC' : acc.role === 'teknisi' ? '🔧 Teknisi' : '👤 Klien'}
                      </span>
                      {isSelected && <Check className="w-3.5 h-3.5 text-sky-400 shrink-0" />}
                    </div>
                    <div className="text-[10px] text-slate-400 font-mono truncate">
                      {acc.username}
                    </div>
                    <div className="text-[9px] text-slate-400/80 mt-1 line-clamp-1">
                      {acc.name}
                    </div>
                  </button>
                );
              })}
            </div>
          </div>

          {error && (
            <div className="mb-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs flex items-center gap-2">
              <span className="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0" />
              <span>{error}</span>
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-3.5">
            <div>
              <label className="block text-xs font-medium text-slate-300 mb-1">
                Username / Akun Pengguna
              </label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                  <User className="w-4 h-4" />
                </div>
                <input
                  type="text"
                  value={username}
                  onChange={(e) => setUsername(e.target.value)}
                  placeholder="admin_noc / teknisi_rizky / klien_sulaiman"
                  className="w-full pl-10 pr-4 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-xs sm:text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all font-mono"
                />
              </div>
            </div>

            <div>
              <div className="flex items-center justify-between mb-1">
                <label className="block text-xs font-medium text-slate-300">
                  Password (Default: password123)
                </label>
              </div>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                  <Lock className="w-4 h-4" />
                </div>
                <input
                  type={showPassword ? 'text' : 'password'}
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  placeholder="password123"
                  className="w-full pl-10 pr-10 py-2.5 bg-slate-900/80 border border-slate-700 rounded-xl text-xs sm:text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all"
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-200 transition-colors"
                >
                  {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                </button>
              </div>
            </div>

            <div className="flex items-center justify-between text-[11px] sm:text-xs text-slate-400 pt-1">
              <label className="flex items-center gap-2 cursor-pointer select-none">
                <input
                  type="checkbox"
                  checked={rememberMe}
                  onChange={(e) => setRememberMe(e.target.checked)}
                  className="w-3.5 h-3.5 rounded border-slate-700 bg-slate-900 text-blue-600 focus:ring-blue-500/50"
                />
                <span>Ingat sesi masuk</span>
              </label>
              <span className="text-slate-500 flex items-center gap-1">
                <ShieldCheck className="w-3.5 h-3.5 text-emerald-400" />
                MySQL Database Ready
              </span>
            </div>

            <button
              type="submit"
              disabled={isLoading}
              className="w-full mt-2 py-2.5 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-blue-600/30 active:scale-[0.99] transition-all flex items-center justify-center gap-2 disabled:opacity-60"
            >
              {isLoading ? (
                <>
                  <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                  <span>Memverifikasi Akses...</span>
                </>
              ) : (
                <>
                  <span>Masuk ke Dashboard ({username})</span>
                  <ArrowRight className="w-4 h-4" />
                </>
              )}
            </button>
          </form>
        </div>

        {/* Credentials Summary Table helper */}
        <div className="mt-4 p-3.5 rounded-2xl bg-slate-800/60 border border-slate-700/60 text-xs text-slate-300">
          <div className="font-bold text-slate-200 mb-2 flex items-center gap-1.5">
            <Users className="w-4 h-4 text-sky-400" />
            <span>Tabel Detail Kredensial Role:</span>
          </div>
          <div className="space-y-1.5 text-[11px]">
            <div className="flex justify-between items-center py-1 border-b border-slate-700/50">
              <span><strong>1. Klien:</strong> klien_sulaiman</span>
              <span className="font-mono text-sky-300">password123</span>
            </div>
            <div className="flex justify-between items-center py-1 border-b border-slate-700/50">
              <span><strong>2. Admin:</strong> admin_noc</span>
              <span className="font-mono text-sky-300">password123</span>
            </div>
            <div className="flex justify-between items-center py-1">
              <span><strong>3. Teknisi:</strong> teknisi_rizky</span>
              <span className="font-mono text-sky-300">password123</span>
            </div>
          </div>
        </div>

        {/* Footer */}
        <p className="text-center text-[11px] text-slate-500 mt-4">
          &copy; 2026 Borneo Fiber Net &bull; Sistem Pengelolaan Keluhan Jaringan
        </p>
      </div>
    </div>
  );
}
