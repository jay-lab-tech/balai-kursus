<?php

namespace App\Console\Commands;

use App\Jobs\GenerateCertificateJob;
use App\Models\Certificate;
use Illuminate\Console\Command;

class IssueCertificate extends Command
{
    protected $signature = 'certificate:issue {peserta_id} {kursus_id}';
    protected $description = 'Issue a certificate for a peserta and kursus';

    public function handle()
    {
        $pesertaId = $this->argument('peserta_id');
        $kursusId = $this->argument('kursus_id');

        $cert = Certificate::create([
            'peserta_id' => $pesertaId,
            'kursus_id' => $kursusId,
            'issued_at' => now(),
        ]);

        GenerateCertificateJob::dispatch($cert);

        $this->info('Certificate queued: ' . $cert->no_sertifikat);
        return 0;
    }
}
