<?php

namespace App\Services;

use App\Models\Kursus;
use App\Models\Level;
use App\Models\Pendaftaran;
use App\Models\PesertaKursusLevel;
use App\Models\Score;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PendaftaranPlacementService
{
    public function placeFromScore(Score $score): array
    {
        $pendaftaran = $score->pendaftaran()->with(['program', 'level', 'kursus'])->first();

        if (! $pendaftaran || $score->jenis !== Score::TYPE_PLACEMENT || $score->final_score === null) {
            return [
                'level' => null,
                'kursus' => null,
                'message' => 'Belum ada hasil tes penempatan yang bisa diproses.',
            ];
        }

        $levels = $this->availableLevelsForProgram($pendaftaran->program_id);
        $level = $this->resolveLevelFromScore($levels, (float) $score->final_score);

        return DB::transaction(function () use ($pendaftaran, $level) {
            $previousKursusId = $pendaftaran->kursus_id;
            $selectedKursus = $level ? $this->findAvailableClass($pendaftaran->program_id, $level->id) : null;

            if ($previousKursusId && $previousKursusId !== optional($selectedKursus)->id) {
                PesertaKursusLevel::where('peserta_id', $pendaftaran->peserta_id)
                    ->where('kursus_id', $previousKursusId)
                    ->delete();
            }

            $pendaftaran->fill([
                'level_id' => $level?->id,
                'kursus_id' => $selectedKursus?->id,
                'status_pendaftaran' => $selectedKursus ? Pendaftaran::STATUS_MENUNGGU_PEMBAYARAN : Pendaftaran::STATUS_MENUNGGU_PENEMPATAN,
                'status_pembayaran' => Pendaftaran::PAYMENT_PENDING,
                'total_bayar' => $selectedKursus?->harga ?? 0,
                'terbayar' => $selectedKursus ? min((int) $pendaftaran->terbayar, (int) ($selectedKursus->harga ?? 0)) : 0,
                'diklasifikasikan_at' => now(),
            ]);
            $pendaftaran->save();

            if ($level && $selectedKursus) {
                PesertaKursusLevel::updateOrCreate(
                    [
                        'peserta_id' => $pendaftaran->peserta_id,
                        'kursus_id' => $selectedKursus->id,
                    ],
                    [
                        'level_id' => $level->id,
                        'assigned_at' => now(),
                    ]
                );
            }

            return [
                'level' => $level,
                'kursus' => $selectedKursus,
                'message' => $selectedKursus
                    ? 'Peserta berhasil diklasifikasikan ke level '.$level->nama.' dan ditempatkan ke kelas '.$selectedKursus->nama.'.'
                    : ($level
                        ? 'Peserta cocok ke level '.$level->nama.', tetapi semua kelas pada level tersebut sedang penuh.'
                        : 'Sistem belum menemukan level yang cocok untuk program ini.'),
            ];
        });
    }

    public function resetPlacement(Pendaftaran $pendaftaran): void
    {
        DB::transaction(function () use ($pendaftaran) {
            if ($pendaftaran->kursus_id) {
                PesertaKursusLevel::where('peserta_id', $pendaftaran->peserta_id)
                    ->where('kursus_id', $pendaftaran->kursus_id)
                    ->delete();
            }

            $pendaftaran->update([
                'level_id' => null,
                'kursus_id' => null,
                'status_pendaftaran' => Pendaftaran::STATUS_MENUNGGU_TES,
                'status_pembayaran' => Pendaftaran::PAYMENT_PENDING,
                'total_bayar' => 0,
                'terbayar' => 0,
                'diklasifikasikan_at' => null,
            ]);
        });
    }

    private function availableLevelsForProgram(?int $programId): Collection
    {
        if (! $programId) {
            return collect();
        }

        return Level::query()
            ->whereHas('kursuses', function ($query) use ($programId) {
                $query->where('program_id', $programId);
            })
            ->ordered()
            ->get();
    }

    private function resolveLevelFromScore(Collection $levels, float $finalScore): ?Level
    {
        if ($levels->isEmpty()) {
            return null;
        }

        $matched = $levels->first(function (Level $level) use ($finalScore) {
            return $level->matchesScore($finalScore);
        });

        if ($matched) {
            return $matched;
        }

        $first = $levels->first();
        $last = $levels->last();

        if ($first && $first->nilai_min !== null && $finalScore < $first->nilai_min) {
            return $first;
        }

        return $last;
    }

    private function findAvailableClass(?int $programId, ?int $levelId): ?Kursus
    {
        if (! $programId || ! $levelId) {
            return null;
        }

        return Kursus::query()
            ->where('program_id', $programId)
            ->where('level_id', $levelId)
            ->whereIn('status', ['buka', 'berjalan'])
            ->withCount('pendaftarans')
            ->orderByRaw("CASE WHEN status = 'buka' THEN 0 ELSE 1 END")
            ->orderBy('tanggal_mulai')
            ->orderBy('id')
            ->get()
            ->first(function (Kursus $kursus) {
                return $kursus->pendaftarans_count < $kursus->kuota;
            });
    }
}
