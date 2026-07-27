<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            [
                'nama' => 'Kampus Utama Bandung',
                'alamat' => 'Jl. Setiabudi No. 201, Bandung',
                'no_telp' => '(022) 2010001',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'keterangan' => 'Cabang utama untuk kelas reguler dan intensif.',
            ],
        ])->each(function (array $lokasi) {
            Lokasi::updateOrCreate(
                ['nama' => $lokasi['nama']],
                $lokasi
            );
        });
    }
}
