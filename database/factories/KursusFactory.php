<?php

namespace Database\Factories;

use App\Models\Kursus;
use App\Models\Level;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

class KursusFactory extends Factory
{
    protected $model = Kursus::class;

    public function definition(): array
    {
        $harga = $this->faker->randomElement([500000, 750000, 1000000, 1500000, 2000000]);
        $mulai = $this->faker->dateTimeBetween('-1 month', '+1 month');

        return [
            'program_id' => Program::factory(),
            'level_id' => Level::factory(),
            'nama' => $this->faker->sentence(3),
            'periode' => $this->faker->year().'-'.$this->faker->randomElement(['A', 'B']),
            'tanggal_mulai' => $mulai->format('Y-m-d'),
            'tanggal_selesai' => $mulai->modify('+2 months')->format('Y-m-d'),
            'jam_pelajaran' => $this->faker->numberBetween(20, 60),
            'harga' => $harga,
            'harga_upi' => (int) ($harga * 0.8),
            'kuota' => $this->faker->numberBetween(10, 50),
            'status' => $this->faker->randomElement(['buka', 'tutup', 'berjalan']),
        ];
    }

    public function buka(): static
    {
        return $this->state(fn () => ['status' => 'buka']);
    }
}
