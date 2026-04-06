<?php

namespace Database\Seeders;

use App\Models\Instruktur;
use App\Models\Kursus;
use App\Models\Risalah;
use Illuminate\Database\Seeder;

class RisalahSeeder extends Seeder
{
    public function run(): void
    {
        $fallbackInstrukturId = Instruktur::value('id');
        $kursuses = Kursus::with(['jadwals', 'instrukturKursusLevels'])
            ->whereHas('pendaftarans')
            ->orderBy('id')
            ->get();

        foreach ($kursuses as $kursus) {
            $jadwals = $kursus->jadwals->sortBy('pertemuan_ke')->take(2)->values();
            $instrukturIds = $kursus->instrukturKursusLevels->pluck('instruktur_id')->unique()->values();

            foreach ($jadwals as $index => $jadwal) {
                $instrukturId = $instrukturIds[$index] ?? $fallbackInstrukturId;

                if (!$instrukturId) {
                    continue;
                }

                Risalah::updateOrCreate([
                    'kursus_id' => $kursus->id,
                    'pertemuan_ke' => $jadwal->pertemuan_ke,
                ], [
                    'instruktur_id' => $instrukturId,
                    'jadwal_id' => $jadwal->id,
                    'tgl_pertemuan' => $jadwal->tgl_pertemuan,
                    'materi' => 'Materi pertemuan ' . $jadwal->pertemuan_ke . ' untuk ' . $kursus->nama,
                    'catatan' => 'Kelas demo aktif dengan peserta hasil klasifikasi.',
                    'dokumen' => null,
                ]);
            }
        }
    }
}
