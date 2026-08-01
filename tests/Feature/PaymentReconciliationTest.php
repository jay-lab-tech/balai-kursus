<?php

namespace Tests\Feature;

use App\Models\Kursus;
use App\Models\Level;
use App\Models\Payment;
use App\Models\Pendaftaran;
use App\Models\Peserta;
use App\Models\Program;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PaymentReconciliationTest extends TestCase
{
    use RefreshDatabase;

    private int $counter = 0;

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_repeated_notification_does_not_double_count_terbayar(): void
    {
        ['user' => $user, 'pendaftaran' => $pendaftaran] = $this->scenario(['total_bayar' => 300000]);

        $this->payment($user, $pendaftaran, 'ORDER-REPLAY-1', 150000);

        $midtrans = Mockery::mock(MidtransService::class);
        $midtrans->shouldReceive('isValidNotification')->twice()->andReturnTrue();
        $midtrans->shouldReceive('getStatus')->twice()->with('ORDER-REPLAY-1')
            ->andReturn((object) ['transaction_status' => 'settlement']);

        $this->app->instance(MidtransService::class, $midtrans);

        $payload = [
            'order_id' => 'ORDER-REPLAY-1',
            'transaction_status' => 'settlement',
            'status_code' => '200',
            'gross_amount' => '150000.00',
            'signature_key' => 'test-signature',
        ];

        // Midtrans kerap mengirim ulang notifikasi yang sama.
        $this->post('/peserta/pembayaran-notification', $payload)->assertOk();
        $this->post('/peserta/pembayaran-notification', $payload)->assertOk();

        $this->assertDatabaseHas('pendaftarans', [
            'id' => $pendaftaran->id,
            'terbayar' => 150000,
            'status_pembayaran' => Pendaftaran::PAYMENT_CICIL,
            'status_pendaftaran' => Pendaftaran::STATUS_MENUNGGU_PEMBAYARAN,
        ]);
    }

    public function test_completed_registration_keeps_its_status_after_notification(): void
    {
        ['user' => $user, 'pendaftaran' => $pendaftaran] = $this->scenario([
            'total_bayar' => 300000,
            'status_pendaftaran' => Pendaftaran::STATUS_SELESAI,
        ]);

        $this->payment($user, $pendaftaran, 'ORDER-SELESAI-1', 300000);

        $midtrans = Mockery::mock(MidtransService::class);
        $midtrans->shouldReceive('isValidNotification')->once()->andReturnTrue();
        $midtrans->shouldReceive('getStatus')->once()->with('ORDER-SELESAI-1')
            ->andReturn((object) ['transaction_status' => 'settlement']);

        $this->app->instance(MidtransService::class, $midtrans);

        $this->post('/peserta/pembayaran-notification', [
            'order_id' => 'ORDER-SELESAI-1',
            'transaction_status' => 'settlement',
            'status_code' => '200',
            'gross_amount' => '300000.00',
            'signature_key' => 'test-signature',
        ])->assertOk();

        $this->assertDatabaseHas('pendaftarans', [
            'id' => $pendaftaran->id,
            'terbayar' => 300000,
            'status_pembayaran' => Pendaftaran::PAYMENT_LUNAS,
            'status_pendaftaran' => Pendaftaran::STATUS_SELESAI,
        ]);
    }

    public function test_failed_callback_cannot_be_triggered_by_another_user(): void
    {
        ['user' => $user, 'pendaftaran' => $pendaftaran] = $this->scenario(['total_bayar' => 300000]);

        $this->payment($user, $pendaftaran, 'ORDER-OWNER-1', 300000);

        $penyusup = User::factory()->create(['role' => 'peserta']);

        $this->actingAs($penyusup)
            ->get('/peserta/pembayaran-failed/ORDER-OWNER-1')
            ->assertRedirect(route('peserta.pendaftaran.index'));

        $this->assertDatabaseHas('payments', [
            'order_id' => 'ORDER-OWNER-1',
            'status' => 'pending',
        ]);
    }

    public function test_failed_callback_cannot_downgrade_a_successful_payment(): void
    {
        ['user' => $user, 'pendaftaran' => $pendaftaran] = $this->scenario(['total_bayar' => 300000]);

        $this->payment($user, $pendaftaran, 'ORDER-SUKSES-1', 300000, 'success');

        $this->actingAs($user)
            ->get('/peserta/pembayaran-failed/ORDER-SUKSES-1')
            ->assertRedirect(route('peserta.pendaftaran.index'));

        $this->assertDatabaseHas('payments', [
            'order_id' => 'ORDER-SUKSES-1',
            'status' => 'success',
        ]);
    }

    public function test_second_payment_is_blocked_while_a_pending_bill_still_exists(): void
    {
        ['user' => $user, 'pendaftaran' => $pendaftaran] = $this->scenario(['total_bayar' => 300000]);

        $this->payment($user, $pendaftaran, 'ORDER-PENDING-1', 300000);

        $this->actingAs($user)
            ->postJson(route('peserta.pendaftaran.create-payment', $pendaftaran))
            ->assertStatus(400)
            ->assertJsonPath('error', 'Masih ada tagihan yang belum diselesaikan. Selesaikan atau tunggu tagihan itu kedaluwarsa.');

        $this->assertSame(1, Payment::where('pendaftaran_id', $pendaftaran->id)->count());
    }

    public function test_payment_amount_cannot_exceed_the_remaining_bill(): void
    {
        ['user' => $user, 'pendaftaran' => $pendaftaran] = $this->scenario([
            'total_bayar' => 300000,
            'terbayar' => 200000,
            'status_pembayaran' => Pendaftaran::PAYMENT_CICIL,
        ]);

        $this->actingAs($user)
            ->postJson(route('peserta.pendaftaran.create-payment', $pendaftaran), ['amount' => 150000])
            ->assertStatus(400)
            ->assertJsonPath('error', 'Jumlah pembayaran melebihi sisa yang harus dibayar');

        $this->assertSame(0, Payment::where('pendaftaran_id', $pendaftaran->id)->count());
    }

    /**
     * @return array{user: User, pendaftaran: Pendaftaran}
     */
    private function scenario(array $pendaftaranAttributes = []): array
    {
        $this->counter++;

        $user = User::factory()->create(['role' => 'peserta']);

        $peserta = Peserta::create([
            'user_id' => $user->id,
            'nomor_peserta' => 'PS-2026-5'.str_pad((string) $this->counter, 4, '0', STR_PAD_LEFT),
            'no_hp' => '08123456789',
            'instansi' => 'UPI',
        ]);

        $program = Program::create(['nama' => 'English '.$this->counter]);
        $level = Level::create(['nama' => 'Dasar '.$this->counter, 'urutan' => 1]);

        $kursus = Kursus::create([
            'program_id' => $program->id,
            'level_id' => $level->id,
            'nama' => 'Kelas '.$this->counter,
            'periode' => '2026-A',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
            'harga' => 300000,
            'harga_upi' => 250000,
            'kuota' => 20,
            'status' => 'buka',
        ]);

        $pendaftaran = Pendaftaran::create(array_merge([
            'peserta_id' => $peserta->id,
            'program_id' => $program->id,
            'level_id' => $level->id,
            'kursus_id' => $kursus->id,
            'status_pendaftaran' => Pendaftaran::STATUS_MENUNGGU_PEMBAYARAN,
            'status_pembayaran' => Pendaftaran::PAYMENT_PENDING,
            'total_bayar' => 300000,
            'terbayar' => 0,
        ], $pendaftaranAttributes));

        return ['user' => $user, 'pendaftaran' => $pendaftaran];
    }

    private function payment(
        User $user,
        Pendaftaran $pendaftaran,
        string $orderId,
        int $amount,
        string $status = 'pending'
    ): Payment {
        return Payment::create([
            'order_id' => $orderId,
            'amount' => $amount,
            'description' => 'Pembayaran Kelas',
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
            'status' => $status,
            'user_id' => $user->id,
            'pendaftaran_id' => $pendaftaran->id,
        ]);
    }
}
