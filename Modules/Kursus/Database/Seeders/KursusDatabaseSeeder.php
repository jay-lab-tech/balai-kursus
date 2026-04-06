<?php

namespace Modules\Kursus\Database\Seeders;

use Illuminate\Database\Seeder;

class KursusDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            \Database\Seeders\InstrukturSeeder::class,
            \Database\Seeders\KursusSeeder::class,
        ]);
    }
}
