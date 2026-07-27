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
