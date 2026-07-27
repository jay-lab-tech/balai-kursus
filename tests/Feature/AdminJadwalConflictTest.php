<?php

namespace Tests\Feature;

use App\Models\Hari;
use App\Models\Jadwal;
use App\Models\Kela;
use App\Models\Kursus;
use App\Models\Level;
use App\Models\Lokasi;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminJadwalConflictTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_store_schedule_that_overlaps_in_same_location_and_date(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$hari, $lokasi, $kelas] = $this->createMasterScheduleData();
        $existingCourse = $this->createCourse('English Pagi');
        $newCourse = $this->createCourse('English Siang');

        Jadwal::create([
            'kursus_id' => $existingCourse->id,
            'lokasi_id' => $lokasi->id,
            'kela_id' => $kelas->id,
            'hari_id' => $hari->id,
            'pertemuan_ke' => 1,
            'tgl_pertemuan' => '2026-05-04',
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '08:00:00',
            'created_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.jadwal.store', $newCourse), [
                'pertemuan_ke' => 1,
                'tgl_pertemuan' => '2026-05-04',
                'jam_mulai' => '07:30',
                'jam_selesai' => '08:30',
                'lokasi_id' => $lokasi->id,
                'kela_id' => $kelas->id,
                'hari_id' => $hari->id,
            ])
            ->assertSessionHasErrors('jam_mulai');

        $this->assertDatabaseCount('jadwals', 1);
    }

    public function test_admin_can_store_schedule_when_slot_starts_after_existing_schedule_ends(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$hari, $lokasi, $kelas] = $this->createMasterScheduleData();
        $existingCourse = $this->createCourse('English Pagi');
        $newCourse = $this->createCourse('English Lanjutan');

        Jadwal::create([
            'kursus_id' => $existingCourse->id,
            'lokasi_id' => $lokasi->id,
            'kela_id' => $kelas->id,
            'hari_id' => $hari->id,
            'pertemuan_ke' => 1,
            'tgl_pertemuan' => '2026-05-04',
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '08:00:00',
            'created_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.jadwal.store', $newCourse), [
                'pertemuan_ke' => 2,
                'tgl_pertemuan' => '2026-05-04',
                'jam_mulai' => '08:00',
                'jam_selesai' => '09:00',
                'lokasi_id' => $lokasi->id,
                'kela_id' => $kelas->id,
                'hari_id' => $hari->id,
            ])
            ->assertRedirect(route('admin.jadwal.index', $newCourse));

        $this->assertDatabaseHas('jadwals', [
            'kursus_id' => $newCourse->id,
            'lokasi_id' => $lokasi->id,
            'tgl_pertemuan' => '2026-05-04',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '09:00:00',
        ]);
    }

    public function test_admin_cannot_update_schedule_to_conflicting_slot(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$hari, $lokasi, $kelas] = $this->createMasterScheduleData();
        $existingCourse = $this->createCourse('English Pagi');
        $updatedCourse = $this->createCourse('English Malam');

        Jadwal::create([
            'kursus_id' => $existingCourse->id,
            'lokasi_id' => $lokasi->id,
            'kela_id' => $kelas->id,
            'hari_id' => $hari->id,
            'pertemuan_ke' => 1,
            'tgl_pertemuan' => '2026-05-04',
            'jam_mulai' => '07:00:00',
            'jam_selesai' => '08:00:00',
            'created_by' => $admin->id,
        ]);

        $jadwalToUpdate = Jadwal::create([
            'kursus_id' => $updatedCourse->id,
            'lokasi_id' => $lokasi->id,
            'kela_id' => $kelas->id,
            'hari_id' => $hari->id,
            'pertemuan_ke' => 2,
            'tgl_pertemuan' => '2026-05-04',
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '10:00:00',
            'created_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.jadwal.update', [$updatedCourse, $jadwalToUpdate]), [
                'pertemuan_ke' => 2,
                'tgl_pertemuan' => '2026-05-04',
                'jam_mulai' => '07:30',
                'jam_selesai' => '08:30',
                'lokasi_id' => $lokasi->id,
                'kela_id' => $kelas->id,
                'hari_id' => $hari->id,
            ])
            ->assertSessionHasErrors('jam_mulai');

        $this->assertDatabaseHas('jadwals', [
            'id' => $jadwalToUpdate->id,
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '10:00:00',
        ]);
    }

    private function createMasterScheduleData(): array
    {
        $hari = Hari::create(['nama' => 'Senin', 'urutan' => 1]);
        $lokasi = Lokasi::create([
            'nama' => 'Gedung A',
            'alamat' => 'Jl. Pendidikan No. 1',
            'kota' => 'Bandung',
        ]);
        $kelas = Kela::create([
            'nama' => 'Ruang 1',
            'kapasitas' => 20,
        ]);

        return [$hari, $lokasi, $kelas];
    }

    private function createCourse(string $name): Kursus
    {
        $program = Program::create(['nama' => $name.' Program']);
        $level = Level::create([
            'program_id' => $program->id,
            'nama' => 'Dasar',
        ]);

        return Kursus::create([
            'program_id' => $program->id,
            'level_id' => $level->id,
            'nama' => $name,
            'periode' => 'Gelombang 1',
            'tanggal_mulai' => '2026-05-04',
            'tanggal_selesai' => '2026-06-04',
            'harga' => 250000,
            'harga_upi' => 200000,
            'kuota' => 15,
            'status' => 'buka',
        ]);
    }
}
