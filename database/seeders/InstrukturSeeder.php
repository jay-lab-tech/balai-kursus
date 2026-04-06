<?php

namespace Database\Seeders;

use App\Models\Instruktur;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InstrukturSeeder extends Seeder
{
    public function run(): void
    {
        $instrukturs = [
            ['name' => 'Budi Santoso', 'email' => 'instruktur1@balai.test', 'spesialisasi' => 'General English'],
            ['name' => 'Siti Amelia', 'email' => 'instruktur2@balai.test', 'spesialisasi' => 'Conversation'],
            ['name' => 'Rian Saputra', 'email' => 'instruktur3@balai.test', 'spesialisasi' => 'TOEFL Preparation'],
            ['name' => 'Dewi Maharani', 'email' => 'instruktur4@balai.test', 'spesialisasi' => 'Academic Writing'],
            ['name' => 'Hendra Kusuma', 'email' => 'instruktur5@balai.test', 'spesialisasi' => 'Business English'],
            ['name' => 'Nadia Lestari', 'email' => 'instruktur6@balai.test', 'spesialisasi' => 'Pronunciation'],
        ];

        foreach ($instrukturs as $index => $instrukturData) {
            $user = User::updateOrCreate(
                ['email' => $instrukturData['email']],
                [
                    'name' => $instrukturData['name'],
                    'password' => Hash::make('password'),
                    'role' => 'instruktur',
                    'email_verified_at' => now(),
                ]
            );

            Instruktur::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_instr' => $instrukturData['name'],
                    'spesialisasi' => $instrukturData['spesialisasi'] . ' Batch ' . ($index + 1),
                ]
            );
        }
    }
}
