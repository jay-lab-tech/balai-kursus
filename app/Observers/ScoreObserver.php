<?php

namespace App\Observers;

use App\Models\Score;
use App\Services\PendaftaranPlacementService;

class ScoreObserver
{
    public function created(Score $score): void
    {
        $this->place($score);
    }

    /**
     * Penempatan hanya diulang bila hasil tes atau pendaftaran tujuannya berubah.
     * Menyimpan ulang catatan lain (keterangan, evaluator) tidak boleh mengacak
     * kelas dan status pembayaran peserta.
     */
    public function updated(Score $score): void
    {
        if (! $score->wasChanged(['final_score', 'pendaftaran_id', 'jenis'])) {
            return;
        }

        $this->place($score);
    }

    public function deleted(Score $score): void
    {
        if ($score->jenis !== Score::TYPE_PLACEMENT || ! $score->pendaftaran) {
            return;
        }

        app(PendaftaranPlacementService::class)->resetPlacement($score->pendaftaran);
    }

    private function place(Score $score): void
    {
        if ($score->jenis !== Score::TYPE_PLACEMENT) {
            return;
        }

        app(PendaftaranPlacementService::class)->placeFromScore($score);
    }
}
