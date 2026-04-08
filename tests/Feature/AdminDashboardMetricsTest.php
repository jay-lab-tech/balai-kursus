<?php

namespace Tests\Feature;

use App\Models\Kursus;
use App\Models\Level;
use App\Models\Payment;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AdminDashboardMetricsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_uses_successful_online_payments_for_metrics(): void
    {
        Cache::flush();

        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'peserta']);

        $program = Program::create(['nama' => 'English']);
        $level = Level::create(['nama' => 'Dasar']);

        Kursus::create([
            'program_id' => $program->id,
            'level_id' => $level->id,
            'nama' => 'English Dasar',
            'periode' => '2026-A',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
            'harga' => 300000,
            'harga_upi' => 250000,
            'kuota' => 20,
            'status' => 'buka',
        ]);

        Payment::create([
            'order_id' => 'ORDER-SUCCESS-1',
            'amount' => 150000,
            'description' => 'Pembayaran Kelas',
            'customer_name' => 'Peserta 1',
            'customer_email' => 'peserta1@example.test',
            'customer_phone' => '08123',
            'status' => 'success',
        ]);

        Payment::create([
            'order_id' => 'ORDER-PENDING-1',
            'amount' => 999999,
            'description' => 'Pembayaran Pending',
            'customer_name' => 'Peserta 2',
            'customer_email' => 'peserta2@example.test',
            'customer_phone' => '08124',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('150,000', false);
        $response->assertDontSee('999,999', false);
    }
}
