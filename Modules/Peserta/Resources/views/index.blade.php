@extends('peserta::layouts.student')

@section('title', 'Peserta - Balai Kursus')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto text-center py-20">
        <div class="inline-flex items-center justify-center h-24 w-24 bg-yellow-500/20 rounded-full mb-6">
            <i class="bi bi-mortarboard text-5xl text-yellow-400"></i>
        </div>
        <h1 class="text-4xl font-bold text-white mb-4">Selamat Datang di Balai Kursus</h1>
        <p class="text-xl text-gray-400 mb-8">Platform pembelajaran terpercaya untuk mengembangkan skill Anda</p>
        <div class="flex gap-4 justify-center flex-wrap">
            <a href="/peserta/dashboard" class="px-8 py-3 bg-gradient-to-r from-sky-600 to-sky-700 hover:from-sky-700 hover:to-sky-800 text-white font-semibold rounded-lg transform hover:scale-105 transition-all duration-200">
                <i class="bi bi-speedometer2 mr-2"></i>Ke Dashboard
            </a>
            <a href="/peserta/kursus" class="px-8 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-semibold rounded-lg transform hover:scale-105 transition-all duration-200">
                <i class="bi bi-book mr-2"></i>Jelajahi Kursus
            </a>
        </div>
    </div>
</div>
@endsection
