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
use App\Models\Jadwal;
use App\Models\Hari;
use App\Models\Lokasi;
use App\Models\Kela;
use App\Models\Score;
use Illuminate\Database\Seeder;

class ComprehensiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. SEED MASTER DATA FIRST
        $this->seedHari();
        $this->seedLokasi();
        $this->seedKela();

        // 2. SEED MAIN DATA
        $adminUser = $this->seedUsers();
        $instrukturs = $this->seedInstrukturs();
        $programs = $this->seedPrograms();
        $levels = $this->seedLevels($programs);
        $kursusList = $this->seedKursus($instrukturs, $levels);
        $this->seedJadwal($kursusList);
        $pesertas = $this->seedPesertas();
        $pendaftarans = $this->seedPendaftaran($pesertas, $kursusList);
        $this->seedPembayaran($pendaftarans);
        $this->seedRisalah($kursusList);
        $this->seedAbsensi();
        $this->seedScores($pendaftarans, $instrukturs);

        $this->command->info('✅ All seeding completed successfully!');
    }

    // ====== MASTER DATA ======

    private function seedHari(): void
    {
        $haris = [
            ['nama' => 'Senin', 'urutan' => 1],
            ['nama' => 'Selasa', 'urutan' => 2],
            ['nama' => 'Rabu', 'urutan' => 3],
            ['nama' => 'Kamis', 'urutan' => 4],
            ['nama' => 'Jumat', 'urutan' => 5],
            ['nama' => 'Sabtu', 'urutan' => 6],
            ['nama' => 'Minggu', 'urutan' => 7],
        ];

        foreach ($haris as $hari) {
            Hari::firstOrCreate(['nama' => $hari['nama']], $hari);
        }
        $this->command->info('✓ Hari seeded');
    }

    private function seedLokasi(): void
    {
        $lokasis = [
            [
                'nama' => 'Gedung A - Lantai 1',
                'alamat' => 'Jl. Pendidikan No. 123, Bandung',
                'no_telp' => '(022) 2000001',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'keterangan' => 'Ruang utama dengan proyektor dan AC'
            ],
            [
                'nama' => 'Gedung A - Lantai 2',
                'alamat' => 'Jl. Pendidikan No. 123, Bandung',
                'no_telp' => '(022) 2000001',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'keterangan' => 'Ruang pembelajaran dengan meja bundar'
            ],
            [
                'nama' => 'Gedung B - Ruang VIP',
                'alamat' => 'Jl. Pendidikan No. 125, Bandung',
                'no_telp' => '(022) 2000002',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'keterangan' => 'Ruang premium dengan fasilitas lengkap'
            ],
            [
                'nama' => 'Kantor Cabang - Jakarta',
                'alamat' => 'Jl. Gatot Subroto No. 500, Jakarta',
                'no_telp' => '(021) 5555555',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'keterangan' => 'Kantor cabang untuk kursus Jakarta'
            ],
            [
                'nama' => 'Kantor Cabang - Surabaya',
                'alamat' => 'Jl. Raya Ahmad Yani No. 88, Surabaya',
                'no_telp' => '(031) 5999999',
                'kota' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'keterangan' => 'Kantor cabang untuk kursus Surabaya'
            ],
        ];

        foreach ($lokasis as $lokasi) {
            Lokasi::firstOrCreate(['nama' => $lokasi['nama']], $lokasi);
        }
        $this->command->info('✓ Lokasi seeded');
    }

    private function seedKela(): void
    {
        $kelas = [
            [
                'nama' => 'Kelas A-101',
                'kapasitas' => 20,
                'fasilitas' => 'Proyektor, Whiteboard, AC, Meja Individu',
                'keterangan' => 'Ruang kelas standar untuk grup kecil'
            ],
            [
                'nama' => 'Kelas A-102',
                'kapasitas' => 20,
                'fasilitas' => 'Proyektor, Whiteboard, AC, Meja Individu',
                'keterangan' => 'Ruang kelas standar untuk grup kecil'
            ],
            [
                'nama' => 'Kelas A-103',
                'kapasitas' => 25,
                'fasilitas' => 'Proyektor, Whiteboard, AC, Meja Kursi',
                'keterangan' => 'Ruang kelas standar ukuran besar'
            ],
            [
                'nama' => 'Kelas B-201',
                'kapasitas' => 15,
                'fasilitas' => 'Smart TV, Whiteboard Digital, AC, Meja Pc',
                'keterangan' => 'Ruang kelas untuk training dengan teknologi'
            ],
            [
                'nama' => 'Kelas VIP - Premium',
                'kapasitas' => 10,
                'fasilitas' => 'Projector 4K, Soundproofing, AC Premium',
                'keterangan' => 'Ruang premium untuk executive training'
            ],
        ];

        foreach ($kelas as $kela) {
            Kela::firstOrCreate(['nama' => $kela['nama']], $kela);
        }
        $this->command->info('✓ Kelas seeded');
    }

    // ====== MAIN DATA ======

    private function seedUsers()
    {
        return User::firstOrCreate(
            ['email' => 'admin@balai.test'],
            [
                'name' => 'Admin Balai Kursus',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]
        );
    }

    private function seedInstrukturs()
    {
        $names = [
            'Dr. Budi Santoso', 'Ibu Siti Fatimah', 'Ahmad Wijaya',
            'Dewi Lestari', 'Rudi Hermawan', 'Ani Wijayanti',
            'Hendra Kusuma', 'Lina Handoko', 'Bambang Suryanto',
            'Rina Pratama'
        ];

        $spesialisasis = [
            'English Grammar', 'Conversation & Speaking', 'TOEFL Preparation',
            'IELTS Preparation', 'Business English', 'Academic English',
            'Pronunciation', 'Writing Skills', 'Listening Comprehension', 'Vocabulary'
        ];

        foreach ($names as $idx => $name) {
            // Create user first
            $user = User::firstOrCreate(
                ['email' => str_replace(' ', '.', strtolower($name)) . '@balai.test'],
                [
                    'name' => $name,
                    'password' => bcrypt('password'),
                    'role' => 'instruktur'
                ]
            );

            // Then create instruktur
            Instruktur::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'nama_instr' => $name,
                    'spesialisasi' => $spesialisasis[$idx]
                ]
            );
        }

        $this->command->info('✓ Instrukturs seeded (10)');
        return Instruktur::all();
    }

    private function seedPrograms()
    {
        $programs = [
            ['nama' => 'General English'],
            ['nama' => 'Business English'],
            ['nama' => 'Academic English'],
            ['nama' => 'Conversation'],
            ['nama' => 'TOEFL Preparation'],
            ['nama' => 'IELTS Preparation'],
            ['nama' => 'Kids English'],
            ['nama' => 'Online English'],
        ];

        foreach ($programs as $program) {
            Program::firstOrCreate($program);
        }

        $this->command->info('✓ Programs seeded (8)');
        return Program::all();
    }

    private function seedLevels($programs)
    {
        $levelNames = ['Beginner', 'Elementary', 'Intermediate', 'Upper Intermediate', 'Advanced'];

        foreach ($programs as $program) {
            foreach ($levelNames as $name) {
                Level::firstOrCreate(
                    ['program_id' => $program->id, 'nama' => $name],
                    ['program_id' => $program->id, 'nama' => $name]
                );
            }
        }

        $this->command->info('✓ Levels seeded');
        return Level::all();
    }

    private function seedKursus($instrukturs, $levels)
    {
        $kursusList = [];

        for ($i = 0; $i < 20; $i++) {
            $level = $levels->random();
            $kursus = Kursus::create([
                'program_id' => $level->program_id,
                'level_id' => $level->id,
                'instruktur_id' => $instrukturs->random()->id,
                'instruktur_id_2' => rand(0, 1) ? $instrukturs->random()->id : null,
                'nama' => 'Kursus ' . $level->program->nama . ' - ' . $level->nama,
                'periode' => 'Februari 2026',
                'harga' => $this->getRandomHarga(),
                'harga_upi' => $this->getRandomHarga(),
                'kuota' => rand(15, 40),
                'status' => ['buka', 'tutup', 'berjalan'][rand(0, 2)]
            ]);
            $kursusList[] = $kursus;
        }

        $this->command->info('✓ Kursus seeded (20)');
        return $kursusList;
    }

    private function seedJadwal($kursusList)
    {
        $haris = Hari::all();
        $lokasis = Lokasi::all();
        $kelas = Kela::all();

        foreach ($kursusList as $kursus) {
            for ($d = 1; $d <= 4; $d++) {
                Jadwal::create([
                    'kursus_id' => $kursus->id,
                    'lokasi_id' => $lokasis->random()->id,
                    'kela_id' => $kelas->random()->id,
                    'hari_id' => $haris->random()->id,
                    'pertemuan_ke' => $d,
                    'tgl_pertemuan' => now()->addDays($d)->toDateString(),
                    'jam_mulai' => '09:00:00',
                    'jam_selesai' => '11:00:00',
                    'created_by' => 1
                ]);
            }
        }

        $this->command->info('✓ Jadwal seeded');
    }

    private function seedPesertas()
    {
        $firstNames = ['Budi', 'Ahmad', 'Siti', 'Rina', 'Bambang', 'Dewi', 'Rudi', 'Ani', 'Hendra', 'Lina'];
        $lastNames = ['Wijaya', 'Santoso', 'Rahman', 'Kusuma', 'Soekarno', 'Handoko', 'Setiawan', 'Suryanto', 'Pratama', 'Gunawan'];
        $instansis = ['UPI', 'ITB', 'Universitas Indonesia', 'Universitas Bandung', 'Dinas Pendidikan', 'PT Maju Jaya', 'PT Sentosa', null];

        $pesertas = [];

        for ($i = 0; $i < 30; $i++) {
            $name = $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];

            $user = User::firstOrCreate(
                ['email' => 'peserta' . ($i + 1) . '@test.com'],
                [
                    'name' => $name,
                    'password' => bcrypt('password'),
                    'role' => 'peserta'
                ]
            );

            $peserta = Peserta::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nomor_peserta' => 'PS-' . str_pad(($i + 1), 4, '0', STR_PAD_LEFT),
                    'no_hp' => '08' . rand(100000000, 999999999),
                    'instansi' => $instansis[array_rand($instansis)]
                ]
            );

            $pesertas[] = $peserta;
        }

        $this->command->info('✓ Pesertas seeded (30)');
        return $pesertas;
    }

    private function seedPendaftaran($pesertas, $kursusList)
    {
        $pendaftarans = [];
        $pendaftaranNo = 1;

        foreach ($pesertas as $peserta) {
            $numKursus = rand(1, 3);
            $selectedKursus = collect($kursusList)->random($numKursus);

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
                    'nomor' => 'ND-' . str_pad($pendaftaranNo, 5, '0', STR_PAD_LEFT),
                    'peserta_id' => $peserta->id,
                    'kursus_id' => $kursus->id,
                    'status_pembayaran' => $statusPembayaran,
                    'total_bayar' => $totalBayar,
                    'terbayar' => $terbayar
                ]);

                // (explicit legacy fields now stored on `scores` table)

                $pendaftarans[] = $pendaftaran;
                $pendaftaranNo++;
            }
        }

        $this->command->info('✓ Pendaftaran seeded (' . count($pendaftarans) . ')');
        return $pendaftarans;
    }

    private function seedPembayaran($pendaftarans)
    {
        foreach ($pendaftarans as $pendaftaran) {
            $numPayments = rand(1, 3);
            for ($j = 0; $j < $numPayments; $j++) {
                Pembayaran::create([
                    'pendaftaran_id' => $pendaftaran->id,
                    'angsuran_ke' => $j + 1,
                    'jumlah' => rand(150000, 400000),
                    'status' => ['pending', 'verified', 'rejected'][rand(0, 2)],
                    'bukti_path' => rand(0, 1) ? 'bukti/pembayaran_' . uniqid() . '.pdf' : null,
                    'tgl_bayar' => rand(0, 1) ? now()->subDays(rand(1, 30)) : null
                ]);
            }
        }

        $this->command->info('✓ Pembayaran seeded');
    }

    private function seedRisalah($kursusList)
    {
        $materis = [
            'Pengenalan Grammar Dasar', 'Vocabulary: Sehari-hari', 'Conversation: Perkenalan Diri',
            'Listening: Monolog Sederhana', 'Reading: Teks Pendek', 'Writing: Kalimat Sederhana',
            'Tenses: Present Simple', 'Pronunciation: Pelafalan Dasar', 'Phrasal Verbs',
            'Conditional Sentences', 'Relative Clauses', 'Reported Speech'
        ];

        $activeCourses = collect($kursusList)->where('status', 'berjalan')->take(5);

        foreach ($activeCourses as $kursus) {
            for ($i = 1; $i <= 4; $i++) {
                Risalah::create([
                    'kursus_id' => $kursus->id,
                       'instruktur_id' => $kursus->instruktur_id,
                    'pertemuan_ke' => $i,
                    'tgl_pertemuan' => now()->subDays(rand(1, 20))->toDateString(),
                    'materi' => $materis[array_rand($materis)],
                    'catatan' => rand(0, 1) ? 'Peserta sangat antusias mengikuti pembelajaran' : null
                ]);
            }
        }

        $this->command->info('✓ Risalah seeded');
    }

    private function seedAbsensi()
    {
        $risalahs = Risalah::all();
        $pendaftarans = Pendaftaran::all();

        $count = 0;
        foreach ($risalahs->take(10) as $risalah) {
            $relevantPendaftarans = $pendaftarans->where('kursus_id', $risalah->kursus_id)->take(8);
            foreach ($relevantPendaftarans as $pendaftaran) {
                try {
                    Absensi::create([
                        'risalah_id' => $risalah->id,
                        'pendaftaran_id' => $pendaftaran->id,
                        'status' => ['H', 'S', 'I', 'A'][rand(0, 3)],
                        'jam_datang' => rand(0, 1) ? now()->format('H:i:s') : null,
                        'catatan' => rand(0, 1) ? 'Peserta terlambat' : null
                    ]);
                    $count++;
                } catch (\Exception $e) {
                    // Skip duplicate
                }
            }
        }

        $this->command->info('✓ Absensi seeded (' . $count . ')');
    }

    private function seedScores($pendaftarans, $instrukturs)
    {
        $feedbacks = [
            'Peserta menunjukkan progres yang baik',
            'Perlu perbaikan dalam writing',
            'Sangat responsif dan aktif',
            'Nilai bagus, terus tingkatkan',
            'Kerja bagus! Sudah siap level berikutnya',
            'Excellent progress!',
            'Cukup baik, perlu lebih konsisten',
            'Menunjukkan potensi yang besar',
        ];

        $count = 0;
        $randomCount = min(50, count($pendaftarans));
        foreach (collect($pendaftarans)->random($randomCount) as $pendaftaran) {
            if (Score::where('pendaftaran_id', $pendaftaran->id)->exists()) {
                continue;
            }

            Score::create([
                'pendaftaran_id' => $pendaftaran->id,
                'listening' => rand(40, 100),
                'speaking' => rand(40, 100),
                'reading' => rand(40, 100),
                'writing' => rand(40, 100),
                'assignment' => rand(40, 100),
                // explicit legacy fields
                'uktp' => rand(0, 100),
                'ukap' => rand(0, 100),
                'var1' => (string) rand(0, 100),
                'var2' => (string) rand(0, 100),
                'var3' => (string) rand(0, 100),
                'var4' => (string) rand(0, 100),
                'final_score' => round(rand(40, 100) / 10, 1) * 10,
                'status' => ['pass', 'fail', 'pending'][rand(0, 2)],
                'evaluated_by' => $instrukturs->random()->id,
                'evaluated_at' => now()->subDays(rand(0, 30))->toDateString(),
                'keterangan' => $feedbacks[array_rand($feedbacks)]
            ]);
            $count++;
        }

        $this->command->info('✓ Scores seeded (' . $count . ')');
    }

    // ====== HELPERS ======

    private function getRandomHarga(): int
    {
        return [500000, 750000, 1000000, 1500000, 2000000][rand(0, 4)];
    }
}
