<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialOutbound;
use App\Models\Ticket;
use App\Models\TopologyNode;
use App\Models\User;
use App\Models\WorkOrder;
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
                'name' => 'Admin NOC Pusat',
                'email' => 'admin.noc@borneofiber.net',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '0812-5541-0001',
                'area' => 'Kantor NOC Regional Kalsel',
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

        // 2. Seed Topology Nodes
        $nodes = [
            ['node_id' => 'NODE-01', 'name' => 'OLT-BJM-PUSAT', 'type' => 'OLT', 'area' => 'Banjarmasin', 'location' => 'Jl. Ahmad Yani KM 4.5, Banjarmasin', 'coordinates' => '-3.3278, 114.6062', 'total_ports' => 16, 'used_ports' => 14, 'status' => 'Online', 'connected_odp' => 12, 'connected_users' => 120, 'issue' => null],
            ['node_id' => 'NODE-02', 'name' => 'ODP-BJM-01', 'type' => 'ODP', 'area' => 'Banjarmasin', 'location' => 'Jl. Lambung Mangkurat No. 12', 'coordinates' => '-3.3186, 114.5908', 'total_ports' => 16, 'used_ports' => 14, 'status' => 'Online', 'connected_odp' => null, 'connected_users' => 14, 'issue' => null],
            ['node_id' => 'NODE-03', 'name' => 'ODP-BJM-02', 'type' => 'ODP', 'area' => 'Banjarmasin', 'location' => 'Jl. Veteran Komp. Merpati', 'coordinates' => '-3.3204, 114.6012', 'total_ports' => 8, 'used_ports' => 8, 'status' => 'Online', 'connected_odp' => null, 'connected_users' => 8, 'issue' => null],
            ['node_id' => 'NODE-04', 'name' => 'ODP-BJM-03', 'type' => 'ODP', 'area' => 'Banjarmasin', 'location' => 'Jl. Gatot Subroto Gang 5', 'coordinates' => '-3.3325, 114.6191', 'total_ports' => 16, 'used_ports' => 12, 'status' => 'Offline', 'connected_odp' => null, 'connected_users' => 12, 'issue' => 'Fiber Cut Main Feeder'],
            ['node_id' => 'NODE-05', 'name' => 'ODP-BJM-04', 'type' => 'ODP', 'area' => 'Banjarmasin', 'location' => 'Jl. Sultan Adam Komp. Taekwondo', 'coordinates' => '-3.2988, 114.6022', 'total_ports' => 16, 'used_ports' => 15, 'status' => 'Online', 'connected_odp' => null, 'connected_users' => 15, 'issue' => null],
            ['node_id' => 'NODE-06', 'name' => 'OLT-BTL-MARABAHAN', 'type' => 'OLT', 'area' => 'Barito Kuala', 'location' => 'Jl. Jenderal Sudirman No. 8, Marabahan', 'coordinates' => '-2.9961, 114.7705', 'total_ports' => 8, 'used_ports' => 6, 'status' => 'Online', 'connected_odp' => 8, 'connected_users' => 60, 'issue' => null],
            ['node_id' => 'NODE-07', 'name' => 'ODP-BTL-01', 'type' => 'ODP', 'area' => 'Barito Kuala', 'location' => 'Jl. Trans Kalimantan, Alalak Berangas', 'coordinates' => '-3.2671, 114.5772', 'total_ports' => 16, 'used_ports' => 11, 'status' => 'Online', 'connected_odp' => null, 'connected_users' => 11, 'issue' => null],
            ['node_id' => 'NODE-08', 'name' => 'ODP-BTL-02', 'type' => 'ODP', 'area' => 'Barito Kuala', 'location' => 'Handil Bakti Komp. Semangat Dalam', 'coordinates' => '-3.2755, 114.6133', 'total_ports' => 16, 'used_ports' => 16, 'status' => 'Online', 'connected_odp' => null, 'connected_users' => 16, 'issue' => null],
            ['node_id' => 'NODE-09', 'name' => 'ODP-BTL-03', 'type' => 'ODP', 'area' => 'Barito Kuala', 'location' => 'Jl. Raya Marabahan Simpang 4', 'coordinates' => '-3.0112, 114.7554', 'total_ports' => 8, 'used_ports' => 7, 'status' => 'Offline', 'connected_odp' => null, 'connected_users' => 7, 'issue' => 'Power Loss Adaptor Rusak'],
            ['node_id' => 'NODE-10', 'name' => 'ODP-BTL-04', 'type' => 'ODP', 'area' => 'Barito Kuala', 'location' => 'Jl. Trans Kalimantan KM 12 Anjir Muara', 'coordinates' => '-3.1954, 114.5122', 'total_ports' => 8, 'used_ports' => 5, 'status' => 'Online', 'connected_odp' => null, 'connected_users' => 5, 'issue' => null],
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
                'technician_name' => $tech2->name,
                'technician_phone' => $tech2->phone,
                'status' => 'In Progress',
                'tech_notes' => null,
                'dispatched_at' => now()->subHours(1),
                'resolved_at' => null,
            ]
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

        $spk5 = WorkOrder::updateOrCreate(
            ['spk_number' => 'SPK-2026-BJM-014'],
            [
                'ticket_id' => $ticket5->id,
                'technician_id' => $tech1->id,
                'technician_name' => $tech1->name,
                'technician_phone' => $tech1->phone,
                'status' => 'Solved',
                'tech_notes' => 'Telah dilakukan penarikan ulang kabel dropcore sepanjang 65 meter dan pemasangan suspension baru. Redaman akhir -18.2 dBm stabil.',
                'dispatched_at' => now()->subDay()->addHours(1),
                'resolved_at' => now()->subDay()->addHours(3),
            ]
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
                'technician_name' => $tech1->name,
                'material_name' => 'Kabel Dropcore 1 Core (1000m)',
                'quantity' => 1,
                'unit' => 'Roll',
                'approved_by' => 'Admin NOC',
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
                'technician_name' => $tech1->name,
                'material_name' => 'Fast Connector SC/UPC Biru',
                'quantity' => 2,
                'unit' => 'Pcs',
                'approved_by' => 'Admin NOC',
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
                'technician_name' => $tech2->name,
                'material_name' => 'Fast Connector SC/UPC Biru',
                'quantity' => 2,
                'unit' => 'Pcs',
                'approved_by' => 'Admin NOC',
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
                'approved_by' => 'Admin NOC',
                'status' => 'In Field Handover',
            ]
        );
    }
}
