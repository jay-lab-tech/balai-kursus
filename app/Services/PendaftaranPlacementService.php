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
    /**
     * Tempatkan peserta ke level dan kelas berdasarkan hasil tes penempatan.
     *
     * Penempatan tidak akan menggeser peserta yang sudah menyetorkan pembayaran,
     * kecuali dipaksa lewat $force.
     */
    public function placeFromScore(Score $score, bool $force = false): array
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

        return DB::transaction(function () use ($pendaftaran, $level, $force) {
            $previousKursusId = $pendaftaran->kursus_id;

            // Peserta yang sudah membayar tidak boleh dipindah otomatis: uang yang
            // sudah masuk terikat ke kelas tersebut.
            if (! $force && $previousKursusId && (int) $pendaftaran->terbayar > 0) {
                return [
                    'level' => $pendaftaran->level,
                    'kursus' => $pendaftaran->kursus,
                    'message' => 'Nilai diperbarui. Penempatan dipertahankan karena peserta sudah melakukan pembayaran pada kelas '
                        .($pendaftaran->kursus->nama ?? '-').'.',
                ];
            }

            $selectedKursus = $level
                ? $this->findAvailableClass($pendaftaran->program_id, $level->id, $previousKursusId, $pendaftaran->id)
                : null;

            $kursusChanged = $previousKursusId !== $selectedKursus?->id;

            if ($kursusChanged && $previousKursusId) {
                PesertaKursusLevel::where('peserta_id', $pendaftaran->peserta_id)
                    ->where('kursus_id', $previousKursusId)
                    ->delete();
            }

            $attributes = [
                'level_id' => $level?->id,
                'kursus_id' => $selectedKursus?->id,
                'diklasifikasikan_at' => now(),
            ];

            if (! $selectedKursus) {
                // Belum ada kelas yang bisa dipakai: kembalikan ke antrean penempatan.
                $attributes['status_pendaftaran'] = Pendaftaran::STATUS_MENUNGGU_PENEMPATAN;
                $attributes['status_pembayaran'] = Pendaftaran::PAYMENT_PENDING;
                $attributes['total_bayar'] = 0;
                $attributes['terbayar'] = 0;
            } elseif ($kursusChanged) {
                // Kelas berubah: biaya mengikuti kelas baru, setoran yang sudah ada tetap dihitung.
                $total = (int) ($selectedKursus->harga ?? 0);
                $terbayar = min((int) $pendaftaran->terbayar, $total);

                $attributes['total_bayar'] = $total;
                $attributes['terbayar'] = $terbayar;
                $attributes['status_pembayaran'] = $this->resolvePaymentStatus($total, $terbayar);
                $attributes['status_pendaftaran'] = $total > 0 && $terbayar >= $total
                    ? Pendaftaran::STATUS_AKTIF
                    : Pendaftaran::STATUS_MENUNGGU_PEMBAYARAN;
            }
            // Kelas sama: status dan angka pembayaran dibiarkan apa adanya.

            $pendaftaran->fill($attributes);
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
                'message' => $this->buildMessage($level, $selectedKursus),
            ];
        });
    }

    /**
     * Ringkas kondisi penempatan sebuah score tanpa mengubah apa pun.
     * Dipakai controller agar penempatan hanya dijalankan sekali (oleh observer).
     */
    public function describe(Score $score): array
    {
        $pendaftaran = $score->pendaftaran()->with(['level', 'kursus'])->first();

        if (! $pendaftaran || $score->jenis !== Score::TYPE_PLACEMENT || $score->final_score === null) {
            return [
                'level' => null,
                'kursus' => null,
                'message' => 'Belum ada hasil tes penempatan yang bisa diproses.',
            ];
        }

        return [
            'level' => $pendaftaran->level,
            'kursus' => $pendaftaran->kursus,
            'message' => $this->buildMessage($pendaftaran->level, $pendaftaran->kursus),
        ];
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

    private function buildMessage(?Level $level, ?Kursus $kursus): string
    {
        if ($level && $kursus) {
            return 'Peserta berhasil diklasifikasikan ke level '.$level->nama.' dan ditempatkan ke kelas '.$kursus->nama.'.';
        }

        if ($level) {
            return 'Peserta cocok ke level '.$level->nama.', tetapi semua kelas pada level tersebut sedang penuh.';
        }

        return 'Sistem belum menemukan level yang cocok untuk program ini.';
    }

    private function resolvePaymentStatus(int $total, int $terbayar): string
    {
        if ($total > 0 && $terbayar >= $total) {
            return Pendaftaran::PAYMENT_LUNAS;
        }

        return $terbayar > 0 ? Pendaftaran::PAYMENT_CICIL : Pendaftaran::PAYMENT_PENDING;
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

    /**
     * Cari kelas yang masih punya sisa kuota. Baris kursus dikunci selama
     * transaksi agar dua penempatan bersamaan tidak melewati kuota, dan
     * pendaftaran yang dibatalkan tidak dihitung sebagai kursi terpakai.
     */
    private function findAvailableClass(
        ?int $programId,
        ?int $levelId,
        ?int $preferKursusId = null,
        ?int $excludePendaftaranId = null
    ): ?Kursus {
        if (! $programId || ! $levelId) {
            return null;
        }

        $candidates = Kursus::query()
            ->where('program_id', $programId)
            ->where('level_id', $levelId)
            ->openForRegistration()
            ->orderByRaw("CASE WHEN status = 'buka' THEN 0 ELSE 1 END")
            ->orderBy('tanggal_mulai')
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        if ($candidates->isEmpty()) {
            return null;
        }

        // Pertahankan kelas yang sudah ditempati bila masih valid untuk level ini,
        // supaya nilai yang diperbarui tidak memindah peserta tanpa alasan.
        if ($preferKursusId && $current = $candidates->firstWhere('id', $preferKursusId)) {
            return $current;
        }

        $occupancy = Pendaftaran::query()
            ->whereIn('kursus_id', $candidates->pluck('id'))
            ->where('status_pendaftaran', '!=', Pendaftaran::STATUS_DIBATALKAN)
            ->when($excludePendaftaranId, fn ($query) => $query->where('id', '!=', $excludePendaftaranId))
            ->selectRaw('kursus_id, COUNT(*) as total')
            ->groupBy('kursus_id')
            ->pluck('total', 'kursus_id');

        return $candidates->first(function (Kursus $kursus) use ($occupancy) {
            return (int) ($occupancy[$kursus->id] ?? 0) < (int) $kursus->kuota;
        });
    }
}
