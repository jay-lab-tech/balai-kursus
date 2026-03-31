@extends('instruktur::layouts.master')

@section('title', 'Kursus Yang Diampu')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center mb-8">
            <i class="bi bi-book text-yellow-400 text-4xl mr-4"></i>
            <h1 class="text-3xl font-bold text-white">Kursus Yang Diampu</h1>
            <a href="{{ url()->previous() }}" class="ml-auto px-5 py-2 border border-gray-400 rounded-lg text-white hover:bg-gray-800 transition flex items-center"><i class="bi bi-arrow-left mr-2"></i>Kembali</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($kursus as $k)
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-2xl shadow-lg p-8 flex flex-col justify-between hover:border-yellow-500/50 transition-colors duration-200">
                <h3 class="text-2xl font-bold text-white mb-2 flex items-center break-words">
                    <i class="bi bi-bookmark text-yellow-400 mr-2"></i>{{ $k->nama }}
                </h3>
                <div class="text-gray-400 mb-2 flex items-center text-base break-words">
                    <i class="bi bi-folder mr-2"></i>{{ $k->program->nama ?? '-' }}
                </div>
                <div class="text-gray-400 flex items-center text-base mb-4 break-words">
                    <i class="bi bi-mortarboard mr-2"></i>{{ $k->level->nama ?? '-' }}
                </div>
                <div class="flex flex-row justify-between items-center mb-4">
                    <div class="text-center">
                        <div class="text-sm text-gray-400 font-semibold mb-1">Peserta</div>
                        <div class="text-2xl font-bold text-yellow-400">{{ $k->pendaftarans()->count() }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm text-gray-400 font-semibold mb-1">Pertemuan</div>
                        <div class="text-2xl font-bold text-yellow-400">{{ $k->risalahs()->count() }}</div>
                    </div>
                </div>
                <a href="{{ url('/instruktur/kursus/'.$k->id) }}" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 text-white font-semibold rounded-lg transform hover:scale-105 transition-all duration-200 text-base whitespace-nowrap w-full"><i class="bi bi-arrow-right mr-2"></i>Kelola Pertemuan</a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
