<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Pendaftaran;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        $template = CertificateTemplate::active()->first();

        if (!$template) {
            return;
        }

        $pendaftarans = Pendaftaran::with(['program', 'peserta.user', 'kursus.program'])
            ->whereNotNull('kursus_id')
            ->where('catatan_admin', 'like', 'Penerima sertifikat seed%')
            ->orderBy('id')
            ->get();

        if ($pendaftarans->isEmpty()) {
            $pendaftarans = Pendaftaran::with(['program', 'peserta.user', 'kursus.program'])
                ->whereNotNull('kursus_id')
                ->where('status_pendaftaran', Pendaftaran::STATUS_SELESAI)
                ->where('status_pembayaran', Pendaftaran::PAYMENT_LUNAS)
                ->orderBy('id')
                ->take(4)
                ->get();
        }

        foreach ($pendaftarans as $index => $pendaftaran) {
            if (!$pendaftaran->peserta || !$pendaftaran->peserta->user || !$pendaftaran->kursus) {
                continue;
            }

            $pendaftaran->forceFill([
                'status_pendaftaran' => Pendaftaran::STATUS_SELESAI,
                'status_pembayaran' => Pendaftaran::PAYMENT_LUNAS,
                'total_bayar' => $pendaftaran->kursus->harga,
                'terbayar' => $pendaftaran->kursus->harga,
            ])->save();

            $issuedDate = now()->subDays($index)->toDateString();

            Certificate::updateOrCreate([
                'course_id' => $pendaftaran->kursus_id,
                'participant_id' => $pendaftaran->peserta_id,
            ], [
                'template_id' => $template->id,
                'user_id' => $pendaftaran->peserta->user_id,
                'certificate_name' => 'Sertifikat Kelulusan ' . ($pendaftaran->program->nama ?? 'Program'),
                'certificate_number' => sprintf('%d/%s/%d', $index + 1, $template->certificate_prefix, now()->year),
                'serial_number' => str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                'issued_date' => $issuedDate,
                'status' => Certificate::STATUS_PUBLISHED,
                'participant_name_snapshot' => $pendaftaran->peserta->user->name,
                'program_name_snapshot' => $pendaftaran->kursus->program->nama ?? $pendaftaran->program->nama ?? $pendaftaran->kursus->nama,
                'course_name_snapshot' => $pendaftaran->kursus->nama,
                'hours_snapshot' => $pendaftaran->kursus->jam_pelajaran,
                'start_date_snapshot' => $pendaftaran->kursus->tanggal_mulai,
                'end_date_snapshot' => $pendaftaran->kursus->tanggal_selesai,
                'signer_name_snapshot' => $template->signer_name,
                'signer_title_snapshot' => $template->signer_title,
                'signer_nip_snapshot' => $template->signer_nip,
                'city_snapshot' => $template->city,
            ]);
        }
    }
}
