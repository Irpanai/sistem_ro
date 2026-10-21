<?php

namespace Tests\Feature;

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

    public function test_technician_can_access_portal(): void
    {
        $tech = User::where('role', 'teknisi')->first();
        if ($tech) {
            $this->actingAs($tech);

            $res = $this->get('/teknisi/portal');
            $res->assertStatus(200);
            $res->assertSee('Tugas SPK Saya');
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
}
