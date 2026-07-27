<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['nama' => 'General English', 'warna' => '#f59e0b'],
            ['nama' => 'Business English', 'warna' => '#0f766e'],
        ])->each(fn (array $program) => Program::updateOrCreate(['nama' => $program['nama']], $program));
    }
}
