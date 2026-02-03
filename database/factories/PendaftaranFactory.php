<?php

namespace Database\Factories;

use App\Models\Pendaftaran;
use App\Models\Peserta;
use App\Models\Kursus;
use Illuminate\Database\Eloquent\Factories\Factory;

class PendaftaranFactory extends Factory
{
    protected $model = Pendaftaran::class;

    public function definition(): array
    {
        $kursus = Kursus::factory();
        $totalBayar = $kursus->harga;
        
        $statusPembayaran = $this->faker->randomElement(['pending', 'dp', 'cicil', 'lunas']);
        
        $terbayar = match ($statusPembayaran) {
            'pending' => 0,
            'dp' => (int)($totalBayar * 0.3),
            'cicil' => (int)($totalBayar * 0.7),
            'lunas' => $totalBayar,
        };

        return [
            'peserta_id' => Peserta::factory(),
            'kursus_id' => $kursus,
            'status_pembayaran' => $statusPembayaran,
            'total_bayar' => $totalBayar,
            'terbayar' => $terbayar,
        ];
    }
}
