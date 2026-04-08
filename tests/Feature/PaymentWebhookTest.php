<?php

namespace Tests\Feature;

use App\Models\Kursus;
use App\Models\Level;
use App\Models\Pendaftaran;
use App\Models\Payment;
use App\Models\Peserta;
use App\Models\Program;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PaymentWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_midtrans_notification_marks_payment_success_and_updates_pendaftaran(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);

        $peserta = Peserta::create([
            'user_id' => $user->id,
            'nomor_peserta' => 'PS-2026-20001',
            'no_hp' => '08123456789',
            'instansi' => 'UPI',
        ]);

        $program = Program::create(['nama' => 'English']);
        $level = Level::create(['nama' => 'Dasar']);
        $kursus = Kursus::create([
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

        $pendaftaran = Pendaftaran::create([
            'peserta_id' => $peserta->id,
            'program_id' => $program->id,
            'level_id' => $level->id,
            'kursus_id' => $kursus->id,
            'status_pendaftaran' => Pendaftaran::STATUS_MENUNGGU_PEMBAYARAN,
            'status_pembayaran' => Pendaftaran::PAYMENT_PENDING,
            'total_bayar' => 150000,
            'terbayar' => 0,
        ]);

        Payment::create([
            'order_id' => 'ORDER-WEBHOOK-1',
            'amount' => 150000,
            'description' => 'Pembayaran Kelas',
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
            'status' => 'pending',
            'user_id' => $user->id,
            'pendaftaran_id' => $pendaftaran->id,
        ]);

        $midtrans = Mockery::mock(MidtransService::class);
        $midtrans->shouldReceive('getStatus')
            ->once()
            ->with('ORDER-WEBHOOK-1')
            ->andReturn((object) ['transaction_status' => 'settlement']);

        $this->app->instance(MidtransService::class, $midtrans);

        $response = $this->post('/peserta/pembayaran-notification', [
            'order_id' => 'ORDER-WEBHOOK-1',
            'transaction_status' => 'settlement',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('payments', [
            'order_id' => 'ORDER-WEBHOOK-1',
            'status' => 'success',
        ]);

        $this->assertDatabaseHas('pendaftarans', [
            'id' => $pendaftaran->id,
            'terbayar' => 150000,
            'status_pembayaran' => Pendaftaran::PAYMENT_LUNAS,
            'status_pendaftaran' => Pendaftaran::STATUS_AKTIF,
        ]);
    }
}
