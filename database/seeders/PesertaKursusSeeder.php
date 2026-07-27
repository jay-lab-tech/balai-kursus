<?php

namespace Database\Seeders;

use App\Models\Pendaftaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PesertaKursusSeeder extends Seeder
{
    public function run(): void
    {
        $pairs = Pendaftaran::whereNotNull('kursus_id')
            ->get(['peserta_id', 'kursus_id'])
            ->unique(fn ($item) => $item->peserta_id.'-'.$item->kursus_id);

        foreach ($pairs as $pair) {
            $exists = DB::table('peserta_kursus')
                ->where('peserta_id', $pair->peserta_id)
                ->where('kursus_id', $pair->kursus_id)
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('peserta_kursus')->insert([
                'peserta_id' => $pair->peserta_id,
                'kursus_id' => $pair->kursus_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
