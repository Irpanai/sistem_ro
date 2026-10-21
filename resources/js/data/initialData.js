// Initial mock data for Sistem Informasi Pengelolaan Keluhan Jaringan (SIPKJ)
// Multi-Role Credentials & Rich Regional ISP Data (Banjarmasin & Barito Kuala)

export const DEMO_ACCOUNTS = [
  {
    role: 'admin',
    roleLabel: 'Admin NOC & Helpdesk',
    username: 'admin_noc',
    password: 'password123',
    name: 'Admin NOC Pusat',
    subtitle: 'Menerima laporan, analisis jaringan & dispatch SPK',
    badge: 'Full Access',
    badgeColor: 'bg-blue-500/20 text-blue-300 border-blue-500/30'
  },
  {
    role: 'teknisi',
    roleLabel: 'Teknisi Lapangan',
    username: 'teknisi_rizky',
    password: 'password123',
    name: 'Rizky Ramadhan',
    subtitle: 'Area Banjarmasin - Menerima & menyelesaikan SPK',
    badge: 'Field App',
    badgeColor: 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30'
  },
  {
    role: 'klien',
    roleLabel: 'Klien / Pelanggan',
    username: 'klien_sulaiman',
    password: 'password123',
    name: 'Haji Sulaiman',
    subtitle: 'ID Pelanggan: CUST-77492 (Banjarmasin Timur)',
    badge: 'Customer',
    badgeColor: 'bg-amber-500/20 text-amber-300 border-amber-500/30'
  }
];

export const INITIAL_TECHNICIANS = [
  { id: 'TECH-01', name: 'Rizky Ramadhan', area: 'Banjarmasin Kota', phone: '0812-5541-9921', status: 'Available', ticketsSolved: 28, rating: 4.9 },
  { id: 'TECH-02', name: 'Ahmad Faisal', area: 'Banjarmasin Utara', phone: '0852-8812-4410', status: 'On Field', ticketsSolved: 24, rating: 4.8 },
  { id: 'TECH-03', name: 'Muhammad Noor', area: 'Barito Kuala (Alalak)', phone: '0821-9932-1188', status: 'Available', ticketsSolved: 19, rating: 4.7 },
  { id: 'TECH-04', name: 'Budi Santoso', area: 'Barito Kuala (Marabahan)', phone: '0878-3321-7765', status: 'On Field', ticketsSolved: 22, rating: 4.8 },
  { id: 'TECH-05', name: 'Fajar Hidayat', area: 'Banjarmasin Selatan', phone: '0813-7744-8899', status: 'Available', ticketsSolved: 16, rating: 4.6 }
];

export const INITIAL_TOPOLOGY = [
  { id: 'NODE-01', name: 'OLT-BJM-PUSAT', type: 'OLT', area: 'Banjarmasin', location: 'Jl. Ahmad Yani KM 4.5, Banjarmasin', coordinates: '-3.3278, 114.6062', totalPorts: 16, usedPorts: 14, status: 'Online', connectedOdp: 12, lastCheck: '2 menit yang lalu' },
  { id: 'NODE-02', name: 'ODP-BJM-01', type: 'ODP', area: 'Banjarmasin', location: 'Jl. Lambung Mangkurat No. 12', coordinates: '-3.3186, 114.5908', totalPorts: 16, usedPorts: 14, status: 'Online', connectedUsers: 14, lastCheck: '1 menit yang lalu' },
  { id: 'NODE-03', name: 'ODP-BJM-02', type: 'ODP', area: 'Banjarmasin', location: 'Jl. Veteran Komp. Merpati', coordinates: '-3.3204, 114.6012', totalPorts: 8, usedPorts: 8, status: 'Online', connectedUsers: 8, lastCheck: '3 menit yang lalu' },
  { id: 'NODE-04', name: 'ODP-BJM-03', type: 'ODP', area: 'Banjarmasin', location: 'Jl. Gatot Subroto Gang 5', coordinates: '-3.3325, 114.6191', totalPorts: 16, usedPorts: 12, status: 'Offline', connectedUsers: 12, lastCheck: 'Baru saja', issue: 'Fiber Cut Main Feeder' },
  { id: 'NODE-05', name: 'ODP-BJM-04', type: 'ODP', area: 'Banjarmasin', location: 'Jl. Sultan Adam Komp. Taekwondo', coordinates: '-3.2988, 114.6022', totalPorts: 16, usedPorts: 15, status: 'Online', connectedUsers: 15, lastCheck: '4 menit yang lalu' },
  
  { id: 'NODE-06', name: 'OLT-BTL-MARABAHAN', type: 'OLT', area: 'Barito Kuala', location: 'Jl. Jenderal Sudirman No. 8, Marabahan', coordinates: '-2.9961, 114.7705', totalPorts: 8, usedPorts: 6, status: 'Online', connectedOdp: 8, lastCheck: '2 menit yang lalu' },
  { id: 'NODE-07', name: 'ODP-BTL-01', type: 'ODP', area: 'Barito Kuala', location: 'Jl. Trans Kalimantan, Alalak Berangas', coordinates: '-3.2671, 114.5772', totalPorts: 16, usedPorts: 11, status: 'Online', connectedUsers: 11, lastCheck: '5 menit yang lalu' },
  { id: 'NODE-08', name: 'ODP-BTL-02', type: 'ODP', area: 'Barito Kuala', location: 'Handil Bakti Komp. Semangat Dalam', coordinates: '-3.2755, 114.6133', totalPorts: 16, usedPorts: 16, status: 'Online', connectedUsers: 16, lastCheck: '1 menit yang lalu' },
  { id: 'NODE-09', name: 'ODP-BTL-03', type: 'ODP', area: 'Barito Kuala', location: 'Jl. Raya Marabahan Simpang 4', coordinates: '-3.0112, 114.7554', totalPorts: 8, usedPorts: 7, status: 'Offline', connectedUsers: 7, lastCheck: 'Baru saja', issue: 'Power Loss / Adaptor Rusak' },
  { id: 'NODE-10', name: 'ODP-BTL-04', type: 'ODP', area: 'Barito Kuala', location: 'Jl. Trans Kalimantan KM 12 Anjir Muara', coordinates: '-3.1954, 114.5122', totalPorts: 8, usedPorts: 5, status: 'Online', connectedUsers: 5, lastCheck: '6 menit yang lalu' }
];

export const INITIAL_MATERIALS = [
  { id: 'MAT-001', code: 'DC-1C-1000', name: 'Kabel Dropcore 1 Core (1000m)', category: 'Kabel & Fiber', stock: 14, minStock: 5, unit: 'Roll', price: 'Rp 650.000', location: 'Gudang Banjarmasin - Rak A1' },
  { id: 'MAT-002', code: 'ONU-ZTE-F609', name: 'Modem ONU GPON ZTE F609', category: 'CPE Hardware', stock: 42, minStock: 15, unit: 'Unit', price: 'Rp 275.000', location: 'Gudang Banjarmasin - Rak B2' },
  { id: 'MAT-003', code: 'ONU-HW-HG8245', name: 'Modem ONU Huawei HG8245H5', category: 'CPE Hardware', stock: 28, minStock: 10, unit: 'Unit', price: 'Rp 310.000', location: 'Gudang Banjarmasin - Rak B3' },
  { id: 'MAT-004', code: 'FC-SC-UPC', name: 'Fast Connector SC/UPC Biru', category: 'Accessories', stock: 280, minStock: 50, unit: 'Pcs', price: 'Rp 3.500', location: 'Gudang Banjarmasin - Rak C1' },
  { id: 'MAT-005', code: 'PC-SC-APC', name: 'Patch Cord SC/UPC - SC/APC 3M', category: 'Kabel & Fiber', stock: 95, minStock: 25, unit: 'Pcs', price: 'Rp 18.000', location: 'Gudang Banjarmasin - Rak C2' },
  { id: 'MAT-006', code: 'SP-1X8-PLC', name: 'Splitter PLC 1:8 Mini Tube', category: 'ODP Material', stock: 18, minStock: 8, unit: 'Pcs', price: 'Rp 45.000', location: 'Gudang Banjarmasin - Rak D1' },
  { id: 'MAT-007', code: 'VFL-30MW', name: 'Visual Fault Locator 30mW (Senter Laser)', category: 'Tools', stock: 6, minStock: 2, unit: 'Unit', price: 'Rp 140.000', location: 'Gudang Tools - Lemari 1' },
  { id: 'MAT-008', code: 'PROT-SLV-60', name: 'Protection Sleeve 60mm', category: 'Accessories', stock: 450, minStock: 100, unit: 'Pcs', price: 'Rp 400', location: 'Gudang Banjarmasin - Rak C3' },
  { id: 'MAT-009', code: 'CLP-SUSP-S', name: 'Clamp Suspension Kabel Dropcore', category: 'Accessories', stock: 160, minStock: 40, unit: 'Pcs', price: 'Rp 4.000', location: 'Gudang Banjarmasin - Rak D3' }
];

export const INITIAL_TICKETS = [
  {
    id: 'TKT-2026-001',
    customerUsername: 'klien_sulaiman',
    customerName: 'Haji Sulaiman',
    customerPhone: '0813-4421-9981',
    address: 'Jl. Gatot Subroto Gang 5 No. 18, Banjarmasin Timur',
    area: 'Banjarmasin',
    odpCode: 'ODP-BJM-03',
    issue: 'LOS Merah berkedip sejak pukul 07:15 WITA, koneksi putus total',
    status: 'Pending', // Pending, In Progress, Solved
    type: 'OUTAGE', // OUTAGE, ISOLIR
    priority: 'High',
    reportedAt: '2026-10-21 07:30',
    analysisDetails: {
      odpStatus: 'Offline',
      affectedUsers: 12,
      recommendation: 'Terdeteksi 12 pelanggan pada ODP-BJM-03 putus bersamaan. Indikasi Fiber Cut Main Feeder atau kabel tiang tertabrak truk.',
      suspectedCause: 'Fiber Cut Main Feeder (Outage Massal)'
    },
    workOrder: null
  },
  {
    id: 'TKT-2026-002',
    customerUsername: 'klien_rahmah',
    customerName: 'Dra. Hj. Siti Rahmah',
    customerPhone: '0852-9901-2234',
    address: 'Handil Bakti Komp. Semangat Dalam Blok C No. 4, Barito Kuala',
    area: 'Barito Kuala',
    odpCode: 'ODP-BTL-02',
    issue: 'Internet lambat dan lampu LOS sesekali merah berkedip saat hujan',
    status: 'In Progress',
    type: 'ISOLIR',
    priority: 'Medium',
    reportedAt: '2026-10-21 08:15',
    analysisDetails: {
      odpStatus: 'Online',
      affectedUsers: 1,
      recommendation: 'ODP-BTL-02 normal (15 pelanggan lain aman). Redaman pelanggan tinggi (-31.4 dBm). Kemungkinan tekukan dropcore atau sambungan connector kotor.',
      suspectedCause: 'High Optical Attenuation (Isolir Individu)'
    },
    workOrder: {
      spkNumber: 'SPK-2026-BTL-019',
      assignedTechnician: 'Muhammad Noor',
      technicianPhone: '0821-9932-1188',
      allocatedMaterials: [
        { materialId: 'MAT-004', name: 'Fast Connector SC/UPC Biru', quantity: 2, unit: 'Pcs' },
        { materialId: 'MAT-001', name: 'Kabel Dropcore 1 Core (1000m)', quantity: 1, unit: 'Roll' }
      ],
      dispatchedAt: '2026-10-21 08:45',
      techNotes: '',
      resolvedAt: null
    }
  },
  {
    id: 'TKT-2026-003',
    customerUsername: 'klien_hendra',
    customerName: 'CV. Borneo Mandiri (Pak Hendra)',
    customerPhone: '0812-7788-3344',
    address: 'Jl. Raya Marabahan Simpang 4 No. 45, Barito Kuala',
    area: 'Barito Kuala',
    odpCode: 'ODP-BTL-03',
    issue: 'Kantor mati koneksi internet sejak subuh, modem mati total',
    status: 'Pending',
    type: 'OUTAGE',
    priority: 'Critical',
    reportedAt: '2026-10-21 08:30',
    analysisDetails: {
      odpStatus: 'Offline',
      affectedUsers: 7,
      recommendation: 'ODP-BTL-03 terputus dari OLT Marabahan. 7 pelanggan corporate dan residensial offline.',
      suspectedCause: 'Power Supply ODP Failure / OLT SFP Issue'
    },
    workOrder: null
  },
  {
    id: 'TKT-2026-004',
    customerUsername: 'klien_baihaki',
    customerName: 'Ahmad Baihaki, S.Kom',
    customerPhone: '0878-1122-9900',
    address: 'Jl. Sultan Adam Komp. Taekwondo Jalur 3, Banjarmasin',
    area: 'Banjarmasin',
    odpCode: 'ODP-BJM-04',
    issue: 'Modem tersiram air kopi, indikator power padam tidak bisa hidup',
    status: 'In Progress',
    type: 'ISOLIR',
    priority: 'Medium',
    reportedAt: '2026-10-21 08:50',
    analysisDetails: {
      odpStatus: 'Online',
      affectedUsers: 1,
      recommendation: 'ODP-BJM-04 normal online. Kerusakan fisik lokal perangkat ONU pelanggan.',
      suspectedCause: 'Perangkat Hardware ONU Rusak (Isolir)'
    },
    workOrder: {
      spkNumber: 'SPK-2026-BJM-022',
      assignedTechnician: 'Rizky Ramadhan',
      technicianPhone: '0812-5541-9921',
      allocatedMaterials: [
        { materialId: 'MAT-002', name: 'Modem ONU GPON ZTE F609', quantity: 1, unit: 'Unit' },
        { materialId: 'MAT-005', name: 'Patch Cord SC/UPC - SC/APC 3M', quantity: 1, unit: 'Pcs' }
      ],
      dispatchedAt: '2026-10-21 09:10',
      techNotes: '',
      resolvedAt: null
    }
  },
  {
    id: 'TKT-2026-005',
    customerUsername: 'klien_medika',
    customerName: 'Klinik Medika Sehat (dr. Indah)',
    customerPhone: '0811-9922-3311',
    address: 'Jl. Lambung Mangkurat No. 88, Banjarmasin Tengah',
    area: 'Banjarmasin',
    odpCode: 'ODP-BJM-01',
    issue: 'Kabel dropcore putus tersangkut mobil box pengangkut barang',
    status: 'Solved',
    type: 'ISOLIR',
    priority: 'High',
    reportedAt: '2026-10-20 14:10',
    analysisDetails: {
      odpStatus: 'Online',
      affectedUsers: 1,
      recommendation: 'ODP normal. Penarikan ulang dropcore 65 meter.',
      suspectedCause: 'Physical Cable Cut by Vehicle'
    },
    workOrder: {
      spkNumber: 'SPK-2026-BJM-014',
      assignedTechnician: 'Rizky Ramadhan',
      technicianPhone: '0812-5541-9921',
      allocatedMaterials: [
        { materialId: 'MAT-001', name: 'Kabel Dropcore 1 Core (1000m)', quantity: 1, unit: 'Roll' },
        { materialId: 'MAT-004', name: 'Fast Connector SC/UPC Biru', quantity: 2, unit: 'Pcs' },
        { materialId: 'MAT-009', name: 'Clamp Suspension Kabel Dropcore', quantity: 3, unit: 'Pcs' }
      ],
      dispatchedAt: '2026-10-20 14:30',
      techNotes: 'Telah dilakukan penarikan ulang kabel dropcore sepanjang 65 meter dan pemasangan suspension baru. Redaman akhir -18.2 dBm stabil.',
      resolvedAt: '2026-10-20 16:15'
    }
  },
  {
    id: 'TKT-2026-006',
    customerUsername: 'klien_marlina',
    customerName: 'Toko Sumber Rezeki (Ibu Marlina)',
    customerPhone: '0822-4455-6677',
    address: 'Jl. Trans Kalimantan, Alalak Berangas No. 12, Barito Kuala',
    area: 'Barito Kuala',
    odpCode: 'ODP-BTL-01',
    issue: 'Koneksi putus setelah pemangkasan ranting pohon PLN',
    status: 'Solved',
    type: 'OUTAGE',
    priority: 'High',
    reportedAt: '2026-10-20 10:00',
    analysisDetails: {
      odpStatus: 'Online',
      affectedUsers: 4,
      recommendation: 'Kabel distribusi 24 core tertimpa dahan pohon, 4 pelanggan terdampak.',
      suspectedCause: 'Distribution Cable Cut by Tree Trimming'
    },
    workOrder: {
      spkNumber: 'SPK-2026-BTL-015',
      assignedTechnician: 'Budi Santoso',
      technicianPhone: '0878-3321-7765',
      allocatedMaterials: [
        { materialId: 'MAT-008', name: 'Protection Sleeve 60mm', quantity: 6, unit: 'Pcs' },
        { materialId: 'MAT-005', name: 'Patch Cord SC/UPC - SC/APC 3M', quantity: 2, unit: 'Pcs' }
      ],
      dispatchedAt: '2026-10-20 10:20',
      techNotes: 'Splicing ulang 4 core yang putus di closure tiang simpang Alalak. Semua pelanggan sudah online kembali.',
      resolvedAt: '2026-10-20 12:45'
    }
  }
];

export const INITIAL_OUTBOUND_REPORTS = [
  {
    id: 'REP-001',
    date: '2026-10-20 14:30',
    ticketId: 'TKT-2026-005',
    spkNumber: 'SPK-2026-BJM-014',
    customerName: 'Klinik Medika Sehat',
    technicianName: 'Rizky Ramadhan',
    materialName: 'Kabel Dropcore 1 Core (1000m)',
    quantity: 1,
    unit: 'Roll',
    approvedBy: 'Admin NOC',
    status: 'Used & Completed'
  },
  {
    id: 'REP-002',
    date: '2026-10-20 14:30',
    ticketId: 'TKT-2026-005',
    spkNumber: 'SPK-2026-BJM-014',
    customerName: 'Klinik Medika Sehat',
    technicianName: 'Rizky Ramadhan',
    materialName: 'Fast Connector SC/UPC Biru',
    quantity: 2,
    unit: 'Pcs',
    approvedBy: 'Admin NOC',
    status: 'Used & Completed'
  },
  {
    id: 'REP-003',
    date: '2026-10-20 14:30',
    ticketId: 'TKT-2026-005',
    spkNumber: 'SPK-2026-BJM-014',
    customerName: 'Klinik Medika Sehat',
    technicianName: 'Rizky Ramadhan',
    materialName: 'Clamp Suspension Kabel Dropcore',
    quantity: 3,
    unit: 'Pcs',
    approvedBy: 'Admin NOC',
    status: 'Used & Completed'
  },
  {
    id: 'REP-004',
    date: '2026-10-20 10:20',
    ticketId: 'TKT-2026-006',
    spkNumber: 'SPK-2026-BTL-015',
    customerName: 'Toko Sumber Rezeki',
    technicianName: 'Budi Santoso',
    materialName: 'Protection Sleeve 60mm',
    quantity: 6,
    unit: 'Pcs',
    approvedBy: 'Admin NOC',
    status: 'Used & Completed'
  },
  {
    id: 'REP-005',
    date: '2026-10-21 08:45',
    ticketId: 'TKT-2026-002',
    spkNumber: 'SPK-2026-BTL-019',
    customerName: 'Dra. Hj. Siti Rahmah',
    technicianName: 'Muhammad Noor',
    materialName: 'Fast Connector SC/UPC Biru',
    quantity: 2,
    unit: 'Pcs',
    approvedBy: 'Admin NOC',
    status: 'In Field Handover'
  },
  {
    id: 'REP-006',
    date: '2026-10-21 08:45',
    ticketId: 'TKT-2026-002',
    spkNumber: 'SPK-2026-BTL-019',
    customerName: 'Dra. Hj. Siti Rahmah',
    technicianName: 'Muhammad Noor',
    materialName: 'Kabel Dropcore 1 Core (1000m)',
    quantity: 1,
    unit: 'Roll',
    approvedBy: 'Admin NOC',
    status: 'In Field Handover'
  },
  {
    id: 'REP-007',
    date: '2026-10-21 09:10',
    ticketId: 'TKT-2026-004',
    spkNumber: 'SPK-2026-BJM-022',
    customerName: 'Ahmad Baihaki, S.Kom',
    technicianName: 'Rizky Ramadhan',
    materialName: 'Modem ONU GPON ZTE F609',
    quantity: 1,
    unit: 'Unit',
    approvedBy: 'Admin NOC',
    status: 'In Field Handover'
  }
];
