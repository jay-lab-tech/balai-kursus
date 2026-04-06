<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Pendaftaran;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        $pendaftarans = Pendaftaran::whereNotNull('kursus_id')
            ->where('status_pembayaran', Pendaftaran::PAYMENT_LUNAS)
            ->orderBy('id')
            ->take(3)
            ->get();

        foreach ($pendaftarans as $pendaftaran) {
            Certificate::updateOrCreate([
                'course_id' => $pendaftaran->kursus_id,
                'participant_id' => $pendaftaran->peserta_id,
            ], [
                'certificate_name' => 'Sertifikat Kelulusan ' . ($pendaftaran->program->nama ?? 'Program'),
                'certificate_image_path' => 'certificates/seed-certificate.svg',
                'status' => 'published',
            ]);
        }
    }
}
