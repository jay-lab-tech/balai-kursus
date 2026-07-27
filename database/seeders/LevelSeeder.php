<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['nama' => 'Beginner', 'urutan' => 1, 'nilai_min' => 0, 'nilai_max' => 39.99, 'deskripsi' => 'Peserta baru mulai belajar dan masih perlu fondasi dasar.'],
            ['nama' => 'Intermediate', 'urutan' => 2, 'nilai_min' => 40, 'nilai_max' => 100, 'deskripsi' => 'Peserta sudah cukup aktif berkomunikasi pada topik umum.'],
        ];

        foreach ($levels as $level) {
            Level::updateOrCreate(['nama' => $level['nama']], $level);
        }
    }
}
