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
            [
                'nama' => 'Cabang Dago',
                'alamat' => 'Jl. Ir. H. Juanda No. 88, Bandung',
                'no_telp' => '(022) 2010002',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'keterangan' => 'Fokus untuk kelas sore dan weekend.',
            ],
            [
                'nama' => 'Cabang Jakarta Selatan',
                'alamat' => 'Jl. Fatmawati No. 17, Jakarta Selatan',
                'no_telp' => '(021) 7001003',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'keterangan' => 'Lokasi untuk corporate training dan privat.',
            ],
            [
                'nama' => 'Cabang Cimahi',
                'alamat' => 'Jl. HMS Mintaredja No. 45, Cimahi',
                'no_telp' => '(022) 2010004',
                'kota' => 'Cimahi',
                'provinsi' => 'Jawa Barat',
                'keterangan' => 'Digunakan untuk kelas remaja dan persiapan tes.',
            ],
        ])->each(function (array $lokasi) {
            Lokasi::updateOrCreate(
                ['nama' => $lokasi['nama']],
                $lokasi
            );
        });
    }
}
