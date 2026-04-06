@extends('peserta::layouts.student')

@section('title', 'Riwayat Pembayaran - Balai Kursus')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl space-y-8">
        <div>
            <h1 class="text-4xl font-bold text-white">
                <i class="bi bi-receipt text-yellow-400 mr-3"></i>Riwayat Pembayaran
            </h1>
            <p class="mt-2 text-gray-400">Daftar seluruh transaksi pembayaran kelas/kursus yang sudah Anda dapatkan.</p>
        </div>

        @if($payments->isEmpty())
            <div class="rounded-3xl border border-dashed border-white/10 px-8 py-16 text-center text-gray-400">
                Belum ada transaksi pembayaran yang tercatat.
            </div>
        @else
            <div class="grid gap-5 md:grid-cols-3">
                <div class="rounded-3xl bg-gradient-to-br from-blue-500 to-blue-700 p-6 text-white shadow-xl">
                    <p class="text-sm font-semibold uppercase tracking-wider opacity-80">Total Transaksi</p>
                    <p class="mt-3 text-4xl font-bold">{{ $payments->count() }}</p>
                </div>
                <div class="rounded-3xl bg-gradient-to-br from-emerald-500 to-emerald-700 p-6 text-white shadow-xl">
                    <p class="text-sm font-semibold uppercase tracking-wider opacity-80">Pembayaran Berhasil</p>
                    <p class="mt-3 text-4xl font-bold">{{ $payments->where('status', 'success')->count() }}</p>
                </div>
                <div class="rounded-3xl bg-gradient-to-br from-yellow-500 to-yellow-700 p-6 text-white shadow-xl">
                    <p class="text-sm font-semibold uppercase tracking-wider opacity-80">Nominal Berhasil</p>
                    <p class="mt-3 text-2xl font-bold">Rp {{ number_format($payments->where('status', 'success')->sum('amount'), 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-black/20 text-left text-xs uppercase tracking-[0.25em] text-gray-400">
                            <tr>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Program</th>
                                <th class="px-6 py-4">Kelas</th>
                                <th class="px-6 py-4">Nominal</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Order ID</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10 text-sm text-gray-200">
                            @foreach($payments as $payment)
                                <tr class="hover:bg-white/5">
                                    <td class="px-6 py-4">{{ $payment->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4">{{ $payment->pendaftaran->program->nama ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $payment->pendaftaran->kursus->nama ?? 'Belum ada kelas saat transaksi dibuat' }}</td>
                                    <td class="px-6 py-4">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="rounded-full bg-white/10 px-3 py-1 text-xs uppercase">{{ $payment->status }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-400">{{ $payment->order_id }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
