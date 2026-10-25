<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialOutbound;
use App\Models\Ticket;
use App\Models\TicketLog;
use App\Models\TopologyNode;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderTechnician;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users (Roles: Admin, Teknisi, Klien)
        $admin = User::updateOrCreate(
            ['username' => 'admin_noc'],
            [
                'name' => 'Admin Kantor Banjarmasin',
                'email' => 'admin.bjm@borneofiber.net',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '0812-5541-0001',
                'area' => 'Kantor Operasional Banjarmasin',
                'address' => 'Jl. Ahmad Yani KM 4.5, Banjarmasin',
                'rating' => 5.0,
                'technician_status' => 'Available',
            ]
        );

        $tech1 = User::updateOrCreate(
            ['username' => 'teknisi_rizky'],
            [
                'name' => 'Rizky Ramadhan',
                'email' => 'rizky.tech@borneofiber.net',
                'password' => Hash::make('password123'),
                'role' => 'teknisi',
                'phone' => '0812-5541-9921',
                'area' => 'Banjarmasin Kota',
                'address' => 'Banjarmasin Timur',
                'rating' => 4.9,
                'technician_status' => 'On Field',
            ]
        );

        $tech2 = User::updateOrCreate(
            ['username' => 'teknisi_noor'],
            [
                'name' => 'Muhammad Noor',
                'email' => 'noor.tech@borneofiber.net',
                'password' => Hash::make('password123'),
                'role' => 'teknisi',
                'phone' => '0821-9932-1188',
                'area' => 'Barito Kuala (Alalak)',
                'address' => 'Handil Bakti, Barito Kuala',
                'rating' => 4.8,
                'technician_status' => 'Available',
            ]
        );

        $tech3 = User::updateOrCreate(
            ['username' => 'teknisi_faisal'],
            [
                'name' => 'Ahmad Faisal',
                'email' => 'faisal.tech@borneofiber.net',
                'password' => Hash::make('password123'),
                'role' => 'teknisi',
                'phone' => '0852-8812-4410',
                'area' => 'Banjarmasin Utara',
                'address' => 'Kayutangi, Banjarmasin',
                'rating' => 4.8,
                'technician_status' => 'Available',
            ]
        );

        $tech4 = User::updateOrCreate(
            ['username' => 'teknisi_budi'],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.tech@borneofiber.net',
                'password' => Hash::make('password123'),
                'role' => 'teknisi',
                'phone' => '0878-3321-7765',
                'area' => 'Barito Kuala (Marabahan)',
                'address' => 'Marabahan Kota',
                'rating' => 4.7,
                'technician_status' => 'Available',
            ]
        );

        $client1 = User::updateOrCreate(
            ['username' => 'klien_sulaiman'],
            [
                'name' => 'Haji Sulaiman',
                'email' => 'sulaiman@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'klien',
                'phone' => '0813-4421-9981',
                'area' => 'Banjarmasin',
                'address' => 'Jl. Gatot Subroto Gang 5 No. 18, Banjarmasin Timur',
                'rating' => 5.0,
                'technician_status' => 'Available',
            ]
        );

        $client2 = User::updateOrCreate(
            ['username' => 'klien_rahmah'],
            [
                'name' => 'Dra. Hj. Siti Rahmah',
                'email' => 'siti.rahmah@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'klien',
                'phone' => '0852-9901-2234',
                'area' => 'Barito Kuala',
                'address' => 'Handil Bakti Komp. Semangat Dalam Blok C No. 4, Barito Kuala',
                'rating' => 5.0,
                'technician_status' => 'Available',
            ]
        );

        // 2. Seed Topology Nodes per Wilayah Kecamatan di Kota Banjarmasin
        $nodes = [
            ['node_id' => 'NODE-01', 'name' => 'OLT-BJM-KANTOR', 'type' => 'OLT', 'area' => 'Banjarmasin Tengah', 'location' => 'Kantor Operasional Banjarmasin - Jl. Ahmad Yani KM 4.5', 'coordinates' => '-3.3278, 114.6062', 'total_ports' => 16, 'used_ports' => 14, 'status' => 'Online', 'connected_odp' => 10, 'connected_users' => 140, 'issue' => null],
            ['node_id' => 'NODE-02', 'name' => 'ODP-BJM-01', 'type' => 'ODP', 'area' => 'Banjarmasin Tengah', 'location' => 'Jl. Lambung Mangkurat No. 12', 'coordinates' => '-3.3186, 114.5908', 'total_ports' => 16, 'used_ports' => 14, 'status' => 'Online', 'connected_odp' => null, 'connected_users' => 14, 'issue' => null],
            ['node_id' => 'NODE-03', 'name' => 'ODP-BJM-02', 'type' => 'ODP', 'area' => 'Banjarmasin Timur', 'location' => 'Jl. Veteran Komp. Merpati', 'coordinates' => '-3.3204, 114.6012', 'total_ports' => 8, 'used_ports' => 8, 'status' => 'Online', 'connected_odp' => null, 'connected_users' => 8, 'issue' => null],
            ['node_id' => 'NODE-04', 'name' => 'ODP-BJM-03', 'type' => 'ODP', 'area' => 'Banjarmasin Timur', 'location' => 'Jl. Gatot Subroto Gang 5', 'coordinates' => '-3.3325, 114.6191', 'total_ports' => 16, 'used_ports' => 12, 'status' => 'Offline', 'connected_odp' => null, 'connected_users' => 12, 'issue' => 'Fiber Cut Main Feeder'],
            ['node_id' => 'NODE-05', 'name' => 'ODP-BJM-04', 'type' => 'ODP', 'area' => 'Banjarmasin Utara', 'location' => 'Jl. Sultan Adam Komp. Taekwondo', 'coordinates' => '-3.2988, 114.6022', 'total_ports' => 16, 'used_ports' => 15, 'status' => 'Online', 'connected_odp' => null, 'connected_users' => 15, 'issue' => null],
            ['node_id' => 'NODE-06', 'name' => 'ODP-BJM-05', 'type' => 'ODP', 'area' => 'Banjarmasin Barat', 'location' => 'Jl. Soetoyo S. Teluk Dalam No. 88', 'coordinates' => '-3.3211, 114.5772', 'total_ports' => 16, 'used_ports' => 11, 'status' => 'Online', 'connected_odp' => null, 'connected_users' => 11, 'issue' => null],
            ['node_id' => 'NODE-07', 'name' => 'ODP-BJM-06', 'type' => 'ODP', 'area' => 'Banjarmasin Selatan', 'location' => 'Jl. Pangeran Antasari No. 45', 'coordinates' => '-3.3382, 114.5985', 'total_ports' => 16, 'used_ports' => 13, 'status' => 'Online', 'connected_odp' => null, 'connected_users' => 13, 'issue' => null],
            ['node_id' => 'NODE-08', 'name' => 'ODP-BJM-07', 'type' => 'ODP', 'area' => 'Banjarmasin Utara', 'location' => 'Jl. Brigjend H. Hasan Basry Kayutangi', 'coordinates' => '-3.2925, 114.5886', 'total_ports' => 16, 'used_ports' => 14, 'status' => 'Online', 'connected_odp' => null, 'connected_users' => 14, 'issue' => null],
            ['node_id' => 'NODE-09', 'name' => 'ODP-BJM-08', 'type' => 'ODP', 'area' => 'Banjarmasin Barat', 'location' => 'Jl. Kuin Selatan Simpang Belitung', 'coordinates' => '-3.3082, 114.5715', 'total_ports' => 8, 'used_ports' => 7, 'status' => 'Offline', 'connected_odp' => null, 'connected_users' => 7, 'issue' => 'Power Loss Adaptor Rusak'],
            ['node_id' => 'NODE-10', 'name' => 'ODP-BJM-09', 'type' => 'ODP', 'area' => 'Banjarmasin Selatan', 'location' => 'Jl. Kelayan B Barat Komp. Ar-Raudhah', 'coordinates' => '-3.3451, 114.6022', 'total_ports' => 8, 'used_ports' => 6, 'status' => 'Online', 'connected_odp' => null, 'connected_users' => 6, 'issue' => null],
            ['node_id' => 'NODE-11', 'name' => 'ODP-BJM-10', 'type' => 'ODP', 'area' => 'Banjarmasin Tengah', 'location' => 'Jl. Pangeran Samudra Kertak Baru', 'coordinates' => '-3.3245, 114.5945', 'total_ports' => 16, 'used_ports' => 10, 'status' => 'Online', 'connected_odp' => null, 'connected_users' => 10, 'issue' => null],
        ];

        foreach ($nodes as $nodeData) {
            TopologyNode::updateOrCreate(['node_id' => $nodeData['node_id']], $nodeData);
        }

        // 3. Seed Materials
        $materials = [
            ['code' => 'DC-1C-1000', 'name' => 'Kabel Dropcore 1 Core (1000m)', 'category' => 'Kabel & Fiber', 'stock' => 14, 'min_stock' => 5, 'unit' => 'Roll', 'price' => 'Rp 650.000', 'location' => 'Gudang Banjarmasin - Rak A1'],
            ['code' => 'ONU-ZTE-F609', 'name' => 'Modem ONU GPON ZTE F609', 'category' => 'CPE Hardware', 'stock' => 42, 'min_stock' => 15, 'unit' => 'Unit', 'price' => 'Rp 275.000', 'location' => 'Gudang Banjarmasin - Rak B2'],
            ['code' => 'ONU-HW-HG8245', 'name' => 'Modem ONU Huawei HG8245H5', 'category' => 'CPE Hardware', 'stock' => 28, 'min_stock' => 10, 'unit' => 'Unit', 'price' => 'Rp 310.000', 'location' => 'Gudang Banjarmasin - Rak B3'],
            ['code' => 'FC-SC-UPC', 'name' => 'Fast Connector SC/UPC Biru', 'category' => 'Accessories', 'stock' => 280, 'min_stock' => 50, 'unit' => 'Pcs', 'price' => 'Rp 3.500', 'location' => 'Gudang Banjarmasin - Rak C1'],
            ['code' => 'PC-SC-APC', 'name' => 'Patch Cord SC/UPC - SC/APC 3M', 'category' => 'Kabel & Fiber', 'stock' => 95, 'min_stock' => 25, 'unit' => 'Pcs', 'price' => 'Rp 18.000', 'location' => 'Gudang Banjarmasin - Rak C2'],
            ['code' => 'SP-1X8-PLC', 'name' => 'Splitter PLC 1:8 Mini Tube', 'category' => 'ODP Material', 'stock' => 18, 'min_stock' => 8, 'unit' => 'Pcs', 'price' => 'Rp 45.000', 'location' => 'Gudang Banjarmasin - Rak D1'],
            ['code' => 'VFL-30MW', 'name' => 'Visual Fault Locator 30mW (Senter Laser)', 'category' => 'Tools', 'stock' => 6, 'min_stock' => 2, 'unit' => 'Unit', 'price' => 'Rp 140.000', 'location' => 'Gudang Tools - Lemari 1'],
            ['code' => 'PROT-SLV-60', 'name' => 'Protection Sleeve 60mm', 'category' => 'Accessories', 'stock' => 450, 'min_stock' => 100, 'unit' => 'Pcs', 'price' => 'Rp 400', 'location' => 'Gudang Banjarmasin - Rak C3'],
            ['code' => 'CLP-SUSP-S', 'name' => 'Clamp Suspension Kabel Dropcore', 'category' => 'Accessories', 'stock' => 160, 'min_stock' => 40, 'unit' => 'Pcs', 'price' => 'Rp 4.000', 'location' => 'Gudang Banjarmasin - Rak D3'],
        ];

        foreach ($materials as $matData) {
            Material::updateOrCreate(['code' => $matData['code']], $matData);
        }

        // 4. Seed Tickets
        $ticket1 = Ticket::updateOrCreate(
            ['ticket_number' => 'TKT-2026-001'],
            [
                'user_id' => $client1->id,
                'customer_name' => 'Haji Sulaiman',
                'customer_phone' => '0813-4421-9981',
                'address' => 'Jl. Gatot Subroto Gang 5 No. 18, Banjarmasin Timur',
                'area' => 'Banjarmasin',
                'odp_code' => 'ODP-BJM-03',
                'issue' => 'LOS Merah berkedip sejak pukul 07:15 WITA, koneksi putus total',
                'status' => 'Pending',
                'type' => 'OUTAGE',
                'priority' => 'High',
                'analysis_details' => 'Terdeteksi 12 pelanggan pada ODP-BJM-03 putus bersamaan. Indikasi Fiber Cut Main Feeder atau kabel tiang tertabrak truk.',
                'reported_at' => now()->subHours(3),
            ]
        );

        $ticket2 = Ticket::updateOrCreate(
            ['ticket_number' => 'TKT-2026-002'],
            [
                'user_id' => $client2->id,
                'customer_name' => 'Dra. Hj. Siti Rahmah',
                'customer_phone' => '0852-9901-2234',
                'address' => 'Handil Bakti Komp. Semangat Dalam Blok C No. 4, Barito Kuala',
                'area' => 'Barito Kuala',
                'odp_code' => 'ODP-BTL-02',
                'issue' => 'Internet lambat dan lampu LOS sesekali merah berkedip saat hujan',
                'status' => 'In Progress',
                'type' => 'ISOLIR',
                'priority' => 'Medium',
                'analysis_details' => 'ODP-BTL-02 normal (15 pelanggan lain aman). Redaman pelanggan tinggi (-31.4 dBm). Kemungkinan tekukan dropcore atau sambungan connector kotor.',
                'reported_at' => now()->subHours(2),
            ]
        );

        $ticket3 = Ticket::updateOrCreate(
            ['ticket_number' => 'TKT-2026-003'],
            [
                'user_id' => null,
                'customer_name' => 'CV. Borneo Mandiri (Pak Hendra)',
                'customer_phone' => '0812-7788-3344',
                'address' => 'Jl. Raya Marabahan Simpang 4 No. 45, Barito Kuala',
                'area' => 'Barito Kuala',
                'odp_code' => 'ODP-BTL-03',
                'issue' => 'Kantor mati koneksi internet sejak subuh, modem mati total',
                'status' => 'Pending',
                'type' => 'OUTAGE',
                'priority' => 'Critical',
                'analysis_details' => 'ODP-BTL-03 terputus dari OLT Marabahan. 7 pelanggan offline.',
                'reported_at' => now()->subHours(1),
            ]
        );

        $ticket4 = Ticket::updateOrCreate(
            ['ticket_number' => 'TKT-2026-004'],
            [
                'user_id' => null,
                'customer_name' => 'Ahmad Baihaki, S.Kom',
                'customer_phone' => '0878-1122-9900',
                'address' => 'Jl. Sultan Adam Komp. Taekwondo Jalur 3, Banjarmasin',
                'area' => 'Banjarmasin',
                'odp_code' => 'ODP-BJM-04',
                'issue' => 'Modem tersiram air kopi, indikator power padam tidak bisa hidup',
                'status' => 'In Progress',
                'type' => 'ISOLIR',
                'priority' => 'Medium',
                'analysis_details' => 'ODP normal online. Kerusakan fisik lokal perangkat ONU pelanggan.',
                'reported_at' => now()->subMinutes(50),
            ]
        );

        $ticket5 = Ticket::updateOrCreate(
            ['ticket_number' => 'TKT-2026-005'],
            [
                'user_id' => null,
                'customer_name' => 'Klinik Medika Sehat',
                'customer_phone' => '0811-9922-3311',
                'address' => 'Jl. Lambung Mangkurat No. 88, Banjarmasin Tengah',
                'area' => 'Banjarmasin',
                'odp_code' => 'ODP-BJM-01',
                'issue' => 'Kabel dropcore putus tersangkut mobil box pengangkut barang',
                'status' => 'Solved',
                'type' => 'ISOLIR',
                'priority' => 'High',
                'analysis_details' => 'ODP normal. Penarikan ulang dropcore 65 meter.',
                'reported_at' => now()->subDay(),
            ]
        );

        // 5. Seed Work Orders
        $spk2 = WorkOrder::updateOrCreate(
            ['spk_number' => 'SPK-2026-BTL-019'],
            [
                'ticket_id' => $ticket2->id,
                'technician_id' => $tech2->id,
                'technician_name' => "{$tech2->name}, {$tech4->name}",
                'technician_phone' => $tech2->phone,
                'status' => 'In Progress',
                'tech_notes' => null,
                'dispatched_at' => now()->subHours(1),
                'resolved_at' => null,
            ]
        );

        WorkOrderTechnician::updateOrCreate(
            ['work_order_id' => $spk2->id, 'user_id' => $tech2->id],
            ['technician_name' => $tech2->name, 'technician_phone' => $tech2->phone, 'role_in_team' => 'Team Lead']
        );
        WorkOrderTechnician::updateOrCreate(
            ['work_order_id' => $spk2->id, 'user_id' => $tech4->id],
            ['technician_name' => $tech4->name, 'technician_phone' => $tech4->phone, 'role_in_team' => 'Teknisi Pendamping']
        );

        $spk4 = WorkOrder::updateOrCreate(
            ['spk_number' => 'SPK-2026-BJM-022'],
            [
                'ticket_id' => $ticket4->id,
                'technician_id' => $tech1->id,
                'technician_name' => $tech1->name,
                'technician_phone' => $tech1->phone,
                'status' => 'In Progress',
                'tech_notes' => null,
                'dispatched_at' => now()->subMinutes(40),
                'resolved_at' => null,
            ]
        );

        WorkOrderTechnician::updateOrCreate(
            ['work_order_id' => $spk4->id, 'user_id' => $tech1->id],
            ['technician_name' => $tech1->name, 'technician_phone' => $tech1->phone, 'role_in_team' => 'Lead PIC']
        );

        $spk5 = WorkOrder::updateOrCreate(
            ['spk_number' => 'SPK-2026-BJM-014'],
            [
                'ticket_id' => $ticket5->id,
                'technician_id' => $tech1->id,
                'technician_name' => "{$tech1->name}, {$tech3->name}",
                'technician_phone' => $tech1->phone,
                'status' => 'Solved',
                'tech_notes' => 'Telah dilakukan penarikan ulang kabel dropcore sepanjang 65 meter dan pemasangan suspension baru. Redaman akhir -18.2 dBm stabil.',
                'dispatched_at' => now()->subDay()->addHours(1),
                'resolved_at' => now()->subDay()->addHours(3),
            ]
        );

        WorkOrderTechnician::updateOrCreate(
            ['work_order_id' => $spk5->id, 'user_id' => $tech1->id],
            ['technician_name' => $tech1->name, 'technician_phone' => $tech1->phone, 'role_in_team' => 'Team Lead']
        );
        WorkOrderTechnician::updateOrCreate(
            ['work_order_id' => $spk5->id, 'user_id' => $tech3->id],
            ['technician_name' => $tech3->name, 'technician_phone' => $tech3->phone, 'role_in_team' => 'Teknisi Splicer']
        );

        // 6. Seed Material Outbounds
        $matDropcore = Material::where('code', 'DC-1C-1000')->first();
        $matConnector = Material::where('code', 'FC-SC-UPC')->first();
        $matModemZte = Material::where('code', 'ONU-ZTE-F609')->first();

        MaterialOutbound::updateOrCreate(
            ['outbound_code' => 'REP-001'],
            [
                'work_order_id' => $spk5->id,
                'ticket_id' => $ticket5->id,
                'material_id' => $matDropcore?->id,
                'spk_number' => $spk5->spk_number,
                'ticket_number' => $ticket5->ticket_number,
                'customer_name' => $ticket5->customer_name,
                'technician_name' => "{$tech1->name}, {$tech3->name}",
                'material_name' => 'Kabel Dropcore 1 Core (1000m)',
                'quantity' => 1,
                'unit' => 'Roll',
                'approved_by' => 'Admin Kantor Banjarmasin',
                'status' => 'Used & Completed',
            ]
        );

        MaterialOutbound::updateOrCreate(
            ['outbound_code' => 'REP-002'],
            [
                'work_order_id' => $spk5->id,
                'ticket_id' => $ticket5->id,
                'material_id' => $matConnector?->id,
                'spk_number' => $spk5->spk_number,
                'ticket_number' => $ticket5->ticket_number,
                'customer_name' => $ticket5->customer_name,
                'technician_name' => "{$tech1->name}, {$tech3->name}",
                'material_name' => 'Fast Connector SC/UPC Biru',
                'quantity' => 2,
                'unit' => 'Pcs',
                'approved_by' => 'Admin Kantor Banjarmasin',
                'status' => 'Used & Completed',
            ]
        );

        MaterialOutbound::updateOrCreate(
            ['outbound_code' => 'REP-003'],
            [
                'work_order_id' => $spk2->id,
                'ticket_id' => $ticket2->id,
                'material_id' => $matConnector?->id,
                'spk_number' => $spk2->spk_number,
                'ticket_number' => $ticket2->ticket_number,
                'customer_name' => $ticket2->customer_name,
                'technician_name' => "{$tech2->name}, {$tech4->name}",
                'material_name' => 'Fast Connector SC/UPC Biru',
                'quantity' => 2,
                'unit' => 'Pcs',
                'approved_by' => 'Admin Kantor Banjarmasin',
                'status' => 'In Field Handover',
            ]
        );

        MaterialOutbound::updateOrCreate(
            ['outbound_code' => 'REP-004'],
            [
                'work_order_id' => $spk4->id,
                'ticket_id' => $ticket4->id,
                'material_id' => $matModemZte?->id,
                'spk_number' => $spk4->spk_number,
                'ticket_number' => $ticket4->ticket_number,
                'customer_name' => $ticket4->customer_name,
                'technician_name' => $tech1->name,
                'material_name' => 'Modem ONU GPON ZTE F609',
                'quantity' => 1,
                'unit' => 'Unit',
                'approved_by' => 'Admin Kantor Banjarmasin',
                'status' => 'In Field Handover',
            ]
        );

        // 7. Seed Ticket Activity Logs (Timeline)
        // Ticket 1 (Pending)
        TicketLog::updateOrCreate(
            ['ticket_id' => $ticket1->id, 'action' => 'TICKET_CREATED'],
            [
                'user_id' => $client1->id,
                'actor_name' => $ticket1->customer_name,
                'actor_role' => 'klien',
                'title' => 'Laporan Dibuat oleh Pelanggan',
                'description' => "Pengaduan masuk dari pelanggan {$ticket1->customer_name} via portal mandiri. Gejala: {$ticket1->issue}. Titik simpul: {$ticket1->odp_code}.",
                'notes' => 'Nomor WhatsApp Pelanggan: '.$ticket1->customer_phone,
                'created_at' => $ticket1->reported_at,
            ]
        );

        // Ticket 2 (In Progress - Multi-Teknisi)
        TicketLog::updateOrCreate(
            ['ticket_id' => $ticket2->id, 'action' => 'TICKET_CREATED'],
            [
                'user_id' => $client2->id,
                'actor_name' => $ticket2->customer_name,
                'actor_role' => 'klien',
                'title' => 'Laporan Dibuat oleh Pelanggan',
                'description' => "Pengaduan masuk dari pelanggan {$ticket2->customer_name}. Gejala: {$ticket2->issue}.",
                'notes' => 'Nomor WhatsApp Pelanggan: '.$ticket2->customer_phone,
                'created_at' => $ticket2->reported_at,
            ]
        );
        TicketLog::updateOrCreate(
            ['ticket_id' => $ticket2->id, 'action' => 'SPK_DISPATCHED'],
            [
                'work_order_id' => $spk2->id,
                'user_id' => $admin->id,
                'actor_name' => 'Admin Kantor Banjarmasin',
                'actor_role' => 'admin',
                'title' => 'SPK Diterbitkan & Tim Teknisi Ditugaskan',
                'description' => "Admin NOC menerbitkan {$spk2->spk_number} dan menugaskan Tim Teknisi ({$spk2->technician_name}). Status tim: Sedang bertugas melakukan perbaikan lapangan pada {$ticket2->odp_code} ({$ticket2->address}).",
                'notes' => 'Logistik Diserahkan: 2 Pcs Fast Connector SC/UPC Biru. Bawa tangga & optical power meter (OPM).',
                'created_at' => $spk2->dispatched_at,
            ]
        );

        // Ticket 3 (Pending - CV Borneo Mandiri)
        TicketLog::updateOrCreate(
            ['ticket_id' => $ticket3->id, 'action' => 'TICKET_CREATED'],
            [
                'actor_name' => $ticket3->customer_name,
                'actor_role' => 'klien',
                'title' => 'Laporan Tiket Didaftarkan',
                'description' => "Pengaduan masuk untuk {$ticket3->customer_name}. Gejala: {$ticket3->issue}. Status: Menunggu penugasan tim teknisi dari NOC.",
                'notes' => 'Tingkat Prioritas: Critical (Kantor Operasional)',
                'created_at' => $ticket3->reported_at,
            ]
        );

        // Ticket 4 (In Progress - Ahmad Baihaki)
        TicketLog::updateOrCreate(
            ['ticket_id' => $ticket4->id, 'action' => 'TICKET_CREATED'],
            [
                'actor_name' => $ticket4->customer_name,
                'actor_role' => 'klien',
                'title' => 'Laporan Tiket Didaftarkan',
                'description' => "Pengaduan masuk untuk {$ticket4->customer_name}. Gejala: {$ticket4->issue}.",
                'created_at' => $ticket4->reported_at,
            ]
        );
        TicketLog::updateOrCreate(
            ['ticket_id' => $ticket4->id, 'action' => 'SPK_DISPATCHED'],
            [
                'work_order_id' => $spk4->id,
                'user_id' => $admin->id,
                'actor_name' => 'Admin Kantor Banjarmasin',
                'actor_role' => 'admin',
                'title' => 'SPK Diterbitkan & Teknisi Ditugaskan',
                'description' => "Admin NOC menerbitkan {$spk4->spk_number} dan menugaskan Teknisi ({$spk4->technician_name}). Status: Menuju lokasi pelanggan untuk penggantian unit modem ONT GPON.",
                'notes' => 'Logistik Diserahkan: 1 Unit Modem GPON ZTE F609 baru.',
                'created_at' => $spk4->dispatched_at,
            ]
        );

        // Ticket 5 (Solved - Klinik Medika Sehat)
        TicketLog::updateOrCreate(
            ['ticket_id' => $ticket5->id, 'action' => 'TICKET_CREATED'],
            [
                'actor_name' => $ticket5->customer_name,
                'actor_role' => 'klien',
                'title' => 'Laporan Tiket Didaftarkan',
                'description' => "Pengaduan tercatat untuk {$ticket5->customer_name}. Gejala: {$ticket5->issue}.",
                'created_at' => $ticket5->reported_at,
            ]
        );
        TicketLog::updateOrCreate(
            ['ticket_id' => $ticket5->id, 'action' => 'SPK_DISPATCHED'],
            [
                'work_order_id' => $spk5->id,
                'user_id' => $admin->id,
                'actor_name' => 'Admin Kantor Banjarmasin',
                'actor_role' => 'admin',
                'title' => 'SPK Diterbitkan & Tim Teknisi Ditugaskan',
                'description' => "Admin NOC menerbitkan {$spk5->spk_number} dan menugaskan Tim Teknisi ({$spk5->technician_name}). Status: Penanganan darurat kabel putus tersangkut kendaraan.",
                'notes' => 'Logistik Diserahkan: 1 Roll Dropcore 1000m, 2 Pcs Fast Connector SC/UPC.',
                'created_at' => $spk5->dispatched_at,
            ]
        );
        TicketLog::updateOrCreate(
            ['ticket_id' => $ticket5->id, 'action' => 'TICKET_SOLVED'],
            [
                'work_order_id' => $spk5->id,
                'user_id' => $tech1->id,
                'actor_name' => "{$tech1->name} (Team Lead)",
                'actor_role' => 'teknisi',
                'title' => 'Perbaikan Lapangan Selesai (Solved)',
                'description' => "Tim Teknisi ({$spk5->technician_name}) telah menyelesaikan perbaikan lapangan pada titik {$ticket5->odp_code}. Seluruh koneksi pelanggan kembali normal.",
                'notes' => $spk5->tech_notes,
                'created_at' => $spk5->resolved_at,
            ]
        );
    }
}
