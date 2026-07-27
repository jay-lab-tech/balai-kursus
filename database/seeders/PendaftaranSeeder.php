<?php

namespace Database\Seeders;

use App\Models\Pendaftaran;
use App\Models\Peserta;
use App\Models\Program;
use Illuminate\Database\Seeder;

class PendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        $pesertas = Peserta::orderBy('id')->get()->values();
        $programs = Program::orderBy('id')->get()->values();

        if ($pesertas->isEmpty() || $programs->isEmpty()) {
            return;
        }

        foreach ($pesertas as $index => $peserta) {
            foreach ([0, 1] as $offset) {
                $program = $programs[($index + $offset) % $programs->count()];
                $sequence = ($index * 2) + $offset + 1;

                Pendaftaran::updateOrCreate([
                    'nomor' => 'REG-SEED-'.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT),
                ], [
                    'peserta_id' => $peserta->id,
                    'participant_email_snapshot' => $peserta->user->email ?? null,
                    'program_id' => $program->id,
                    'level_id' => null,
                    'kursus_id' => null,
                    'status_pendaftaran' => Pendaftaran::STATUS_MENUNGGU_TES,
                    'status_pembayaran' => Pendaftaran::PAYMENT_PENDING,
                    'total_bayar' => 0,
                    'terbayar' => 0,
                    'catatan_admin' => null,
                    'diklasifikasikan_at' => null,
                ]);
            }
        }
    }
}
