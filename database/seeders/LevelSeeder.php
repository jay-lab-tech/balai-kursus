<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('levels')->truncate();
        DB::table('levels')->insert([
            ['nama' => 'Beginner'],
            ['nama' => 'Elementary'],
            ['nama' => 'Intermediate'],
            ['nama' => 'Upper Intermediate'],
            ['nama' => 'Advanced'],
        ]);
    }
}
