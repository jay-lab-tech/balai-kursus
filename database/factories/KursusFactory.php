<?php

namespace Database\Factories;

use App\Models\Kursus;
use App\Models\Program;
use App\Models\Level;
use App\Models\Instruktur;
use Illuminate\Database\Eloquent\Factories\Factory;

class KursusFactory extends Factory
{
    protected $model = Kursus::class;

    public function definition(): array
    {
        $harga = $this->faker->randomElement([500000, 750000, 1000000, 1500000, 2000000]);
        
        return [
            'program_id' => Program::factory(),
            'level_id' => Level::factory(),
            'instruktur_id' => Instruktur::factory(),
            'nama' => $this->faker->sentence(3),
            'harga' => $harga,
            'kuota' => $this->faker->numberBetween(10, 50),
            'status' => $this->faker->randomElement(['buka', 'tutup', 'berjalan']),
        ];
    }
}
