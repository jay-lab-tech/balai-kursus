<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ComprehensiveSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            HariSeeder::class,
            LokasiSeeder::class,
            KelaSeeder::class,
            ProgramSeeder::class,
            LevelSeeder::class,
            InstrukturSeeder::class,
            KursusSeeder::class,
            PesertaSeeder::class,
            PendaftaranSeeder::class,
            ScoreSeeder::class,
            JadwalSeeder::class,
            RisalahSeeder::class,
            AbsensiSeeder::class,
            PesertaKursusLevelSeeder::class,
            PesertaKursusSeeder::class,
            CertificateSeeder::class,
        ]);
    }
}
