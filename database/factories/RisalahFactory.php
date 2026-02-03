<?php

namespace Database\Factories;

use App\Models\Risalah;
use App\Models\Kursus;
use App\Models\Instruktur;
use Illuminate\Database\Eloquent\Factories\Factory;

class RisalahFactory extends Factory
{
    protected $model = Risalah::class;

    public function definition(): array
    {
        return [
            'kursus_id' => Kursus::factory(),
            'instruktur_id' => Instruktur::factory(),
            'pertemuan_ke' => $this->faker->numberBetween(1, 12),
            'tgl_pertemuan' => $this->faker->dateTimeBetween('-2 months'),
            'materi' => $this->faker->sentence(4),
            'catatan' => $this->faker->optional(0.5)->paragraph(),
        ];
    }
}
