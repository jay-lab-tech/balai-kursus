<?php

namespace Database\Seeders;

use App\Models\Hari;
use App\Models\Jadwal;
use App\Models\Kela;
use App\Models\Kursus;
use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::where('role', 'admin')->value('id') ?? User::value('id');
        $haris = Hari::orderBy('urutan')->get()->values();
        $lokasis = Lokasi::orderBy('id')->get()->values();
        $kelas = Kela::orderBy('id')->get()->values();
        $kursuses = Kursus::orderBy('id')->get()->values();

        if (!$adminId || $haris->isEmpty() || $lokasis->isEmpty() || $kelas->isEmpty()) {
            return;
        }

        foreach ($kursuses as $courseIndex => $kursus) {
            $mulai = $kursus->tanggal_mulai?->copy() ?? now()->startOfDay();

            foreach (range(1, 4) as $pertemuan) {
                $hari = $haris[($courseIndex + $pertemuan - 1) % $haris->count()];
                $lokasi = $lokasis[($courseIndex + $pertemuan - 1) % $lokasis->count()];
                $kela = $kelas[($courseIndex + $pertemuan - 1) % $kelas->count()];
                $tanggal = $mulai->copy()->addDays(($pertemuan - 1) * 7);

                Jadwal::updateOrCreate([
                    'kursus_id' => $kursus->id,
                    'pertemuan_ke' => $pertemuan,
                ], [
                    'lokasi_id' => $lokasi->id,
                    'kela_id' => $kela->id,
                    'hari_id' => $hari->id,
                    'tgl_pertemuan' => $tanggal->toDateString(),
                    'jam_mulai' => $pertemuan % 2 === 0 ? '13:00:00' : '09:00:00',
                    'jam_selesai' => $pertemuan % 2 === 0 ? '15:00:00' : '11:00:00',
                    'created_by' => $adminId,
                ]);
            }
        }
    }
}
