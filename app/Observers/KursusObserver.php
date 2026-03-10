<?php

namespace App\Observers;

use App\Models\Kursus;
// use App\Models\CertificateTemplate; // dihapus

class KursusObserver
{
    /**
     * Handle the Kursus "created" event.
     * Fitur sertifikat dihapus, tidak ada template otomatis.
     */
    public function created(Kursus $kursus)
    {
        // Fitur sertifikat dihapus
    }
    // Fungsi getDefaultHtml dihapus
}
