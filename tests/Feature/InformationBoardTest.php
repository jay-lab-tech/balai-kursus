<?php

namespace Tests\Feature;

use App\Models\Hari;
use App\Models\Instruktur;
use App\Models\InstrukturKursusLevel;
use App\Models\Jadwal;
use App\Models\Kela;
use App\Models\Kursus;
use App\Models\Level;
use App\Models\Lokasi;
use App\Models\Program;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InformationBoardTest extends TestCase
{
    use RefreshDatabase;

    public function test_information_board_is_public_and_only_shows_today_schedule(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-04-07 09:00:00'));

        $todayJadwal = $this->createJadwalForDate('2026-04-07', '08:00:00', '10:00:00', 'English Dasar');
        $tomorrowJadwal = $this->createJadwalForDate('2026-04-08', '08:00:00', '10:00:00', 'English Besok');

        $response = $this->get('/papan-informasi');

        $response->assertOk();
        $response->assertSee('Papan Informasi Kursus');
        $response->assertSee($todayJadwal->kursus->nama);
        $response->assertDontSee($tomorrowJadwal->kursus->nama);
        $response->assertSee('Sedang Berlangsung');

        Carbon::setTestNow();
    }

    public function test_display_mode_enables_refresh_hint(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-04-07 07:00:00'));

        $this->createJadwalForDate('2026-04-07', '10:00:00', '12:00:00', 'English Display');

        $response = $this->get('/papan-informasi?display=1');

        $response->assertOk();
        $response->assertSee('Mode display aktif. Halaman diperbarui otomatis setiap 60 detik.');
        $response->assertSee('Mode Display');

        Carbon::setTestNow();
    }

    private function createJadwalForDate(string $date, string $start, string $end, string $courseName): Jadwal
    {
        $program = Program::create(['nama' => 'English']);
        $level = Level::create(['nama' => 'Dasar']);
        $lokasi = Lokasi::create([
            'nama' => 'Gedung Utama',
            'alamat' => 'Jalan Pendidikan 1',
            'kota' => 'Bandung',
        ]);
        $kelas = Kela::create(['nama' => 'Ruang 101']);
        $hari = Hari::create(['nama' => 'Selasa', 'urutan' => 2]);
        $user = User::factory()->create(['role' => 'instruktur']);
        $instruktur = Instruktur::create([
            'user_id' => $user->id,
            'nama_instr' => 'Budi Santoso',
            'spesialisasi' => 'Speaking',
        ]);

        $kursus = Kursus::create([
            'program_id' => $program->id,
            'level_id' => $level->id,
            'nama' => $courseName,
            'periode' => 'Gelombang 1',
            'tanggal_mulai' => $date,
            'tanggal_selesai' => Carbon::parse($date)->addMonth()->toDateString(),
            'harga' => 300000,
            'harga_upi' => 250000,
            'kuota' => 20,
            'status' => 'berjalan',
        ]);

        InstrukturKursusLevel::create([
            'instruktur_id' => $instruktur->id,
            'kursus_id' => $kursus->id,
            'level_id' => $level->id,
            'assigned_at' => Carbon::parse($date)->subDay(),
        ]);

        return Jadwal::create([
            'kursus_id' => $kursus->id,
            'lokasi_id' => $lokasi->id,
            'kela_id' => $kelas->id,
            'hari_id' => $hari->id,
            'pertemuan_ke' => 1,
            'tgl_pertemuan' => $date,
            'jam_mulai' => $start,
            'jam_selesai' => $end,
        ]);
    }
}
