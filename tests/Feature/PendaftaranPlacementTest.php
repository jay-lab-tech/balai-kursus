<?php

namespace Tests\Feature;

use App\Models\Kursus;
use App\Models\Level;
use App\Models\Pendaftaran;
use App\Models\Peserta;
use App\Models\Program;
use App\Models\Score;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendaftaranPlacementTest extends TestCase
{
    use RefreshDatabase;

    private int $pesertaCounter = 0;

    public function test_placement_score_assigns_level_and_class(): void
    {
        $program = Program::create(['nama' => 'English']);
        $dasar = $this->level('Dasar', 1, 0, 59);
        $menengah = $this->level('Menengah', 2, 60, 100);

        $this->kursus($program, $dasar, ['nama' => 'Dasar A']);
        $kursusMenengah = $this->kursus($program, $menengah, ['nama' => 'Menengah A']);

        $pendaftaran = $this->pendaftaran($program);

        $this->placementScore($pendaftaran, 75);

        $pendaftaran->refresh();

        $this->assertSame($menengah->id, $pendaftaran->level_id);
        $this->assertSame($kursusMenengah->id, $pendaftaran->kursus_id);
        $this->assertSame(Pendaftaran::STATUS_MENUNGGU_PEMBAYARAN, $pendaftaran->status_pendaftaran);
        $this->assertSame(300000, (int) $pendaftaran->total_bayar);

        $this->assertDatabaseHas('peserta_kursus_levels', [
            'peserta_id' => $pendaftaran->peserta_id,
            'kursus_id' => $kursusMenengah->id,
            'level_id' => $menengah->id,
        ]);
    }

    public function test_editing_score_without_changing_result_keeps_payment_state(): void
    {
        $program = Program::create(['nama' => 'English']);
        $dasar = $this->level('Dasar', 1, 0, 59);
        $kursus = $this->kursus($program, $dasar, ['nama' => 'Dasar A']);

        $pendaftaran = $this->pendaftaran($program);
        $score = $this->placementScore($pendaftaran, 40);

        // Peserta sudah menyicil pada kelas hasil penempatan.
        $pendaftaran->refresh()->update([
            'terbayar' => 100000,
            'status_pembayaran' => Pendaftaran::PAYMENT_CICIL,
        ]);

        // Perubahan yang tidak menyentuh hasil tes tidak boleh memicu penempatan ulang.
        $score->update(['keterangan' => 'Catatan penguji diperbarui']);

        $pendaftaran->refresh();

        $this->assertSame($kursus->id, $pendaftaran->kursus_id);
        $this->assertSame(100000, (int) $pendaftaran->terbayar);
        $this->assertSame(300000, (int) $pendaftaran->total_bayar);
        $this->assertSame(Pendaftaran::PAYMENT_CICIL, $pendaftaran->status_pembayaran);
    }

    public function test_paid_participant_is_not_moved_when_score_changes(): void
    {
        $program = Program::create(['nama' => 'English']);
        $dasar = $this->level('Dasar', 1, 0, 59);
        $menengah = $this->level('Menengah', 2, 60, 100);

        $kursusDasar = $this->kursus($program, $dasar, ['nama' => 'Dasar A']);
        $this->kursus($program, $menengah, ['nama' => 'Menengah A']);

        $pendaftaran = $this->pendaftaran($program);
        $score = $this->placementScore($pendaftaran, 40);

        $pendaftaran->refresh()->update([
            'terbayar' => 100000,
            'status_pembayaran' => Pendaftaran::PAYMENT_CICIL,
        ]);

        // Nilai direvisi ke level yang lebih tinggi, tetapi uang peserta sudah
        // terikat pada kelas lama, jadi penempatan dipertahankan.
        $score->update(['final_score' => 85]);

        $pendaftaran->refresh();

        $this->assertSame($kursusDasar->id, $pendaftaran->kursus_id);
        $this->assertSame($dasar->id, $pendaftaran->level_id);
        $this->assertSame(100000, (int) $pendaftaran->terbayar);
        $this->assertSame(Pendaftaran::PAYMENT_CICIL, $pendaftaran->status_pembayaran);
    }

    public function test_cancelled_registration_does_not_consume_quota(): void
    {
        $program = Program::create(['nama' => 'English']);
        $dasar = $this->level('Dasar', 1, 0, 59);
        $kursus = $this->kursus($program, $dasar, ['nama' => 'Dasar A', 'kuota' => 1]);

        // Satu-satunya kursi diisi pendaftaran yang sudah dibatalkan.
        $this->pendaftaran($program, [
            'kursus_id' => $kursus->id,
            'level_id' => $dasar->id,
            'status_pendaftaran' => Pendaftaran::STATUS_DIBATALKAN,
        ]);

        $pendaftaran = $this->pendaftaran($program);
        $this->placementScore($pendaftaran, 40);

        $pendaftaran->refresh();

        $this->assertSame($kursus->id, $pendaftaran->kursus_id);
    }

    public function test_full_class_rolls_over_to_the_next_open_class(): void
    {
        $program = Program::create(['nama' => 'English']);
        $dasar = $this->level('Dasar', 1, 0, 59);

        $penuh = $this->kursus($program, $dasar, [
            'nama' => 'Dasar A',
            'kuota' => 1,
            'tanggal_mulai' => now()->toDateString(),
        ]);
        $cadangan = $this->kursus($program, $dasar, [
            'nama' => 'Dasar B',
            'kuota' => 5,
            'tanggal_mulai' => now()->addWeek()->toDateString(),
        ]);

        $this->pendaftaran($program, [
            'kursus_id' => $penuh->id,
            'level_id' => $dasar->id,
            'status_pendaftaran' => Pendaftaran::STATUS_AKTIF,
        ]);

        $pendaftaran = $this->pendaftaran($program);
        $this->placementScore($pendaftaran, 40);

        $pendaftaran->refresh();

        $this->assertSame($cadangan->id, $pendaftaran->kursus_id);
    }

    public function test_participant_waits_for_placement_when_every_class_is_full(): void
    {
        $program = Program::create(['nama' => 'English']);
        $dasar = $this->level('Dasar', 1, 0, 59);
        $kursus = $this->kursus($program, $dasar, ['nama' => 'Dasar A', 'kuota' => 1]);

        $this->pendaftaran($program, [
            'kursus_id' => $kursus->id,
            'level_id' => $dasar->id,
            'status_pendaftaran' => Pendaftaran::STATUS_AKTIF,
        ]);

        $pendaftaran = $this->pendaftaran($program);
        $this->placementScore($pendaftaran, 40);

        $pendaftaran->refresh();

        $this->assertNull($pendaftaran->kursus_id);
        $this->assertSame($dasar->id, $pendaftaran->level_id);
        $this->assertSame(Pendaftaran::STATUS_MENUNGGU_PENEMPATAN, $pendaftaran->status_pendaftaran);
        $this->assertSame(0, (int) $pendaftaran->total_bayar);
    }

    private function level(string $nama, int $urutan, ?int $min, ?int $max): Level
    {
        return Level::create([
            'nama' => $nama,
            'urutan' => $urutan,
            'nilai_min' => $min,
            'nilai_max' => $max,
        ]);
    }

    private function kursus(Program $program, Level $level, array $attributes = []): Kursus
    {
        return Kursus::create(array_merge([
            'program_id' => $program->id,
            'level_id' => $level->id,
            'nama' => 'Kelas',
            'periode' => '2026-A',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonth()->toDateString(),
            'harga' => 300000,
            'harga_upi' => 250000,
            'kuota' => 20,
            'status' => 'buka',
        ], $attributes));
    }

    private function pendaftaran(Program $program, array $attributes = []): Pendaftaran
    {
        $this->pesertaCounter++;

        $user = User::factory()->create(['role' => 'peserta']);

        $peserta = Peserta::create([
            'user_id' => $user->id,
            'nomor_peserta' => 'PS-2026-4'.str_pad((string) $this->pesertaCounter, 4, '0', STR_PAD_LEFT),
            'no_hp' => '08123456789',
            'instansi' => 'UPI',
        ]);

        return Pendaftaran::create(array_merge([
            'peserta_id' => $peserta->id,
            'program_id' => $program->id,
            'status_pendaftaran' => Pendaftaran::STATUS_MENUNGGU_TES,
            'status_pembayaran' => Pendaftaran::PAYMENT_PENDING,
            'total_bayar' => 0,
            'terbayar' => 0,
        ], $attributes));
    }

    private function placementScore(Pendaftaran $pendaftaran, float $finalScore): Score
    {
        return Score::create([
            'pendaftaran_id' => $pendaftaran->id,
            'jenis' => Score::TYPE_PLACEMENT,
            'final_score' => $finalScore,
            'status' => 'pass',
        ]);
    }
}
