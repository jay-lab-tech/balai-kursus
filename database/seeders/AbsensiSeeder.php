<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Risalah;
use Illuminate\Database\Seeder;

class AbsensiSeeder extends Seeder
{
    public function run(): void
    {
        $statusCycle = ['H', 'H', 'H', 'S', 'I', 'A'];
        $risalahs = Risalah::with('kursus.pendaftarans')->orderBy('id')->get();

        foreach ($risalahs as $risalah) {
            $pendaftarans = $risalah->kursus->pendaftarans()->orderBy('id')->get();

            foreach ($pendaftarans as $index => $pendaftaran) {
                $status = $statusCycle[$index % count($statusCycle)];

                Absensi::updateOrCreate([
                    'risalah_id' => $risalah->id,
                    'pendaftaran_id' => $pendaftaran->id,
                ], [
                    'status' => $status,
                    'jam_datang' => $status === 'H' ? '08:55:00' : null,
                    'catatan' => $status === 'A' ? 'Tidak hadir pada pertemuan ini.' : null,
                ]);
            }
        }
    }
}
