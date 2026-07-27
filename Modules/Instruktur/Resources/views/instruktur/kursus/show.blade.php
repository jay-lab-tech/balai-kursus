@extends('instruktur::layouts.master')

@section('title', $kursus->nama . ' - Balai Kursus')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Tombol Kembali -->
        <a href="/instruktur/kursus" class="inline-flex items-center px-4 py-2 mb-8 text-yellow-400 hover:text-yellow-300 transition-colors duration-200 group">
            <i class="bi bi-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
            <span>Kembali ke Daftar Kursus</span>
        </a>

        <!-- Header Kursus -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Konten Utama -->
            <div class="lg:col-span-2">
                <!-- Hero Section -->
                <div class="rounded-2xl overflow-hidden shadow-2xl mb-8">
                    <div class="h-64 bg-gradient-to-r from-sky-600 to-sky-700 flex items-center justify-center">
                        <i class="bi bi-book text-white" style="font-size: 80px; opacity: 0.3;"></i>
                    </div>
                    <div class="bg-gradient-to-r from-gray-50 to-white p-8">
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $kursus->nama }}</h1>
                        <p class="text-gray-600">{{ $kursus->program->nama ?? '-' }} â€¢ {{ $kursus->level->nama ?? '-' }}</p>
                    </div>
                </div>

                <!-- Kartu Informasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Program & Level -->
                    <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-6 hover:border-yellow-500/50 transition-colors duration-200">
                        <div class="flex items-start justify-between mb-4">
                            <h3 class="text-lg font-semibold text-white">Informasi Program</h3>
                            <i class="bi bi-diagram-3 text-yellow-400 text-2xl"></i>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Program</p>
                                <p class="text-white font-semibold">{{ $kursus->program->nama ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Level</p>
                                <p class="text-white font-semibold">{{ $kursus->level->nama ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Instruktur & Kuota -->
                    <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-6 hover:border-sky-500/50 transition-colors duration-200">
                        <div class="flex items-start justify-between mb-4">
                            <h3 class="text-lg font-semibold text-white">Detail Pelaksanaan</h3>
                            <i class="bi bi-person-circle text-sky-500 text-2xl"></i>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Instruktur</p>
                                <p class="text-white font-semibold">{{ $kursus->instruktur->nama_instr ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Kuota Peserta</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-white font-semibold">{{ $kursus->pendaftarans()->count() }}/{{ $kursus->kuota }} peserta</span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-2 mt-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-sky-500 to-yellow-400 h-full" style="width: {{ min(($kursus->pendaftarans()->count() / $kursus->kuota) * 100, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Harga -->
                    <div class="bg-gradient-to-br from-yellow-400/10 to-yellow-500/10 border border-yellow-500/30 rounded-xl p-6">
                        <div class="flex items-start justify-between mb-4">
                            <h3 class="text-lg font-semibold text-white">Harga</h3>
                            <i class="bi bi-cash-coin text-yellow-400 text-2xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-yellow-400">Rp {{ number_format($kursus->harga, 0, ',', '.') }}</p>
                    </div>

                    <!-- Periode -->
                    <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-6">
                        <div class="flex items-start justify-between mb-4">
                            <h3 class="text-lg font-semibold text-white">Periode</h3>
                            <i class="bi bi-calendar-event text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-white font-semibold">{{ $kursus->periode ?? '-' }}</p>
                    </div>
                </div>

                <!-- Jadwal -->
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-8 mb-8">
                    <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <i class="bi bi-calendar3 text-sky-500 mr-3"></i>
                        Jadwal Pertemuan
                    </h3>

                    @if($kursus->jadwals && count($kursus->jadwals) > 0)
                        <div class="space-y-4">
                            @foreach($kursus->jadwals as $jadwal)
                            <div class="flex items-start p-4 bg-gradient-to-r from-gray-700/50 to-transparent rounded-lg border border-gray-700 hover:border-sky-500/50 transition-colors duration-200 group">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-sky-600/20 text-sky-400 group-hover:bg-sky-600/30">
                                        <i class="bi bi-calendar2-check"></i>
                                    </div>
                                </div>
                                <div class="flex-grow">
                                    <p class="text-white font-semibold">{{ $jadwal->hari->nama ?? '-' }}</p>
                                    <p class="text-gray-400 text-sm">{{ $jadwal->jam_mulai ?? '-' }} - {{ $jadwal->jam_selesai ?? '-' }} | {{ $jadwal->lokasi->nama ?? '-' }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            <i class="bi bi-inbox text-4xl mb-3 block opacity-50"></i>
                            <p>Jadwal belum tersedia saat ini</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar (bisa diisi info tambahan jika perlu) -->
            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-6 mt-6">
                        <h4 class="text-white font-semibold mb-4 flex items-center">
                            <i class="bi bi-info-circle text-yellow-400 mr-2"></i>
                            Informasi Kursus
                        </h4>
                        <ul class="space-y-3 text-sm text-gray-300">
                            <li class="flex items-start">
                                <i class="bi bi-dot mr-2 text-yellow-400 flex-shrink-0 mt-0.5"></i>
                                <span>Halaman ini hanya dapat diakses oleh instruktur yang mengajar kursus ini.</span>
                            </li>
                            <li class="flex items-start">
                                <i class="bi bi-dot mr-2 text-yellow-400 flex-shrink-0 mt-0.5"></i>
                                <span>Kelola materi, jadwal, dan peserta melalui menu yang tersedia.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
