<?php

namespace Database\Factories;

use App\Models\Level;
use Illuminate\Database\Eloquent\Factories\Factory;

class LevelFactory extends Factory
{
    protected $model = Level::class;

    public function definition(): array
    {
        $levels = ['Beginner', 'Elementary', 'Intermediate', 'Upper-Intermediate', 'Advanced'];
        $urutan = $this->faker->unique()->numberBetween(1, count($levels));

        return [
            'nama' => $levels[$urutan - 1],
            'urutan' => $urutan,
            'nilai_min' => ($urutan - 1) * 20,
            'nilai_max' => $urutan * 20 - 1,
        ];
    }
}
