
@extends('instruktur::layouts.master')

@section('title', 'Kursus - Instruktur')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-12 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-white mb-2 flex items-center">
                <i class="bi bi-book text-yellow-400 mr-3"></i>Kursus Yang Diampu
            </h1>
            <a href="{{ url('/instruktur/dashboard') }}" class="inline-flex items-center px-5 py-2 border border-gray-500 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors">
                <i class="bi bi-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        @if(count($kursus) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($kursus as $k)
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl shadow-lg p-6 flex flex-col h-full hover:border-yellow-500/50 transition-colors duration-200">
                    <h2 class="text-xl font-bold text-white mb-2 flex items-center">
                        <i class="bi bi-bookmark text-yellow-400 mr-2"></i>{{ $k->nama_kursus ?? $k->nama }}
                    </h2>
                    <div class="text-gray-400 mb-2 flex items-center">
                        <i class="bi bi-folder mr-2"></i>{{ $k->program->nama_program ?? $k->program->nama ?? 'N/A' }}
                    </div>
                    <div class="text-gray-400 mb-4 flex items-center">
                        <i class="bi bi-mortarboard mr-2"></i>{{ $k->level->nama_level ?? $k->level->nama ?? 'N/A' }}
                    </div>
                    <div class="flex gap-4 mb-4">
                        <div class="flex-1 bg-gray-700 rounded-lg p-3 text-center">
                            <div class="text-xs text-gray-400">Peserta</div>
                            <div class="text-2xl font-bold text-yellow-400">{{ $k->peserta_count ?? 0 }}</div>
                        </div>
                        <div class="flex-1 bg-gray-700 rounded-lg p-3 text-center">
                            <div class="text-xs text-gray-400">Pertemuan</div>
                            <div class="text-2xl font-bold text-yellow-400">{{ $k->risalah_count ?? 0 }}</div>
                        </div>
                    </div>
                    <a href="{{ url('/instruktur/kursus/'.$k->id) }}" class="mt-auto inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 text-white font-semibold rounded-lg transform hover:scale-105 transition-all duration-200">
                        <i class="bi bi-arrow-right mr-2"></i>Kelola Pertemuan
                    </a>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center h-24 w-24 bg-gray-700/50 rounded-full mb-6">
                    <i class="bi bi-inbox text-4xl text-gray-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Belum Ada Kursus</h2>
                <p class="text-gray-400 mb-8">Hubungi Admin untuk mendapatkan kursus</p>
            </div>
        @endif
    </div>
</div>
@endsection
