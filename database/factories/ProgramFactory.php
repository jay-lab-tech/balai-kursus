<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgramFactory extends Factory
{
    protected $model = Program::class;

    public function definition(): array
    {
        $programs = [
            'General English',
            'Business English',
            'Academic English',
            'English for Kids',
            'TOEFL Preparation',
            'IELTS Preparation',
            'English Conversation',
            'English Writing',
        ];

        return [
            'nama' => $this->faker->unique()->randomElement($programs),
        ];
    }
}
