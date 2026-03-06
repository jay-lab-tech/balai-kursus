@extends('peserta::layouts.student')

@section('title', 'Kursus Saya - Balai Kursus')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-white mb-2">
                <i class="bi bi-bookmark-check text-yellow-400 mr-3"></i>Kursus Saya
            </h1>
            <p class="text-gray-400">Kelola kursus yang telah Anda daftarkan</p>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
        <div class="mb-6 bg-green-500/20 border-l-4 border-green-500 rounded-lg p-4 text-green-400 animate-fade-in-up">
            <div class="flex items-start">
                <i class="bi bi-check-circle mr-3 mt-0.5 flex-shrink-0"></i>
                <div>
                    <p class="font-semibold">Berhasil</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if($pendaftarans && count($pendaftarans) > 0)
            <!-- Courses Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pendaftarans as $p)
                <div class="group rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 cursor-pointer" onclick="window.location.href='/peserta/kursus/{{ $p->kursus->id }}/detail'">
                    <!-- Course Card -->
                    <div class="
                        @if($p->kursus->tanggal_selesai && \Carbon\Carbon::parse($p->kursus->tanggal_selesai)->lt(now()))
                            bg-gradient-to-r from-gray-700/50 to-gray-800/50
                        @else
                            bg-gradient-to-r from-gray-50 to-white
                        @endif
                    ">
                        <!-- Header with Status -->
                        <div class="relative h-32 bg-gradient-to-r from-red-600 to-red-700 flex items-center justify-center">
                            <i class="bi bi-book text-white" style="font-size: 60px; opacity: 0.3;"></i>
                            
                            <!-- Ended Badge -->
                            @if($p->kursus->tanggal_selesai && \Carbon\Carbon::parse($p->kursus->tanggal_selesai)->lt(now()))
                            <div class="absolute top-2 right-2 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold flex items-center space-x-1">
                                <i class="bi bi-x-circle"></i>
                                <span>Selesai</span>
                            </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ $p->kursus->nama }}</h3>

                            <!-- Course Info -->
                            <div class="space-y-2 mb-6 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="bi bi-diagram-3 text-red-500 mr-2 flex-shrink-0"></i>
                                    <span>{{ $p->kursus->program->nama }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="bi bi-bar-chart text-yellow-500 mr-2 flex-shrink-0"></i>
                                    <span>{{ $p->kursus->level->nama }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="bi bi-person text-blue-500 mr-2 flex-shrink-0"></i>
                                    <span>{{ $p->kursus->instruktur->nama_instr ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <!-- Payment Status -->
                            <div class="pb-6 border-b border-gray-200">
                                <p class="text-xs text-gray-500 mb-2 font-semibold">Status Pembayaran</p>
                                @if($p->status_pembayaran === 'selesai')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        <i class="bi bi-check-circle mr-1"></i>Selesai
                                    </span>
                                @elseif($p->status_pembayaran === 'dp')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                        <i class="bi bi-hourglass-split mr-1"></i>DP (Cicilan)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-700">
                                        <i class="bi bi-x-circle mr-1"></i>{{ ucfirst($p->status_pembayaran) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Action Button -->
                            <div class="pt-4">
                                <button onclick="event.stopPropagation(); window.location.href='/peserta/kursus/{{ $p->kursus->id }}/detail'" class="w-full px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-lg transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                                    <i class="bi bi-eye mr-2"></i>
                                    <span>Lihat Detail</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center h-24 w-24 bg-gray-700/50 rounded-full mb-6">
                    <i class="bi bi-inbox text-4xl text-gray-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Belum Ada Kursus</h2>
                <p class="text-gray-400 mb-8">Anda belum mendaftar di kursus apapun. Mulai petualangan pembelajaran Anda sekarang!</p>
                <a href="/peserta/kursus" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-lg transform hover:scale-105 transition-all duration-200">
                    <i class="bi bi-plus-circle mr-2"></i>
                    <span>Jelajahi Kursus Sekarang</span>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
