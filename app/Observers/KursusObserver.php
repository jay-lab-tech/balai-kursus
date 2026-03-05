<?php

namespace App\Observers;

use App\Models\Kursus;
use App\Models\CertificateTemplate;

class KursusObserver
{
    /**
     * Handle the Kursus "created" event.
     * Auto-create a certificate template for the course.
     */
    public function created(Kursus $kursus)
    {
        // Check if template already exists
        if ($kursus->certificateTemplate) {
            return;
        }

        // Create default template for the course
        $defaultTemplate = CertificateTemplate::getDefault();
        $defaultHtml = $defaultTemplate?->html_template ?? $this->getDefaultHtml();

        CertificateTemplate::create([
            'kursus_id' => $kursus->id,
            'name' => 'Template ' . $kursus->judul,
            'html_template' => $defaultHtml,
            'is_default' => false,
        ]);
    }

    /**
     * Get default HTML template if none exists.
     */
    private function getDefaultHtml()
    {
        return <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 40px; }
        .certificate { border: 3px solid #gold; padding: 40px; max-width: 800px; margin: 0 auto; }
        h1 { color: #333; }
        .course { font-size: 18px; margin: 20px 0; }
        .date { font-size: 14px; color: #666; }
        .footer { margin-top: 40px; border-top: 1px solid #ddd; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="certificate">
        <h1>SERTIFIKAT</h1>
        <p>Diberikan kepada:</p>
        <h2>{{NAMA}}</h2>
        <p>Telah berhasil menyelesaikan kursus:</p>
        <h3 class="course">{{KURSUS}}</h3>
        <p class="date">Tanggal: {{TANGGAL}}</p>
        <p>No. Sertifikat: {{NO_SERTIF}}</p>
        <div class="footer">
            <p>&copy; Balai Kursus</p>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
