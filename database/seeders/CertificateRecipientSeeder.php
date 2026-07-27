<?php

namespace Database\Seeders;

use App\Models\Kursus;
use App\Models\Pendaftaran;
use App\Models\Peserta;
use Illuminate\Database\Seeder;

class CertificateRecipientSeeder extends Seeder
{
    public function run(): void
    {
        $recipientEmails = [
            'peserta1@balai.test',
            'peserta2@balai.test',
            'peserta3@balai.test',
        ];

        $pesertas = Peserta::with('user')
            ->whereHas('user', fn ($query) => $query->whereIn('email', $recipientEmails))
            ->get()
            ->sortBy(fn (Peserta $peserta) => array_search($peserta->user->email, $recipientEmails, true))
            ->values();

        $kursuses = Kursus::with('program')
            ->orderBy('tanggal_mulai')
            ->take($pesertas->count())
            ->get()
            ->values();

        if ($pesertas->isEmpty() || $kursuses->isEmpty()) {
            return;
        }

        foreach ($pesertas as $index => $peserta) {
            $kursus = $kursuses[$index % $kursuses->count()];
            $sequence = str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT);
            $diklasifikasikanAt = $kursus->tanggal_mulai
                ? $kursus->tanggal_mulai->copy()->subDays(3)
                : now()->subDays(3);

            Pendaftaran::updateOrCreate([
                'nomor' => 'REG-SERTIF-'.$sequence,
            ], [
                'peserta_id' => $peserta->id,
                'program_id' => $kursus->program_id,
                'level_id' => $kursus->level_id,
                'kursus_id' => $kursus->id,
                'status_pendaftaran' => Pendaftaran::STATUS_SELESAI,
                'status_pembayaran' => Pendaftaran::PAYMENT_LUNAS,
                'total_bayar' => $kursus->harga,
                'terbayar' => $kursus->harga,
                'catatan_admin' => 'Penerima sertifikat seed '.($index + 1),
                'diklasifikasikan_at' => $diklasifikasikanAt,
            ]);
        }
    }
}
