<?php

namespace App\Observers;

use App\Models\Score;
use App\Services\PendaftaranPlacementService;

class ScoreObserver
{
    public function saved(Score $score): void
    {
        if ($score->jenis !== Score::TYPE_PLACEMENT) {
            return;
        }

        app(PendaftaranPlacementService::class)->placeFromScore($score);
    }

    public function deleted(Score $score): void
    {
        if ($score->jenis !== Score::TYPE_PLACEMENT || ! $score->pendaftaran) {
            return;
        }

        app(PendaftaranPlacementService::class)->resetPlacement($score->pendaftaran);
    }
}
