<?php

namespace Database\Seeders;

use App\Models\Instruktur;
use App\Models\Pendaftaran;
use App\Models\Score;
use Illuminate\Database\Seeder;

class ScoreSeeder extends Seeder
{
    public function run(): void
    {
        $pendaftarans = Pendaftaran::with('program')->orderBy('id')->get()->values();
        $instrukturs = Instruktur::orderBy('id')->get()->values();

        if ($pendaftarans->isEmpty() || $instrukturs->isEmpty()) {
            return;
        }

        $placementScores = [28, 36, 44, 53, 61, 68, 75, 83, 91];

        foreach ($pendaftarans as $index => $pendaftaran) {
            if ($index % 6 === 0) {
                continue;
            }

            $nilaiAkhir = $placementScores[$index % count($placementScores)];
            $instruktur = $instrukturs[$index % $instrukturs->count()];

            Score::updateOrCreate([
                'pendaftaran_id' => $pendaftaran->id,
                'jenis' => Score::TYPE_PLACEMENT,
            ], [
                'listening' => $nilaiAkhir,
                'speaking' => min(100, $nilaiAkhir + 3),
                'reading' => min(100, $nilaiAkhir + 5),
                'writing' => max(0, $nilaiAkhir - 2),
                'assignment' => $nilaiAkhir,
                'uktp' => $nilaiAkhir,
                'ukap' => min(100, $nilaiAkhir + 4),
                'var1' => (string) max(0, $nilaiAkhir - 1),
                'var2' => (string) $nilaiAkhir,
                'var3' => (string) min(100, $nilaiAkhir + 1),
                'var4' => (string) min(100, $nilaiAkhir + 2),
                'final_score' => $nilaiAkhir,
                'status' => $nilaiAkhir >= 55 ? 'pass' : 'pending',
                'evaluated_by' => $instruktur->id,
                'evaluated_at' => now()->subDays(($index % 5) + 1),
                'keterangan' => 'Hasil tes penempatan seed untuk ' . ($pendaftaran->program->nama ?? 'program'),
            ]);
        }

        $assignedPendaftarans = Pendaftaran::with('kursus')->whereNotNull('kursus_id')->orderBy('id')->get()->values();

        foreach ($assignedPendaftarans as $index => $pendaftaran) {
            if ($index % 4 === 3) {
                continue;
            }

            $nilaiAkhir = 72 + (($index % 5) * 5);
            $instruktur = $instrukturs[($index + 1) % $instrukturs->count()];

            Score::updateOrCreate([
                'pendaftaran_id' => $pendaftaran->id,
                'jenis' => Score::TYPE_COURSE,
            ], [
                'listening' => min(100, $nilaiAkhir + 2),
                'speaking' => min(100, $nilaiAkhir + 1),
                'reading' => $nilaiAkhir,
                'writing' => max(0, $nilaiAkhir - 3),
                'assignment' => min(100, $nilaiAkhir + 4),
                'uktp' => $nilaiAkhir,
                'ukap' => min(100, $nilaiAkhir + 3),
                'var1' => (string) $nilaiAkhir,
                'var2' => (string) min(100, $nilaiAkhir + 1),
                'var3' => (string) min(100, $nilaiAkhir + 2),
                'var4' => (string) min(100, $nilaiAkhir + 3),
                'final_score' => $nilaiAkhir,
                'status' => $nilaiAkhir >= 75 ? 'pass' : 'pending',
                'evaluated_by' => $instruktur->id,
                'evaluated_at' => now()->subDays($index % 3),
                'keterangan' => 'Nilai kelas seed untuk ' . ($pendaftaran->kursus->nama ?? 'kelas'),
            ]);
        }
    }
}
