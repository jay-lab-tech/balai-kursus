@extends('peserta::layouts.student')

@section('title', 'Riwayat Pembayaran - Balai Kursus')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-white mb-2">
                <i class="bi bi-receipt text-yellow-400 mr-3"></i>Riwayat Pembayaran
            </h1>
            <p class="text-gray-400">Kelola dan pantau semua transaksi pembayaran Anda</p>
        </div>

        @if(Auth::user()->peserta)
            @php
                $pendaftarans = Auth::user()->peserta->pendaftarans()->with('kursus', 'pembayarans')->get();
                $allPayments = [];
                foreach($pendaftarans as $p) {
                    foreach($p->pembayarans as $payment) {
                        $payment->pendaftaran = $p;
                        $allPayments[] = $payment;
                    }
                }
                // Sort by date descending
                usort($allPayments, function($a, $b) {
                    $dateA = $a->tgl_pembayaran ? strtotime($a->tgl_pembayaran) : 0;
                    $dateB = $b->tgl_pembayaran ? strtotime($b->tgl_pembayaran) : 0;
                    return $dateB - $dateA;
                });
            @endphp

            @if(count($allPayments) > 0)
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    <!-- Total Transaksi -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold opacity-90">Total Transaksi</p>
                                <p class="text-4xl font-bold mt-2">{{ count($allPayments) }}</p>
                            </div>
                            <div class="text-5xl opacity-20">
                                <i class="bi bi-credit-card"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Terbayar -->
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold opacity-90">Total Terbayar</p>
                                <p class="text-2xl font-bold mt-2">Rp {{ number_format(collect($allPayments)->where('status', 'settlement')->orWhere('status', 'capture')->sum('nominal'), 0, ',', '.') }}</p>
                            </div>
                            <div class="text-5xl opacity-20">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Pending -->
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold opacity-90">Pembayaran Pending</p>
                                <p class="text-2xl font-bold mt-2">Rp {{ number_format(collect($allPayments)->where('status', 'pending')->sum('nominal'), 0, ',', '.') }}</p>
                            </div>
                            <div class="text-5xl opacity-20">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment History Table -->
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl overflow-hidden">
                    <!-- Table Header -->
                    <div class="bg-gradient-to-r from-gray-700 to-gray-800 border-b border-gray-700 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="bi bi-list-check text-yellow-400 mr-3"></i>
                            Riwayat Transaksi
                        </h3>
                    </div>

                    <!-- Table Content -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gradient-to-r from-gray-700/50 to-gray-800/50 border-b border-gray-700">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-300">Tanggal</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-300">Kursus</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-300">Nominal</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-300">Metode</th>
                                    <th class="px-6 py-4 text-center font-semibold text-gray-300">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allPayments as $payment)
                                <tr class="border-b border-gray-700 hover:bg-gray-700/30 transition-colors duration-200">
                                    <!-- Date -->
                                    <td class="px-6 py-4 text-white">
                                        {{ $payment->tgl_pembayaran ? \Carbon\Carbon::parse($payment->tgl_pembayaran)->translatedFormat('d F Y H:i') : '-' }}
                                    </td>

                                    <!-- Course -->
                                    <td class="px-6 py-4">
                                        <div class="text-white font-semibold">{{ $payment->pendaftaran->kursus->nama }}</div>
                                        <div class="text-gray-400 text-xs">{{ $payment->pendaftaran->kursus->program->nama ?? 'N/A' }}</div>
                                    </td>

                                    <!-- Nominal -->
                                    <td class="px-6 py-4 text-yellow-400 font-semibold">
                                        Rp {{ number_format($payment->nominal, 0, ',', '.') }}
                                    </td>

                                    <!-- Method -->
                                    <td class="px-6 py-4 text-gray-300">
                                        @if($payment->payment_method)
                                            <span class="inline-flex items-center">
                                                <i class="bi bi-credit-card mr-2 text-blue-400"></i>
                                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                            </span>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4 text-center">
                                        @if($payment->status === 'settlement' || $payment->status === 'capture')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400 border border-green-500/30">
                                                <i class="bi bi-check-circle mr-1"></i>
                                                Berhasil
                                            </span>
                                        @elseif($payment->status === 'pending')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                                                <i class="bi bi-hourglass-split mr-1"></i>
                                                Pending
                                            </span>
                                        @elseif($payment->status === 'deny' || $payment->status === 'cancel')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-500/20 text-red-400 border border-red-500/30">
                                                <i class="bi bi-x-circle mr-1"></i>
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-500/20 text-gray-400 border border-gray-500/30">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-20">
                    <div class="inline-flex items-center justify-center h-24 w-24 bg-gray-700/50 rounded-full mb-6">
                        <i class="bi bi-inbox text-4xl text-gray-400"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Belum Ada Riwayat Pembayaran</h2>
                    <p class="text-gray-400 mb-8">Anda belum melakukan transaksi pembayaran apapun</p>
                    <a href="/peserta/pendaftaran" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-lg transform hover:scale-105 transition-all duration-200">
                        <i class="bi bi-credit-card mr-2"></i>
                        <span>Lakukan Pembayaran</span>
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-20 text-gray-400">
                <p>Data peserta tidak ditemukan. Hubungi administrator.</p>
            </div>
        @endif
    </div>
</div>
@endsection
