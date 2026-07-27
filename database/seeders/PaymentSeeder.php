<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Pendaftaran;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $pendaftaran = Pendaftaran::with(['peserta.user', 'kursus'])
            ->whereNotNull('kursus_id')
            ->orderBy('id')
            ->first();

        if (! $pendaftaran || ! $pendaftaran->peserta?->user || ! $pendaftaran->kursus) {
            return;
        }

        Payment::updateOrCreate(
            ['order_id' => 'DEMO-PAID-0001'],
            [
                'amount' => $pendaftaran->kursus->harga,
                'description' => 'Pembayaran demo '.$pendaftaran->kursus->nama,
                'customer_name' => $pendaftaran->peserta->user->name,
                'customer_email' => $pendaftaran->peserta->user->email,
                'customer_phone' => $pendaftaran->peserta->no_hp ?? '081200000001',
                'status' => 'success',
                'payment_method' => 'bank_transfer',
                'transaction_id' => 'DEMO-TRANSACTION-0001',
                'response_data' => ['source' => 'local-demo-seeder'],
                'user_id' => $pendaftaran->peserta->user_id,
                'pendaftaran_id' => $pendaftaran->id,
            ]
        );
    }
}
