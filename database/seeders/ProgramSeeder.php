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
            ['nama' => 'Academic English', 'warna' => '#2563eb'],
            ['nama' => 'Conversation Intensive', 'warna' => '#db2777'],
            ['nama' => 'TOEFL Preparation', 'warna' => '#7c3aed'],
            ['nama' => 'IELTS Preparation', 'warna' => '#dc2626'],
        ])->each(fn (array $program) => Program::updateOrCreate(['nama' => $program['nama']], $program));
    }
}
