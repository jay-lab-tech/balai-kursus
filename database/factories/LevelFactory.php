<?php

namespace Database\Factories;

use App\Models\Level;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

class LevelFactory extends Factory
{
    protected $model = Level::class;

    public function definition(): array
    {
        $levels = ['Beginner', 'Elementary', 'Intermediate', 'Upper-Intermediate', 'Advanced'];

        return [
            'program_id' => Program::factory(),
            'nama' => $this->faker->randomElement($levels),
        ];
    }
}
