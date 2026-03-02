<?php

namespace Modules\Level\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class LevelDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        \App\Models\Kursus::query()->delete();
        \App\Models\Level::truncate();
        $levels = [
            ['program_id' => 1, 'nama' => 'Beginner', 'warna' => '#b3e5fc'],
            ['program_id' => 1, 'nama' => 'Elementary', 'warna' => '#ffe082'],
            ['program_id' => 1, 'nama' => 'Intermediate', 'warna' => '#ce93d8'],
            ['program_id' => 1, 'nama' => 'Upper Intermediate', 'warna' => '#a5d6a7'],
            ['program_id' => 1, 'nama' => 'Advanced', 'warna' => '#ffab91'],
        ];
        foreach ($levels as $level) {
            \App\Models\Level::create($level);
        }
    }
}
