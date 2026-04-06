<?php

namespace Modules\Level\Database\Seeders;

use Illuminate\Database\Seeder;

class LevelDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(\Database\Seeders\LevelSeeder::class);
    }
}
