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
        ])->each(function (array $kelas) {
            Kela::updateOrCreate(
                ['nama' => $kelas['nama']],
                $kelas
            );
        });
    }
}
