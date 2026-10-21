import React, { useState } from 'react';
import LoginScreen from './components/LoginScreen';
import Sidebar from './components/Sidebar';
import Header from './components/Header';
import Toast from './components/Toast';

// Pages
import DashboardPage from './components/pages/DashboardPage';
import TopologiPage from './components/pages/TopologiPage';
import LogistikPage from './components/pages/LogistikPage';
import TiketPage from './components/pages/TiketPage';
import LaporanPage from './components/pages/LaporanPage';
import ClientPortalPage from './components/pages/ClientPortalPage';
import TechnicianPortalPage from './components/pages/TechnicianPortalPage';

// Modals
import DispatchModal from './components/modals/DispatchModal';
import TechnicianSimulationModal from './components/modals/TechnicianSimulationModal';

// Initial Data
import { 
  INITIAL_TECHNICIANS, 
  INITIAL_TOPOLOGY, 
  INITIAL_MATERIALS, 
  INITIAL_TICKETS, 
  INITIAL_OUTBOUND_REPORTS,
  DEMO_ACCOUNTS
} from './data/initialData';

import { Smartphone } from 'lucide-react';

export default function App() {
  // Authentication State with default admin account
  const [user, setUser] = useState({
    username: 'admin_noc',
    name: 'Admin NOC Pusat',
    role: 'admin',
    avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=250'
  });

  // Mobile sidebar drawer state
  const [sidebarOpen, setSidebarOpen] = useState(false);

  // Navigation State
  const [activePage, setActivePage] = useState('dashboard');

  // Core Data State
  const [tickets, setTickets] = useState(INITIAL_TICKETS);
  const [topology, setTopology] = useState(INITIAL_TOPOLOGY);
  const [materials, setMaterials] = useState(INITIAL_MATERIALS);
  const [technicians, setTechnicians] = useState(INITIAL_TECHNICIANS);
  const [reports, setReports] = useState(INITIAL_OUTBOUND_REPORTS);

  // Modals & UI States
  const [dispatchTicket, setDispatchTicket] = useState(null);
  const [isTechnicianModalOpen, setIsTechnicianModalOpen] = useState(false);
  const [toasts, setToasts] = useState([]);
  const [notifications, setNotifications] = useState([
    {
      id: 'N1',
      title: 'ODP-BJM-03 Terputus',
      message: '12 pelanggan offline di Banjarmasin Timur. Indikasi fiber cut.',
      time: '07:35 WITA',
      type: 'outage'
    },
    {
      id: 'N2',
      title: 'Tiket Baru TKT-2026-004',
      message: 'Laporan kerusakan modem ONU di Komp. Taekwondo Banjarmasin.',
      time: '08:52 WITA',
      type: 'ticket'
    }
  ]);

  // Toast Helper
  const addToast = (title, message = '', type = 'success') => {
    const id = Date.now().toString();
    setToasts((prev) => [...prev, { id, title, message, type }]);
    setTimeout(() => {
      setToasts((prev) => prev.filter((t) => t.id !== id));
    }, 4500);
  };

  const removeToast = (id) => {
    setToasts((prev) => prev.filter((t) => t.id !== id));
  };

  // Switch role handler (Admin, Teknisi, Klien)
  const handleSwitchRole = (acc) => {
    setUser({
      username: acc.username,
      name: acc.name,
      role: acc.role,
      avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=250'
    });

    if (acc.role === 'klien') {
      setActivePage('klien-portal');
    } else if (acc.role === 'teknisi') {
      setActivePage('teknisi-portal');
    } else {
      setActivePage('dashboard');
    }

    addToast(
      'Peran Berhasil Dialihkan',
      `Sekarang masuk sebagai ${acc.roleLabel} (${acc.name}).`,
      'info'
    );
  };

  // 1. Generate SPK / Work Order
  const handleGenerateSpk = ({ ticketId, classificationType, technicianName, materials: handoverItems }) => {
    const spkNumber = `SPK-2026-${classificationType === 'OUTAGE' ? 'OUT' : 'ISL'}-${Math.floor(100 + Math.random() * 900)}`;
    const nowTime = new Date().toISOString().replace('T', ' ').substring(0, 16);

    // Update Ticket
    setTickets((prev) =>
      prev.map((t) => {
        if (t.id === ticketId) {
          return {
            ...t,
            status: 'In Progress',
            type: classificationType,
            workOrder: {
              spkNumber: spkNumber,
              assignedTechnician: technicianName,
              technicianPhone: technicians.find(tec => tec.name === technicianName)?.phone || '0812-XXXX',
              allocatedMaterials: handoverItems,
              dispatchedAt: nowTime,
              techNotes: '',
              resolvedAt: null
            }
          };
        }
        return t;
      })
    );

    // Decrement Materials stock
    setMaterials((prev) =>
      prev.map((mat) => {
        const item = handoverItems.find((h) => h.materialId === mat.id);
        if (item) {
          const newStock = Math.max(0, mat.stock - item.quantity);
          return { ...mat, stock: newStock };
        }
        return mat;
      })
    );

    // Add to Outbound Reports
    const targetTicket = tickets.find((t) => t.id === ticketId);
    const newReportEntries = handoverItems.map((item, idx) => ({
      id: `REP-${Date.now().toString().slice(-4)}-${idx}`,
      date: nowTime,
      ticketId: ticketId,
      spkNumber: spkNumber,
      customerName: targetTicket?.customerName || 'Pelanggan',
      technicianName: technicianName,
      materialName: item.name,
      quantity: item.quantity,
      unit: item.unit,
      approvedBy: user?.name || 'Admin NOC',
      status: 'In Field Handover'
    }));

    setReports((prev) => [...newReportEntries, ...prev]);

    // Update technician status to 'On Field'
    setTechnicians((prev) =>
      prev.map((tec) => (tec.name === technicianName ? { ...tec, status: 'On Field' } : tec))
    );

    // System Alert Toast
    addToast(
      'Work Order (SPK) Diterbitkan!',
      `${spkNumber} berhasil ditugaskan ke ${technicianName} dengan ${handoverItems.length} material.`,
      'success'
    );

    // Add to Notifications dropdown
    setNotifications((prev) => [
      {
        id: Date.now().toString(),
        title: `SPK Diterbitkan: ${spkNumber}`,
        message: `${targetTicket?.customerName} (${classificationType}) ditugaskan ke ${technicianName}.`,
        time: 'Baru saja',
        type: 'info'
      },
      ...prev
    ]);
  };

  // 2. Resolve Ticket (From Mobile Simulation or Technician Portal)
  const handleResolveTicket = (ticketId, techNotes) => {
    const nowTime = new Date().toISOString().replace('T', ' ').substring(0, 16);
    let resolvedTechName = '';

    setTickets((prev) =>
      prev.map((t) => {
        if (t.id === ticketId) {
          resolvedTechName = t.workOrder?.assignedTechnician || '';
          return {
            ...t,
            status: 'Solved',
            workOrder: {
              ...t.workOrder,
              techNotes: techNotes || 'Pekerjaan selesai di lapangan.',
              resolvedAt: nowTime
            }
          };
        }
        return t;
      })
    );

    // Increase technician's tickets solved
    if (resolvedTechName) {
      setTechnicians((prev) =>
        prev.map((tec) =>
          tec.name === resolvedTechName
            ? { ...tec, ticketsSolved: tec.ticketsSolved + 1, status: 'Available' }
            : tec
        )
      );
    }

    // Update report status
    setReports((prev) =>
      prev.map((r) => (r.ticketId === ticketId ? { ...r, status: 'Used & Completed' } : r))
    );

    addToast(
      'Tiket Berhasil Diselesaikan!',
      `Pekerjaan untuk tiket ${ticketId} telah ditandai Solved oleh teknisi.`,
      'success'
    );
  };

  // 3. Topology Actions
  const handleAddNode = (newNode) => {
    setTopology((prev) => [newNode, ...prev]);
    addToast('Node Baru Ditambahkan', `${newNode.name} (${newNode.type}) berhasil didaftarkan ke topologi.`, 'success');
  };

  const handleToggleNodeStatus = (nodeId) => {
    setTopology((prev) =>
      prev.map((node) => {
        if (node.id === nodeId) {
          const nextStatus = node.status === 'Online' ? 'Offline' : 'Online';
          addToast(
            `Status Node Diubah`,
            `${node.name} sekarang berstatus: ${nextStatus}`,
            nextStatus === 'Online' ? 'success' : 'warning'
          );
          return {
            ...node,
            status: nextStatus,
            lastCheck: 'Baru saja diperbarui'
          };
        }
        return node;
      })
    );
  };

  const handleDeleteNode = (nodeId) => {
    const node = topology.find(n => n.id === nodeId);
    setTopology((prev) => prev.filter((n) => n.id !== nodeId));
    addToast('Node Dihapus', `${node?.name || nodeId} dihapus dari master topologi.`, 'warning');
  };

  // 4. Material Actions
  const handleRestockMaterial = (materialId, addedQty) => {
    setMaterials((prev) =>
      prev.map((mat) => {
        if (mat.id === materialId) {
          const updated = mat.stock + addedQty;
          addToast('Restock Berhasil', `Stok ${mat.name} bertambah +${addedQty} ${mat.unit} (Total: ${updated}).`, 'success');
          return { ...mat, stock: updated };
        }
        return mat;
      })
    );
  };

  const handleAddMaterial = (newMaterial) => {
    setMaterials((prev) => [newMaterial, ...prev]);
    addToast('Material Baru Ditambahkan', `${newMaterial.name} tercatat di inventaris.`, 'success');
  };

  // 5. New Ticket action
  const handleAddNewTicket = (newTkt) => {
    setTickets((prev) => [newTkt, ...prev]);
    addToast('Keluhan Baru Didaftarkan', `Tiket ${newTkt.id} untuk ${newTkt.customerName} siap dianalisis NOC.`, 'success');
  };

  // If user is not authenticated, render LoginScreen
  if (!user) {
    return <LoginScreen onLogin={(userData) => {
      setUser(userData);
      if (userData.role === 'klien') setActivePage('klien-portal');
      else if (userData.role === 'teknisi') setActivePage('teknisi-portal');
      else setActivePage('dashboard');
    }} />;
  }

  const pendingCount = tickets.filter((t) => t.status === 'Pending').length;
  const activeSpkCount = tickets.filter((t) => t.status === 'In Progress').length;
  const onlineTopologyCount = topology.filter((n) => n.status === 'Online').length;

  return (
    <div className="flex min-h-screen bg-slate-50 text-slate-800 font-sans antialiased selection:bg-blue-600 selection:text-white relative">
      {/* Responsive Left Sidebar */}
      <Sidebar
        activePage={activePage}
        setActivePage={setActivePage}
        pendingTicketsCount={pendingCount}
        topologyStats={{ online: onlineTopologyCount, total: topology.length }}
        onOpenTechnicianModal={() => setIsTechnicianModalOpen(true)}
        isOpen={sidebarOpen}
        onClose={() => setSidebarOpen(false)}
        user={user}
      />

      {/* Main Layout Area */}
      <div className="flex-1 flex flex-col min-w-0 min-h-screen w-full">
        {/* Responsive Top Header */}
        <Header
          user={user}
          onLogout={() => setUser(null)}
          onOpenTechnicianModal={() => setIsTechnicianModalOpen(true)}
          onToggleSidebar={() => setSidebarOpen(!sidebarOpen)}
          onSwitchRole={handleSwitchRole}
          unreadNotifications={notifications}
          onClearNotifications={() => setNotifications([])}
          activeWorkOrdersCount={activeSpkCount}
        />

        {/* Responsive Page Content Area */}
        <main className="flex-1 p-3.5 sm:p-6 lg:p-7 max-w-7xl w-full mx-auto pb-24">
          {/* CLIENT / PELANGGAN PORTAL */}
          {activePage === 'klien-portal' && (
            <ClientPortalPage
              user={user}
              tickets={tickets}
              onAddNewTicket={handleAddNewTicket}
            />
          )}

          {/* TECHNICIAN PORTAL */}
          {activePage === 'teknisi-portal' && (
            <TechnicianPortalPage
              user={user}
              tickets={tickets}
              onResolveTicket={handleResolveTicket}
            />
          )}

          {/* ADMIN NOC DASHBOARD & MODULES */}
          {activePage === 'dashboard' && (
            <DashboardPage
              tickets={tickets}
              topology={topology}
              technicians={technicians}
              onNavigateToTiket={() => setActivePage('tiket')}
              onRefreshDiagnostic={() => {
                addToast('Pemindaian Selesai', 'Seluruh OLT & ODP di Banjarmasin dan Barito Kuala telah dicek.', 'success');
              }}
            />
          )}

          {activePage === 'topologi' && (
            <TopologiPage
              topology={topology}
              onAddNode={handleAddNode}
              onToggleNodeStatus={handleToggleNodeStatus}
              onDeleteNode={handleDeleteNode}
            />
          )}

          {activePage === 'logistik' && (
            <LogistikPage
              materials={materials}
              onRestockMaterial={handleRestockMaterial}
              onAddMaterial={handleAddMaterial}
            />
          )}

          {activePage === 'tiket' && (
            <TiketPage
              tickets={tickets}
              topology={topology}
              onOpenDispatchModal={(t) => setDispatchTicket(t)}
              onAddNewTicket={handleAddNewTicket}
            />
          )}

          {activePage === 'laporan' && (
            <LaporanPage
              reports={reports}
              technicians={technicians}
            />
          )}
        </main>
      </div>

      {/* MODAL: Analyze & Dispatch Work Order (SPK) */}
      <DispatchModal
        isOpen={Boolean(dispatchTicket)}
        onClose={() => setDispatchTicket(null)}
        ticket={dispatchTicket}
        materials={materials}
        technicians={technicians}
        onGenerateSpk={handleGenerateSpk}
      />

      {/* MODAL: Simulated Technician App Mobile View */}
      <TechnicianSimulationModal
        isOpen={isTechnicianModalOpen}
        onClose={() => setIsTechnicianModalOpen(false)}
        tickets={tickets}
        technicians={technicians}
        onResolveTicket={handleResolveTicket}
      />

      {/* FLOATING ACTION BUTTON: Simulate Technician App (Only for Admin) */}
      {user?.role === 'admin' && (
        <div className="fixed bottom-4 sm:bottom-6 right-4 sm:right-6 z-40">
          <button
            onClick={() => setIsTechnicianModalOpen(true)}
            className="group relative flex items-center gap-2 p-3 sm:px-4 sm:py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl shadow-xl shadow-slate-900/30 border border-slate-700/80 active:scale-95 transition-all"
            title="Buka simulasi antarmuka aplikasi mobile teknisi di lapangan"
          >
            <div className="relative">
              <Smartphone className="w-5 h-5 text-sky-400 group-hover:scale-110 transition-transform" />
              <span className="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-500 rounded-full animate-ping" />
            </div>

            <div className="text-left hidden md:block">
              <div className="text-xs font-bold leading-tight flex items-center gap-1.5">
                <span>Simulate Technician App</span>
              </div>
              <div className="text-[10px] text-slate-400">
                Input 5: Resolusi SPK Lapangan
              </div>
            </div>

            {activeSpkCount > 0 && (
              <span className="ml-1 bg-blue-600 text-white font-mono text-xs font-bold px-1.5 sm:px-2 py-0.5 rounded-full">
                {activeSpkCount}
              </span>
            )}
          </button>
        </div>
      )}

      {/* Toast Notification Container */}
      <Toast toasts={toasts} onDismiss={removeToast} />
    </div>
  );
}
