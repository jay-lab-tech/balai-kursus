@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran')

@section('page-title', 'Verifikasi Pembayaran')

@section('content')
<div class="space-y-6">
    <h2 class="text-2xl font-bold text-gray-900"><i class="bi bi-credit-card me-2"></i>Verifikasi Pembayaran</h2>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($pembayarans as $p)
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden">
            <div class="p-6">
                <h3 class="font-semibold text-gray-900 mb-4">
                    <i class="bi bi-person me-2"></i>{{ $p->pendaftaran->peserta->user->name }}
                </h3>
                
                <div class="mb-4 p-3 bg-gray-50 rounded border border-gray-200">
                    <p class="text-xs text-gray-600 uppercase tracking-wider mb-1">Jumlah Pembayaran</p>
                    <p class="text-lg font-bold text-blue-600">Rp {{ number_format($p->jumlah) }}</p>
                </div>

                <p class="text-sm text-gray-600 mb-3 uppercase tracking-wider font-medium">Bukti Pembayaran:</p>
                <div class="mb-4 h-48 overflow-hidden rounded">
                    <img src="{{ asset('storage/'.$p->bukti_path) }}" class="w-full h-full object-cover rounded">
                </div>

                <form action="/admin/pembayaran/{{ $p->id }}/verifikasi" method="POST">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                        <i class="bi bi-check-circle me-2"></i>VERIFIKASI
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="md:col-span-2 lg:col-span-3">
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Informasi:</strong> Tidak ada pembayaran yang menunggu verifikasi.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
