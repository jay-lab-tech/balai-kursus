<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use Illuminate\Database\Seeder;

class PembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $pendaftarans = Pendaftaran::with(['program', 'peserta.user'])
            ->whereNotNull('kursus_id')
            ->where('total_bayar', '>', 0)
            ->orderBy('id')
            ->take(8)
            ->get()
            ->values();

        foreach ($pendaftarans as $index => $pendaftaran) {
            if ($index < 4) {
                $onlineAmount = $index < 2 ? (int) $pendaftaran->total_bayar : (int) floor($pendaftaran->total_bayar / 2);

                Payment::updateOrCreate([
                    'order_id' => 'PAY-SEED-' . str_pad((string) $pendaftaran->id, 4, '0', STR_PAD_LEFT),
                ], [
                    'amount' => $onlineAmount,
                    'description' => 'Pembayaran online untuk ' . ($pendaftaran->program->nama ?? 'program'),
                    'customer_name' => $pendaftaran->peserta->user->name ?? 'Peserta',
                    'customer_email' => $pendaftaran->peserta->user->email ?? 'peserta@balai.test',
                    'customer_phone' => $pendaftaran->peserta->no_hp ?? '-',
                    'status' => 'success',
                    'payment_method' => 'bank_transfer',
                    'transaction_id' => 'TRX-SEED-' . $pendaftaran->id,
                    'response_data' => ['source' => 'seeder'],
                    'user_id' => $pendaftaran->peserta->user_id,
                    'pendaftaran_id' => $pendaftaran->id,
                ]);
            }

            if ($index >= 2 && $index < 6) {
                Pembayaran::updateOrCreate([
                    'pendaftaran_id' => $pendaftaran->id,
                    'angsuran_ke' => 1,
                ], [
                    'jumlah' => (int) floor($pendaftaran->total_bayar / 3),
                    'status' => 'verified',
                    'bukti_path' => 'payments/seed-proof-' . $pendaftaran->id . '.jpg',
                    'tgl_bayar' => now()->subDays(2)->toDateString(),
                ]);
            }

            $onlinePaid = Payment::where('pendaftaran_id', $pendaftaran->id)
                ->where('status', 'success')
                ->sum('amount');
            $manualPaid = Pembayaran::where('pendaftaran_id', $pendaftaran->id)
                ->where('status', 'verified')
                ->sum('jumlah');
            $paid = (int) ($onlinePaid + $manualPaid);

            $pendaftaran->update([
                'terbayar' => min((int) $pendaftaran->total_bayar, $paid),
                'status_pembayaran' => $paid >= (int) $pendaftaran->total_bayar
                    ? Pendaftaran::PAYMENT_LUNAS
                    : ($paid > 0 ? Pendaftaran::PAYMENT_CICIL : Pendaftaran::PAYMENT_PENDING),
                'status_pendaftaran' => $paid >= (int) $pendaftaran->total_bayar
                    ? Pendaftaran::STATUS_AKTIF
                    : Pendaftaran::STATUS_MENUNGGU_PEMBAYARAN,
            ]);
        }
    }
}
