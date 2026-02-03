<?php

namespace Database\Factories;

use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class PembayaranFactory extends Factory
{
    protected $model = Pembayaran::class;

    public function definition(): array
    {
        return [
            'pendaftaran_id' => Pendaftaran::factory(),
            'angsuran_ke' => $this->faker->numberBetween(1, 4),
            'jumlah' => $this->faker->numberBetween(200000, 500000),
            'status' => $this->faker->randomElement(['pending', 'verified', 'rejected']),
            'bukti_path' => $this->faker->optional(0.7)->filePath(),
            'tgl_bayar' => $this->faker->optional(0.8)->dateTimeBetween('-3 months'),
        ];
    }
}
