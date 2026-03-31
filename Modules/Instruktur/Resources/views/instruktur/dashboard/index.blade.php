@extends('instruktur::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard Instruktur
            </h2>
        </div>
    </div>

    @php
        $instruktur = auth()->user()->instruktur;
        $kursus = \App\Models\Kursus::where('instruktur_id', $instruktur->id)->get();
        $totalPeserta = $kursus->sum(function($k) { return $k->pendaftarans()->count(); });
        $totalRisalah = $kursus->sum(function($k) { return $k->risalahs()->count(); });
    @endphp

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Kursus -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl shadow-lg p-6 flex items-center gap-4">
            <div class="flex-shrink-0">
                <i class="bi bi-book text-yellow-400 text-4xl"></i>
            </div>
            <div>
                <div class="text-gray-400 text-sm font-semibold mb-1">Total Kursus</div>
                <div class="text-3xl font-bold text-white">{{ $kursus->count() }}</div>
            </div>
        </div>
        <!-- Total Peserta -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl shadow-lg p-6 flex items-center gap-4">
            <div class="flex-shrink-0">
                <i class="bi bi-people text-green-400 text-4xl"></i>
            </div>
            <div>
                <div class="text-gray-400 text-sm font-semibold mb-1">Total Peserta</div>
                <div class="text-3xl font-bold text-white">{{ $totalPeserta }}</div>
            </div>
        </div>
        <!-- Total Pertemuan -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl shadow-lg p-6 flex items-center gap-4">
            <div class="flex-shrink-0">
                <i class="bi bi-calendar-event text-blue-400 text-4xl"></i>
            </div>
            <div>
                <div class="text-gray-400 text-sm font-semibold mb-1">Total Pertemuan</div>
                <div class="text-3xl font-bold text-white">{{ $totalRisalah }}</div>
            </div>
        </div>
    <div class="mb-8 w-full max-w-none px-0">
        <h2 class="text-2xl font-bold text-white mb-6">Kursus yang Anda Ajarkan</h2>
        @if($kursus->count() > 0)
            <div class="flex flex-wrap gap-6">
            @foreach($kursus as $k)
                <div class="w-full md:w-1/2 lg:w-1/3">
                    <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-2xl shadow-lg flex flex-col justify-between p-8 mb-6 h-full hover:border-yellow-500/50 transition-colors duration-200">
                        <div class="flex flex-col flex-1 min-w-0">
                            <h3 class="text-2xl font-bold text-white mb-1 flex items-center break-words">
                                <i class="bi bi-bookmark text-yellow-400 mr-2"></i>{{ $k->nama }}
                            </h3>
                            <div class="text-gray-400 mb-1 flex items-center text-base break-words">
                                <i class="bi bi-folder mr-2"></i>{{ $k->program->nama ?? '-' }}
                            </div>
                            <div class="text-gray-400 flex items-center text-base mb-4 break-words">
                                <i class="bi bi-bookmark mr-2"></i>{{ $k->level->nama ?? '-' }}
                            </div>
                        </div>
                        <div class="flex flex-row gap-8 items-center flex-shrink-0 mb-4">
                            <div class="text-center flex-1">
                                <div class="text-sm text-gray-400 font-semibold mb-1">Peserta</div>
                                <div class="text-2xl font-bold text-yellow-400">{{ $k->pendaftarans()->count() }}</div>
                            </div>
                            <div class="text-center flex-1">
                                <div class="text-sm text-gray-400 font-semibold mb-1">Pertemuan</div>
                                <div class="text-2xl font-bold text-yellow-400">{{ $k->risalahs()->count() }}</div>
                            </div>
                        </div>
                        <a href="{{ url('/instruktur/kursus/'.$k->id) }}" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 text-white font-semibold rounded-lg transform hover:scale-105 transition-all duration-200 text-base whitespace-nowrap w-full">
                            <i class="bi bi-arrow-right mr-2"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
            </div>
        @else
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center h-24 w-24 bg-gray-700/50 rounded-full mb-6">
                    <i class="bi bi-book text-5xl text-gray-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Belum Ada Kursus</h2>
                <p class="text-gray-400 mb-8">Hubungi Admin untuk mendapatkan kursus</p>
            </div>
        @endif
    </div>
@endsection
