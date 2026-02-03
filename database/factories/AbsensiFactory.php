<?php

namespace Database\Factories;

use App\Models\Absensi;
use App\Models\Risalah;
use App\Models\Pendaftaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbsensiFactory extends Factory
{
    protected $model = Absensi::class;

    public function definition(): array
    {
        return [
            'risalah_id' => Risalah::factory(),
            'pendaftaran_id' => Pendaftaran::factory(),
            'status' => $this->faker->randomElement(['H', 'S', 'I', 'A']),
            'jam_datang' => $this->faker->optional(0.8)->time(),
            'catatan' => $this->faker->optional(0.3)->sentence(),
        ];
    }
}
