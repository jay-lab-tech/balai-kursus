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
            ['nama' => 'Elementary', 'urutan' => 2, 'nilai_min' => 40, 'nilai_max' => 54.99, 'deskripsi' => 'Peserta sudah mengenal pola kalimat sederhana.'],
            ['nama' => 'Intermediate', 'urutan' => 3, 'nilai_min' => 55, 'nilai_max' => 69.99, 'deskripsi' => 'Peserta sudah cukup aktif berkomunikasi pada topik umum.'],
            ['nama' => 'Upper Intermediate', 'urutan' => 4, 'nilai_min' => 70, 'nilai_max' => 84.99, 'deskripsi' => 'Peserta mampu memahami materi menengah ke atas.'],
            ['nama' => 'Advanced', 'urutan' => 5, 'nilai_min' => 85, 'nilai_max' => 100, 'deskripsi' => 'Peserta siap masuk kelas lanjutan dan persiapan tes intensif.'],
        ];

        foreach ($levels as $level) {
            Level::updateOrCreate(['nama' => $level['nama']], $level);
        }
    }
}
