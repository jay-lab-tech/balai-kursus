<?php

namespace Database\Seeders;

use App\Models\Peserta;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PesertaSeeder extends Seeder
{
    public function run(): void
    {
        $pesertas = [
            ['name' => 'Alya Putri', 'email' => 'peserta1@balai.test', 'nomor_peserta' => 'PS-0001', 'no_hp' => '081200000001', 'instansi' => 'UPI'],
            ['name' => 'Farhan Akbar', 'email' => 'peserta2@balai.test', 'nomor_peserta' => 'PS-0002', 'no_hp' => '081200000002', 'instansi' => 'ITB'],
            ['name' => 'Nabila Sari', 'email' => 'peserta3@balai.test', 'nomor_peserta' => 'PS-0003', 'no_hp' => '081200000003', 'instansi' => 'Telkom University'],
            ['name' => 'Raka Pratama', 'email' => 'peserta4@balai.test', 'nomor_peserta' => 'PS-0004', 'no_hp' => '081200000004', 'instansi' => 'Universitas Pendidikan Indonesia'],
            ['name' => 'Citra Maharani', 'email' => 'peserta5@balai.test', 'nomor_peserta' => 'PS-0005', 'no_hp' => '081200000005', 'instansi' => 'SMA Negeri 3 Bandung'],
            ['name' => 'Dimas Saputra', 'email' => 'peserta6@balai.test', 'nomor_peserta' => 'PS-0006', 'no_hp' => '081200000006', 'instansi' => 'PT Nusantara Maju'],
            ['name' => 'Intan Ramadhani', 'email' => 'peserta7@balai.test', 'nomor_peserta' => 'PS-0007', 'no_hp' => '081200000007', 'instansi' => 'Universitas Padjadjaran'],
            ['name' => 'Bagas Wicaksono', 'email' => 'peserta8@balai.test', 'nomor_peserta' => 'PS-0008', 'no_hp' => '081200000008', 'instansi' => 'SMK Negeri 1 Cimahi'],
            ['name' => 'Salsa Anindita', 'email' => 'peserta9@balai.test', 'nomor_peserta' => 'PS-0009', 'no_hp' => '081200000009', 'instansi' => 'Politeknik Negeri Bandung'],
            ['name' => 'Yoga Permana', 'email' => 'peserta10@balai.test', 'nomor_peserta' => 'PS-0010', 'no_hp' => '081200000010', 'instansi' => 'PT Inovasi Digital'],
            ['name' => 'Mira Febriani', 'email' => 'peserta11@balai.test', 'nomor_peserta' => 'PS-0011', 'no_hp' => '081200000011', 'instansi' => 'Universitas Indonesia'],
            ['name' => 'Rizky Kurniawan', 'email' => 'peserta12@balai.test', 'nomor_peserta' => 'PS-0012', 'no_hp' => '081200000012', 'instansi' => 'Dinas Pendidikan Kota Bandung'],
        ];

        foreach ($pesertas as $pesertaData) {
            $user = User::updateOrCreate(
                ['email' => $pesertaData['email']],
                [
                    'name' => $pesertaData['name'],
                    'password' => Hash::make('password'),
                    'role' => 'peserta',
                    'email_verified_at' => now(),
                ]
            );

            Peserta::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nomor_peserta' => $pesertaData['nomor_peserta'],
                    'no_hp' => $pesertaData['no_hp'],
                    'instansi' => $pesertaData['instansi'],
                ]
            );
        }
    }
}
