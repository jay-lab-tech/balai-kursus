<?php

namespace Database\Factories;

use App\Models\Instruktur;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstruktorFactory extends Factory
{
    protected $model = Instruktur::class;

    public function definition(): array
    {
        $spesialisasi = [
            'TOEFL',
            'IELTS',
            'General English',
            'Business English',
            'Conversation',
            'Grammar',
            'Writing',
            'Speaking',
        ];

        return [
            'user_id' => User::factory()->create(['role' => 'instruktur'])->id,
            'nama_instr' => $this->faker->name(),
            'spesialisasi' => $this->faker->randomElement($spesialisasi),
        ];
    }
}
