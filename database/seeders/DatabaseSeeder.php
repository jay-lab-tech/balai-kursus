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
use App\Models\Risalah;
use App\Models\Absensi;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User (idempotent)
        User::firstOrCreate(
            ['email' => 'admin@balai.test'],
            [
                'name' => 'Admin Balai Kursus',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]
        );

        // Create 10 Instruktur (Teachers)
        $instrukturs = Instruktur::factory(10)->create();

        // Create 8 Programs
        $programs = Program::factory(8)->create();

        // Create 16 Levels (2 levels per program)
        foreach ($programs as $program) {
            Level::factory(2)->create(['program_id' => $program->id]);
        }

        // Get all levels for kursus creation
        $levels = Level::all();

        // Create 12 Kursus (Courses)
        $kursusList = [];
        for ($i = 0; $i < 12; $i++) {
            $level = $levels->random();
            $kursus = Kursus::create([
                'program_id' => $level->program_id,
                'level_id' => $level->id,
                'instruktur_id' => $instrukturs->random()->id,
                'nama' => $this->generateKursusName($level),
                'harga' => $this->getRandomHarga(),
                'kuota' => rand(15, 40),
                'status' => $this->getRandomStatus()
            ]);
            $kursusList[] = $kursus;
        }

        // Create 15 Peserta (Students) with registrations and payments
        $kursusByStatus = collect($kursusList);
        for ($i = 0; $i < 15; $i++) {
            $user = User::create([
                'name' => $this->generateName(),
                'email' => 'peserta' . ($i + 1) . '@test.com',
                'password' => bcrypt('password'),
                'role' => 'peserta'
            ]);

            $peserta = Peserta::create([
                'user_id' => $user->id,
                'nomor_peserta' => 'PS-' . str_pad(($i + 1), 4, '0', STR_PAD_LEFT),
                'no_hp' => '08' . rand(100000000, 999999999),
                'instansi' => $this->getRandomInstansi()
            ]);

            // Register to 1-2 courses
            $numKursus = rand(1, 2);
            $selectedKursus = $kursusByStatus->random($numKursus);

            foreach ($selectedKursus as $kursus) {
                $statusPembayaran = ['pending', 'dp', 'cicil', 'lunas'][rand(0, 3)];
                $totalBayar = $kursus->harga;
                
                $terbayar = match ($statusPembayaran) {
                    'pending' => 0,
                    'dp' => (int)($totalBayar * 0.3),
                    'cicil' => (int)($totalBayar * 0.7),
                    'lunas' => $totalBayar,
                    default => 0
                };

                $pendaftaran = Pendaftaran::create([
                    'peserta_id' => $peserta->id,
                    'kursus_id' => $kursus->id,
                    'status_pembayaran' => $statusPembayaran,
                    'total_bayar' => $totalBayar,
                    'terbayar' => $terbayar
                ]);

                // Create 1-3 payments per registration
                $numPayments = rand(1, 3);
                for ($j = 0; $j < $numPayments; $j++) {
                    Pembayaran::create([
                        'pendaftaran_id' => $pendaftaran->id,
                        'angsuran_ke' => $j + 1,
                        'jumlah' => rand(150000, 400000),
                        'status' => ['pending', 'verified', 'rejected'][rand(0, 2)],
                        'bukti_path' => (rand(0, 1) == 1) ? 'bukti/pembayaran_' . uniqid() . '.pdf' : null,
                        'tgl_bayar' => (rand(0, 1) == 1) ? now()->subDays(rand(1, 30)) : null
                    ]);
                }
            }
        }

        // Create 5 Risalah (Meeting notes) per active course
        $activeCourses = collect($kursusList)->where('status', 'berjalan')->take(3);
        foreach ($activeCourses as $kursus) {
            for ($i = 1; $i <= 5; $i++) {
                Risalah::create([
                    'kursus_id' => $kursus->id,
                    'instruktur_id' => $kursus->instruktur_id,
                    'pertemuan_ke' => $i,
                    'tgl_pertemuan' => now()->subDays(rand(1, 20)),
                    'materi' => $this->generateMateri(),
                    'catatan' => (rand(0, 1) == 1) ? 'Peserta sangat antusias mengikuti pembelajaran' : null
                ]);
            }
        }

        // Create Absensi data
        $risalahs = Risalah::all();
        $pendaftarans = Pendaftaran::all();

        foreach ($risalahs->take(15) as $risalah) {
            $relevantPendaftarans = $pendaftarans->where('kursus_id', $risalah->kursus_id)->take(10);
            foreach ($relevantPendaftarans as $pendaftaran) {
                try {
                    Absensi::create([
                        'risalah_id' => $risalah->id,
                        'pendaftaran_id' => $pendaftaran->id,
                        'status' => ['H', 'S', 'I', 'A'][rand(0, 3)],
                        'jam_datang' => (rand(0, 1) == 1) ? now()->format('H:i:s') : null,
                        'catatan' => (rand(0, 1) == 1) ? 'Peserta terlambat 5 menit' : null
                    ]);
                } catch (\Exception $e) {
                    // Ignore duplicate unique constraint errors
                }
            }
        }
    }

    private function generateKursusName($level): string
    {
        $programs = ['General English', 'Business English', 'Academic English', 'Conversation'];
        $batches = ['Batch 1', 'Batch 2', 'Batch 3'];
        return $programs[array_rand($programs)] . ' - ' . $level->nama . ' - ' . $batches[array_rand($batches)];
    }

    private function getRandomHarga(): int
    {
        return [500000, 750000, 1000000, 1500000, 2000000][rand(0, 4)];
    }

    private function getRandomStatus(): string
    {
        return ['buka', 'tutup', 'berjalan'][rand(0, 2)];
    }

    private function generateName(): string
    {
        $firstNames = ['Budi', 'Ahmad', 'Siti', 'Rina', 'Bambang', 'Dewi', 'Rudi', 'Ani', 'Hendra', 'Lina'];
        $lastNames = ['Wijaya', 'Santoso', 'Rahman', 'Kusuma', 'Soekarno', 'Handoko', 'Setiawan', 'Suryanto', 'Pratama', 'Gunawan'];
        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    private function getRandomInstansi(): ?string
    {
        $instansis = ['UPI', 'ITB', 'Universitas Indonesia', 'Universitas Bandung', 'Dinas Pendidikan', 'PT Maju Jaya', 'PT Sentosa', 'SMA Negeri 1', 'SMA Swasta', null];
        return $instansis[array_rand($instansis)];
    }

    private function generateMateri(): string
    {
        $materis = [
            'Pengenalan Grammar Dasar',
            'Vocabulary: Sehari-hari',
            'Conversation: Perkenalan Diri',
            'Listening: Monolog Sederhana',
            'Reading: Teks Pendek',
            'Writing: Kalimat Sederhana',
            'Tenses: Present Simple',
            'Pronunciation: Pelafalan Dasar',
            'Phrasal Verbs',
            'Conditional Sentences',
            'Relative Clauses',
            'Reported Speech',
            'Business Vocabulary',
            'Formal Email Writing',
            'Presentation Skills'
        ];
        return $materis[array_rand($materis)];
    }
}
