<?php

namespace Database\Seeders;

use App\Models\Pendaftaran;
use App\Models\PesertaKursusLevel;
use Illuminate\Database\Seeder;

class PesertaKursusLevelSeeder extends Seeder
{
    public function run(): void
    {
        $pendaftarans = Pendaftaran::whereNotNull('kursus_id')
            ->whereNotNull('level_id')
            ->get();

        foreach ($pendaftarans as $pendaftaran) {
            PesertaKursusLevel::updateOrCreate([
                'peserta_id' => $pendaftaran->peserta_id,
                'kursus_id' => $pendaftaran->kursus_id,
            ], [
                'level_id' => $pendaftaran->level_id,
                'assigned_at' => $pendaftaran->diklasifikasikan_at ?? now(),
            ]);
        }
    }
}
