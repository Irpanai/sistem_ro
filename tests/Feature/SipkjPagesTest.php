<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\MaterialOutbound;
use App\Models\Ticket;
use App\Models\TopologyNode;
use App\Models\User;
use Tests\TestCase;

class SipkjPagesTest extends TestCase
{
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_admin_can_access_dashboard_and_topology(): void
    {
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $this->actingAs($admin);

            $dashboardRes = $this->get('/dashboard');
            $dashboardRes->assertStatus(200);
            $dashboardRes->assertSee('Network Diagnostic');

            $topologyRes = $this->get('/topologi');
            $topologyRes->assertStatus(200);
            $topologyRes->assertSee('topologyMap');

            $ticketRes = $this->get('/tiket');
            $ticketRes->assertStatus(200);

            $logistikRes = $this->get('/logistik');
            $logistikRes->assertStatus(200);

            $laporanRes = $this->get('/laporan');
            $laporanRes->assertStatus(200);
        }
    }

    public function test_admin_can_access_user_management(): void
    {
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $this->actingAs($admin);

            $response = $this->get('/users');
            $response->assertStatus(200);
            $response->assertSee('Manajemen Pengguna');
        }
    }

    public function test_admin_can_create_user(): void
    {
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $this->actingAs($admin);

            $uniqueUsername = 'test_tech_'.time();
            $response = $this->post('/users', [
                'name' => 'Teknisi Uji Coba',
                'username' => $uniqueUsername,
                'email' => $uniqueUsername.'@sipkj.net',
                'password' => 'password123',
                'role' => 'teknisi',
                'area' => 'Banjarmasin',
                'phone' => '081299998888',
                'status' => 'Available',
            ]);

            $response->assertRedirect('/users');
            $this->assertDatabaseHas('users', [
                'username' => $uniqueUsername,
                'role' => 'teknisi',
            ]);
        }
    }

    public function test_technician_can_view_materials_diserahkan(): void
    {
        $tech = User::where('role', 'teknisi')->first();
        if ($tech) {
            $this->actingAs($tech);

            $res = $this->get('/logistik');
            $res->assertStatus(200);
            $res->assertSee('Material Diserahkan ke Teknisi');
        }
    }

    public function test_technician_can_access_portal(): void
    {
        $tech = User::where('role', 'teknisi')->first();
        if ($tech) {
            $this->actingAs($tech);

            $res = $this->get('/teknisi/portal');
            $res->assertStatus(200);
            $res->assertSee('Portal Teknisi');
        }
    }

    public function test_client_can_access_portal(): void
    {
        $client = User::where('role', 'klien')->first();
        if ($client) {
            $this->actingAs($client);

            $res = $this->get('/klien/portal');
            $res->assertStatus(200);
            $res->assertSee('Portal Layanan Pelanggan');
        }
    }

    public function test_client_can_access_history(): void
    {
        $client = User::where('role', 'klien')->first();
        if ($client) {
            $this->actingAs($client);

            $res = $this->get('/klien/riwayat');
            $res->assertStatus(200);
            $res->assertSee('Riwayat Tiket &amp; Pengaduan', false);
        }
    }

    public function test_technician_can_access_history(): void
    {
        $tech = User::where('role', 'teknisi')->first();
        if ($tech) {
            $this->actingAs($tech);

            $res = $this->get('/teknisi/riwayat');
            $res->assertStatus(200);
            $res->assertSee('Riwayat SPK &amp; Tiket Selesai', false);
        }
    }

    public function test_client_portal_shows_auto_detected_odp_without_dropdown(): void
    {
        $client = User::where('role', 'klien')->first();
        if ($client) {
            $this->actingAs($client);

            $res = $this->get('/klien/portal');
            $res->assertStatus(200);
            $res->assertSee('Otomatis Tertera');
            $res->assertSee('name="odp_code"', false);
            $res->assertDontSee('<select name="odp_code">', false);
        }
    }

    public function test_technician_logistik_is_riwayat_material_only(): void
    {
        $tech = User::where('role', 'teknisi')->first();
        if ($tech) {
            $this->actingAs($tech);

            $res = $this->get('/logistik');
            $res->assertStatus(200);
            $res->assertSee('Riwayat Material yang Pernah Diterima');
            $res->assertDontSee('Katalog Stok Gudang');
        }
    }

    public function test_dashboard_excludes_olt_nodes(): void
    {
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $this->actingAs($admin);

            $res = $this->get('/dashboard');
            $res->assertStatus(200);
            $res->assertSee('Status Titik ODP Distribusi Lapangan');
            $res->assertDontSee('OLT-BJM-KANTOR');
        }
    }

    public function test_topology_has_kecamatan_filter(): void
    {
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $this->actingAs($admin);

            $res = $this->get('/topologi');
            $res->assertStatus(200);
            $res->assertSee('Banjarmasin Utara');
            $res->assertSee('Banjarmasin Timur');
            $res->assertSee('Banjarmasin Barat');
        }
    }

    public function test_laporan_supports_monthly_filter_and_stok_opname(): void
    {
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $this->actingAs($admin);

            $resBulanan = $this->get('/laporan?tab=bulanan&month=10&year=2026');
            $resBulanan->assertStatus(200);
            $resBulanan->assertSee('Laporan Pengeluaran Material (Bulanan)');

            $resOpname = $this->get('/laporan?tab=stok_opname');
            $resOpname->assertStatus(200);
            $resOpname->assertSee('Audit Stok Opname Fisik Gudang');
            $resOpname->assertSee('Lembar Audit Rekonsiliasi Stok Opname Fisik Gudang');
        }
    }

    public function test_dashboard_displays_team_sla_and_not_individual_technicians(): void
    {
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $this->actingAs($admin);

            $res = $this->get('/dashboard');
            $res->assertStatus(200);
            $res->assertSee('SLA Tim Lapangan &amp; Performa Regu', false);
            $res->assertSee('SLA Khusus Regu / Tim');
            $res->assertSee('Tim Alpha');
            $res->assertSee('Tim Beta');
            $res->assertSee('Tim Charlie');
            $res->assertSee('Kepatuhan SLA');
        }
    }

    public function test_printable_pdf_documents_accessible(): void
    {
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $this->actingAs($admin);

            // 1. Ticket SPK Print
            $ticket = Ticket::first();
            if ($ticket) {
                $spkRes = $this->get("/tiket/{$ticket->id}/print");
                $spkRes->assertStatus(200);
                $spkRes->assertSee('SURAT PERINTAH KERJA (SPK)');

                // 2. Klien Receipt Print
                $clientReceiptRes = $this->get("/klien/tiket/{$ticket->id}/print");
                $clientReceiptRes->assertStatus(200);
                $clientReceiptRes->assertSee('TANDA TERIMA &amp; LEMBAR BUKTI PENGADUAN GANGGUAN', false);
            }

            // 3. Topology Node Print
            $node = TopologyNode::first();
            if ($node) {
                $nodeRes = $this->get("/topologi/{$node->id}/print");
                $nodeRes->assertStatus(200);
                $nodeRes->assertSee('BERITA ACARA REGISTRASI &amp; SERTIFIKAT SIMPUL DISTRIBUSI', false);
            }

            // 4. Material Outbound Print
            $outbound = MaterialOutbound::first();
            if ($outbound) {
                $outboundRes = $this->get("/logistik/outbound/{$outbound->id}/print");
                $outboundRes->assertStatus(200);
                $outboundRes->assertSee('SURAT BUKTI PENGELUARAN &amp; SERAH TERIMA MATERIAL (SPMB)', false);
            }

            // 5. Material SKU Print
            $material = Material::first();
            if ($material) {
                $materialRes = $this->get("/logistik/material/{$material->id}/print");
                $materialRes->assertStatus(200);
                $materialRes->assertSee('KARTU KENDALI INVENTARIS &amp; STOK GUDANG LOGISTIK', false);
            }

            // 6. User Account Registration Print
            $user = User::first();
            if ($user) {
                $userRes = $this->get("/users/{$user->id}/print");
                $userRes->assertStatus(200);
                $userRes->assertSee('LEMBAR BUKTI REGISTRASI AKUN &amp; PAKTA AKSES SISTEM', false);
            }

            // 7. Laporan Print
            $laporanRes = $this->get('/laporan/print?tab=bulanan');
            $laporanRes->assertStatus(200);
            $laporanRes->assertSee('LAPORAN REKAPITULASI PENGELUARAN LOGISTIK &amp; MATERIAL BULANAN', false);

            $laporanOpnameRes = $this->get('/laporan/print?tab=stok_opname');
            $laporanOpnameRes->assertStatus(200);
            $laporanOpnameRes->assertSee('BERITA ACARA AUDIT REKONSILIASI STOK OPNAME FISIK GUDANG');
        }
    }
}
