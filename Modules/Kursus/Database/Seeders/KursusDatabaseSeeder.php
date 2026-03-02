<?php

namespace Modules\Kursus\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class KursusDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $levels = \App\Models\Level::limit(5)->get();
        $programs = \App\Models\Program::pluck('id')->toArray();
        $instrukturs = \App\Models\Instruktur::pluck('id')->toArray();

        foreach ($levels as $level) {
            \App\Models\Kursus::create([
                'program_id' => $programs[array_rand($programs)],
                'level_id' => $level->id,
                'instruktur_id' => $instrukturs[array_rand($instrukturs)],
                'instruktur_id_2' => rand(0, 1) ? $instrukturs[array_rand($instrukturs)] : null,
                'nama' => 'Kursus ' . $level->nama,
                'periode' => 'Maret 2026',
                'tanggal_mulai' => '2026-03-10',
                'tanggal_selesai' => '2026-04-10',
                'harga' => rand(400000, 1000000),
                'harga_upi' => rand(300000, 900000),
                'kuota' => rand(20, 40),
                'status' => 'buka'
            ]);
        }
    }
}
