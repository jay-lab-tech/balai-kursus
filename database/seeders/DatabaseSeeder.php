<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\Peserta;
use App\Models\Instruktur;
use App\Models\Program;
use App\Models\Level;
use App\Models\Kursus;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ADMIN
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@balai.test',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);

    // PESERTA
    $userPeserta = User::create([
        'name' => 'Budi',
        'email' => 'budi@test.com',
        'password' => bcrypt('password'),
        'role' => 'peserta'
    ]);

    $peserta = Peserta::create([
        'user_id' => $userPeserta->id,
        'nomor_peserta' => 'PS-001',
        'no_hp' => '08123456789',
        'instansi' => 'UPI'
    ]);

    // INSTRUKTUR
    $userInstruktur = User::create([
        'name' => 'Sari',
        'email' => 'sari@test.com',
        'password' => bcrypt('password'),
        'role' => 'instruktur'
    ]);

    $instruktur = Instruktur::create([
        'user_id' => $userInstruktur->id,
        'nama_instr' => 'Sari Wijaya',
        'spesialisasi' => 'TOEFL'
    ]);

    // PROGRAM
    $program = Program::create([
        'nama' => 'General English'
    ]);

    // LEVEL
    $level = Level::create([
        'program_id' => $program->id,
        'nama' => 'Beginner'
    ]);

    // KURSUS
    $kursus = Kursus::create([
        'program_id' => $program->id,
        'level_id' => $level->id,
        'instruktur_id' => $instruktur->id,
        'nama' => 'GE Beginner Batch 1',
        'harga' => 715000,
        'kuota' => 20,
        'status' => 'buka'
    ]);

    // PENDAFTARAN
    $pendaftaran = Pendaftaran::create([
        'peserta_id' => $peserta->id,
        'kursus_id' => $kursus->id,
        'status_pembayaran' => 'dp',
        'total_bayar' => 715000,
        'terbayar' => 215000
    ]);

    // PEMBAYARAN
    Pembayaran::create([
        'pendaftaran_id' => $pendaftaran->id,
        'angsuran_ke' => 1,
        'jumlah' => 215000,
        'status' => 'verified'
    ]);
    }
}
