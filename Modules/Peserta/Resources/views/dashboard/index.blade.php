@extends('peserta::layouts.student')

@section('title', 'Dashboard - Balai Kursus')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">
                        <i class="bi bi-speedometer2 text-yellow-400 mr-3"></i>Dashboard Peserta
                    </h1>
                    <p class="text-gray-400">Kelola kursus dan pembelajaran Anda</p>
                </div>
                <div class="text-right">
                    <a href="/peserta/kursus" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200">
                        <i class="bi bi-plus-circle mr-2"></i>
                        Daftar Kursus Baru
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Kursus -->
                <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-200">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold opacity-80">Total Kursus</p>
                                <p class="text-4xl font-bold mt-2">{{ $pendaftarans ? count($pendaftarans) : 0 }}</p>
                            </div>
                            <div class="text-5xl opacity-20">
                                <i class="bi bi-book-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kursus Aktif -->
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-200">
                    <div class="p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold opacity-90">Kursus Aktif</p>
                                <p class="text-4xl font-bold mt-2">{{ $pendaftarans ? count($pendaftarans->filter(fn($p) => $p->kursus->status === 'berjalan')) : 0 }}</p>
                            </div>
                            <div class="text-5xl opacity-20">
                                <i class="bi bi-play-circle-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pembayaran Selesai -->
                <div class="bg-gradient-to-br from-black to-gray-800 rounded-xl shadow-lg overflow-hidden border border-yellow-400 transform hover:scale-105 transition-transform duration-200">
                    <div class="p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-yellow-400">Pembayaran Selesai</p>
                                <p class="text-4xl font-bold mt-2 text-white">{{ $pendaftarans ? count($pendaftarans->filter(fn($p) => $p->status_pembayaran === 'selesai')) : 0 }}</p>
                            </div>
                            <div class="text-5xl opacity-30 text-yellow-400">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Kursus Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-red-600 to-red-700 px-8 py-6">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <i class="bi bi-book mr-3"></i>
                            Kursus yang Saya Ikuti
                        </h2>
                    </div>

                    <!-- Content -->
                    <div class="p-8">
                        @if($pendaftarans && count($pendaftarans) > 0)
                            <div class="space-y-4">
                                @foreach($pendaftarans as $p)
                                <div class="group bg-gradient-to-r from-gray-50 to-white hover:from-red-50 hover:to-yellow-50 border-2 border-gray-200 hover:border-red-300 rounded-xl p-6 transition-all duration-300 transform hover:shadow-xl hover:-translate-y-1">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-red-700 transition-colors">
                                                {{ $p->kursus->nama }}
                                            </h3>
                                            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                                <span class="flex items-center">
                                                    <i class="bi bi-diagram-3 text-red-500 mr-1"></i>
                                                    {{ $p->kursus->program->nama }}
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="bi bi-bar-chart text-yellow-500 mr-1"></i>
                                                    {{ $p->kursus->level->nama }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            @if($p->status_pembayaran === 'selesai')
                                                <span class="inline-block px-4 py-2 bg-gradient-to-r from-green-400 to-green-500 text-white font-bold rounded-full text-xs">
                                                    <i class="bi bi-check-circle mr-1"></i>Dibayar
                                                </span>
                                            @elseif($p->status_pembayaran === 'dp')
                                                <span class="inline-block px-4 py-2 bg-gradient-to-r from-yellow-400 to-yellow-500 text-gray-900 font-bold rounded-full text-xs">
                                                    <i class="bi bi-exclamation-circle mr-1"></i>DP
                                                </span>
                                            @else
                                                <span class="inline-block px-4 py-2 bg-gradient-to-r from-red-400 to-red-500 text-white font-bold rounded-full text-xs">
                                                    <i class="bi bi-clock mr-1"></i>Pending
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="mb-4">
                                        <div class="flex items-center justify-between text-xs text-gray-600 mb-2">
                                            <span>Progress Pembelajaran</span>
                                            <span class="font-semibold">75%</span>
                                        </div>
                                        <div class="h-2 bg-gray-300 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-red-500 to-yellow-400 rounded-full" style="width: 75%"></div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex items-center space-x-3">
                                        <a href="/peserta/kursus/{{ $p->kursus_id }}" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105">
                                            <i class="bi bi-eye mr-2"></i>
                                            Lihat Detail
                                        </a>
                                        <a href="/peserta/kursus/{{ $p->kursus_id }}/risalah" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-800 hover:to-gray-900 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105">
                                            <i class="bi bi-file-earmark mr-2"></i>
                                            Risalah
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="text-6xl text-gray-300 mb-4">
                                    <i class="bi bi-inbox"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Kursus</h3>
                                <p class="text-gray-600 mb-6">Anda belum mendaftar di kursus manapun.</p>
                                <a href="/peserta/kursus" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105">
                                    <i class="bi bi-plus-circle mr-2"></i>
                                    Lihat Daftar Kursus Tersedia
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Access -->
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-black to-gray-800 px-8 py-6">
                        <h2 class="text-xl font-bold text-yellow-400 flex items-center">
                            <i class="bi bi-lightning mr-2"></i>
                            Akses Cepat
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="/peserta/kursus" class="flex items-center px-4 py-3 bg-gradient-to-r from-red-50 to-white hover:from-red-100 hover:to-red-50 border-l-4 border-red-500 hover:border-red-600 font-semibold text-gray-900 rounded-lg transition-all duration-200 transform hover:translate-x-1">
                            <i class="bi bi-book text-red-500 text-xl mr-3"></i>
                            <span>Semua Kursus</span>
                        </a>
                        <a href="/peserta/pendaftaran" class="flex items-center px-4 py-3 bg-gradient-to-r from-yellow-50 to-white hover:from-yellow-100 hover:to-yellow-50 border-l-4 border-yellow-400 hover:border-yellow-500 font-semibold text-gray-900 rounded-lg transition-all duration-200 transform hover:translate-x-1">
                            <i class="bi bi-clipboard text-yellow-500 text-xl mr-3"></i>
                            <span>Pendaftaran Saya</span>
                        </a>
                        <a href="/peserta/riwayat-pembayaran" class="flex items-center px-4 py-3 bg-gradient-to-r from-gray-50 to-white hover:from-gray-100 hover:to-gray-50 border-l-4 border-gray-700 hover:border-gray-900 font-semibold text-gray-900 rounded-lg transition-all duration-200 transform hover:translate-x-1">
                            <i class="bi bi-receipt text-gray-700 text-xl mr-3"></i>
                            <span>Riwayat Pembayaran</span>
                        </a>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-2xl shadow-2xl overflow-hidden text-white p-6">
                    <h3 class="text-lg font-bold mb-4 flex items-center">
                        <i class="bi bi-info-circle-fill mr-2"></i>
                        Info Penting
                    </h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start">
                            <span class="text-yellow-300 mr-2">•</span>
                            <span>Pastikan pembayaran selesai sebelum pembelajaran dimulai</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-yellow-300 mr-2">•</span>
                            <span>Hadir tepat waktu untuk setiap sesi kursus</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-yellow-300 mr-2">•</span>
                            <span>Hubungi admin jika ada pertanyaan</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* Gradient text effect */
    .gradient-text {
        background: linear-gradient(135deg, #EF4444 0%, #FCD34D 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Hover effects */
    @media (prefers-reduced-motion: no-preference) {
        * {
            scroll-behavior: smooth;
        }
    }
</style>
@endsection
