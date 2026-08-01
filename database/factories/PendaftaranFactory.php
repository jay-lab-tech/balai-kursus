<?php

namespace Database\Factories;

use App\Models\Kursus;
use App\Models\Pendaftaran;
use App\Models\Peserta;
use Illuminate\Database\Eloquent\Factories\Factory;

class PendaftaranFactory extends Factory
{
    protected $model = Pendaftaran::class;

    public function definition(): array
    {
        $statusPembayaran = $this->faker->randomElement([
            Pendaftaran::PAYMENT_PENDING,
            Pendaftaran::PAYMENT_DP,
            Pendaftaran::PAYMENT_CICIL,
            Pendaftaran::PAYMENT_LUNAS,
        ]);

        // Kursus dibuat lebih dulu supaya harga, program, dan level pendaftaran
        // konsisten dengan kelas yang ditempati.
        $kursus = Kursus::factory()->create();
        $totalBayar = (int) $kursus->harga;

        $terbayar = match ($statusPembayaran) {
            Pendaftaran::PAYMENT_PENDING => 0,
            Pendaftaran::PAYMENT_DP => (int) ($totalBayar * 0.3),
            Pendaftaran::PAYMENT_CICIL => (int) ($totalBayar * 0.7),
            Pendaftaran::PAYMENT_LUNAS => $totalBayar,
        };

        return [
            'peserta_id' => Peserta::factory(),
            'program_id' => $kursus->program_id,
            'level_id' => $kursus->level_id,
            'kursus_id' => $kursus->id,
            'status_pendaftaran' => $terbayar >= $totalBayar
                ? Pendaftaran::STATUS_AKTIF
                : Pendaftaran::STATUS_MENUNGGU_PEMBAYARAN,
            'status_pembayaran' => $statusPembayaran,
            'total_bayar' => $totalBayar,
            'terbayar' => $terbayar,
        ];
    }
}
