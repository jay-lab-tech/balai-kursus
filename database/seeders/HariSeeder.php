<?php

namespace Database\Seeders;

use App\Models\Hari;
use Illuminate\Database\Seeder;

class HariSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['nama' => 'Senin', 'urutan' => 1],
            ['nama' => 'Selasa', 'urutan' => 2],
            ['nama' => 'Rabu', 'urutan' => 3],
            ['nama' => 'Kamis', 'urutan' => 4],
            ['nama' => 'Jumat', 'urutan' => 5],
            ['nama' => 'Sabtu', 'urutan' => 6],
            ['nama' => 'Minggu', 'urutan' => 7],
        ])->each(function (array $hari) {
            Hari::updateOrCreate(
                ['nama' => $hari['nama']],
                ['urutan' => $hari['urutan']]
            );
        });
    }
}
