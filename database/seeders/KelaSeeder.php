<?php

namespace Database\Seeders;

use App\Models\Kela;
use Illuminate\Database\Seeder;

class KelaSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            [
                'nama' => 'Ruang Anggrek',
                'kapasitas' => 18,
                'fasilitas' => 'AC, Proyektor, Whiteboard, Audio',
                'keterangan' => 'Ruang standar untuk kelas reguler.',
            ],
            [
                'nama' => 'Ruang Melati',
                'kapasitas' => 20,
                'fasilitas' => 'AC, Smart TV, Whiteboard',
                'keterangan' => 'Cocok untuk speaking class dan presentasi.',
            ],
            [
                'nama' => 'Ruang Cendana',
                'kapasitas' => 16,
                'fasilitas' => 'AC, Whiteboard, U-shape table',
                'keterangan' => 'Dipakai untuk kelas diskusi intensif.',
            ],
            [
                'nama' => 'Laboratorium Bahasa 1',
                'kapasitas' => 24,
                'fasilitas' => 'Headset, Komputer, Smart TV, AC',
                'keterangan' => 'Untuk listening dan simulasi tes.',
            ],
            [
                'nama' => 'Executive Class',
                'kapasitas' => 12,
                'fasilitas' => 'AC, LED Display, Coffee Corner',
                'keterangan' => 'Untuk kelas privat dan corporate.',
            ],
        ])->each(function (array $kelas) {
            Kela::updateOrCreate(
                ['nama' => $kelas['nama']],
                $kelas
            );
        });
    }
}
