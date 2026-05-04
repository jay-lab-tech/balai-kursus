<?php

namespace Tests\Feature;

use App\Models\Jadwal;
use Database\Seeders\AdminSeeder;
use Database\Seeders\HariSeeder;
use Database\Seeders\InstrukturSeeder;
use Database\Seeders\JadwalSeeder;
use Database\Seeders\KelaSeeder;
use Database\Seeders\KursusSeeder;
use Database\Seeders\LevelSeeder;
use Database\Seeders\LokasiSeeder;
use Database\Seeders\ProgramSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JadwalSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_jadwal_seeder_generates_non_overlapping_slots_per_location_and_date(): void
    {
        $this->seed([
            AdminSeeder::class,
            HariSeeder::class,
            LokasiSeeder::class,
            KelaSeeder::class,
            ProgramSeeder::class,
            LevelSeeder::class,
            InstrukturSeeder::class,
            KursusSeeder::class,
            JadwalSeeder::class,
        ]);

        $jadwals = Jadwal::with('hari')
            ->orderBy('lokasi_id')
            ->orderBy('tgl_pertemuan')
            ->orderBy('jam_mulai')
            ->get();

        $this->assertNotEmpty($jadwals);

        foreach ($jadwals->groupBy(fn (Jadwal $jadwal) => $jadwal->lokasi_id . '|' . $jadwal->tgl_pertemuan->toDateString()) as $key => $group) {
            $ordered = $group->sortBy('jam_mulai')->values();

            for ($index = 1; $index < $ordered->count(); $index++) {
                $previous = $ordered[$index - 1];
                $current = $ordered[$index];

                $this->assertTrue(
                    $previous->jam_selesai <= $current->jam_mulai,
                    "Bentrok terdeteksi pada slot {$key}: {$previous->jam_mulai}-{$previous->jam_selesai} bertabrakan dengan {$current->jam_mulai}-{$current->jam_selesai}."
                );
            }
        }

        foreach ($jadwals as $jadwal) {
            $this->assertSame(
                $jadwal->tgl_pertemuan->dayOfWeekIso,
                $jadwal->hari?->urutan,
                "Hari jadwal {$jadwal->id} tidak sesuai dengan tanggal pertemuan."
            );
        }
    }
}
