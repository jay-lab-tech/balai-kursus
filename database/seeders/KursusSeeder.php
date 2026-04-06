<?php

namespace Database\Seeders;

use App\Models\Instruktur;
use App\Models\InstrukturKursusLevel;
use App\Models\Kursus;
use App\Models\Level;
use App\Models\Program;
use Illuminate\Database\Seeder;

class KursusSeeder extends Seeder
{
    public function run(): void
    {
        $programs = Program::orderBy('id')->get()->values();
        $levels = Level::ordered()->get()->values();
        $instrukturs = Instruktur::orderBy('id')->get()->values();

        if ($programs->isEmpty() || $levels->isEmpty() || $instrukturs->isEmpty()) {
            return;
        }

        foreach ($programs as $programIndex => $program) {
            foreach ($levels as $levelIndex => $level) {
                foreach (range(1, 2) as $classNumber) {
                    $tanggalMulai = now()->startOfDay()->addDays(($programIndex * 5) + ($levelIndex * 7) + (($classNumber - 1) * 3));

                    $kursus = Kursus::updateOrCreate([
                        'nama' => $program->nama . ' - ' . $level->nama . ' Kelas ' . $classNumber,
                    ], [
                        'program_id' => $program->id,
                        'level_id' => $level->id,
                        'periode' => 'Gelombang ' . $classNumber . ' 2026',
                        'tanggal_mulai' => $tanggalMulai,
                        'tanggal_selesai' => $tanggalMulai->copy()->addDays(56),
                        'harga' => 1250000 + (($level->urutan - 1) * 150000),
                        'harga_upi' => 1100000 + (($level->urutan - 1) * 150000),
                        'kuota' => 16 + $classNumber,
                        'status' => $classNumber === 1 ? 'berjalan' : 'buka',
                    ]);

                    $primaryInstructor = $instrukturs[($programIndex + $levelIndex + $classNumber - 1) % $instrukturs->count()];
                    $secondaryInstructor = $instrukturs[($programIndex + $levelIndex + $classNumber) % $instrukturs->count()];

                    InstrukturKursusLevel::updateOrCreate([
                        'instruktur_id' => $primaryInstructor->id,
                        'kursus_id' => $kursus->id,
                        'level_id' => $level->id,
                    ], [
                        'assigned_at' => $tanggalMulai->copy()->subDays(10),
                    ]);

                    if ($secondaryInstructor->id !== $primaryInstructor->id) {
                        InstrukturKursusLevel::updateOrCreate([
                            'instruktur_id' => $secondaryInstructor->id,
                            'kursus_id' => $kursus->id,
                            'level_id' => $level->id,
                        ], [
                            'assigned_at' => $tanggalMulai->copy()->subDays(7),
                        ]);
                    }
                }
            }
        }
    }
}
