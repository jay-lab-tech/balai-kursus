<?php

namespace Database\Seeders;

use App\Models\Hari;
use App\Models\Jadwal;
use App\Models\Kela;
use App\Models\Kursus;
use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::where('role', 'admin')->value('id') ?? User::value('id');
        $haris = Hari::orderBy('urutan')->get()->keyBy('urutan');
        $lokasis = Lokasi::orderBy('id')->get()->values();
        $kelas = Kela::orderBy('id')->get()->values();
        $kursuses = Kursus::orderBy('id')->get()->values();
        $timeSlots = [
            ['jam_mulai' => '07:00:00', 'jam_selesai' => '09:00:00'],
            ['jam_mulai' => '09:30:00', 'jam_selesai' => '11:30:00'],
            ['jam_mulai' => '13:00:00', 'jam_selesai' => '15:00:00'],
            ['jam_mulai' => '15:30:00', 'jam_selesai' => '17:30:00'],
        ];

        if (!$adminId || $haris->isEmpty() || $lokasis->isEmpty() || $kelas->isEmpty()) {
            return;
        }

        $weeklySlotCount = $haris->count() * $lokasis->count() * count($timeSlots);

        foreach ($kursuses as $courseIndex => $kursus) {
            $slotIndex = $courseIndex % $weeklySlotCount;
            $slotBatch = intdiv($courseIndex, $weeklySlotCount);
            $hariUrutan = ($slotIndex % $haris->count()) + 1;
            $locationBand = intdiv($slotIndex, $haris->count());
            $lokasiIndex = $locationBand % $lokasis->count();
            $timeSlotIndex = intdiv($locationBand, $lokasis->count()) % count($timeSlots);

            $lokasi = $lokasis[$lokasiIndex];
            $kela = $kelas[($courseIndex + $lokasiIndex) % $kelas->count()];
            $slotWaktu = $timeSlots[$timeSlotIndex];
            $tanggalMulai = $kursus->tanggal_mulai?->copy()->startOfDay() ?? now()->startOfDay();
            $tanggalPertama = $this->nextDateForDay(
                $tanggalMulai->addWeeks($slotBatch * 4),
                $hariUrutan
            );

            foreach (range(1, 4) as $pertemuan) {
                $tanggal = $tanggalPertama->copy()->addWeeks($pertemuan - 1);
                $hari = $haris->get($tanggal->dayOfWeekIso);

                Jadwal::updateOrCreate([
                    'kursus_id' => $kursus->id,
                    'pertemuan_ke' => $pertemuan,
                ], [
                    'lokasi_id' => $lokasi->id,
                    'kela_id' => $kela->id,
                    'hari_id' => $hari?->id,
                    'tgl_pertemuan' => $tanggal->toDateString(),
                    'jam_mulai' => $slotWaktu['jam_mulai'],
                    'jam_selesai' => $slotWaktu['jam_selesai'],
                    'created_by' => $adminId,
                ]);
            }
        }
    }

    private function nextDateForDay(Carbon $startDate, int $hariUrutan): Carbon
    {
        $tanggal = $startDate->copy();

        while ($tanggal->dayOfWeekIso !== $hariUrutan) {
            $tanggal->addDay();
        }

        return $tanggal;
    }
}
