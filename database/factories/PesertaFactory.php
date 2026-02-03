<?php

namespace Database\Factories;

use App\Models\Peserta;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PesertaFactory extends Factory
{
    protected $model = Peserta::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['role' => 'peserta'])->id,
            'nomor_peserta' => 'PS-' . str_pad($this->faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'no_hp' => '08' . $this->faker->numerify('##########'),
            'instansi' => $this->faker->randomElement([
                'UPI',
                'ITB',
                'Universitas Indonesia',
                'Universitas Bandung',
                'Dinas Pendidikan',
                'PT Maju Jaya',
                'PT Sentosa',
                'SMA Negeri 1',
                'SMA Swasta',
                null,
            ]),
        ];
    }
}
