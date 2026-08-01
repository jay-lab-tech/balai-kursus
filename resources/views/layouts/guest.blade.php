@props(['judul' => null, 'kicker' => null, 'lede' => null])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ request()->routeIs('register') ? 'Daftar' : 'Masuk' }} · Balai Kursus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="guestAuthNavigation()">
@php
    $daftar = request()->routeIs('register');
@endphp
<div class="bk-gate">

    <aside class="bk-gate__aside">
        <a href="/" class="bk-gate__brand">
            <img src="{{ asset('images/logo.png') }}" alt="">
            <span>
                <b>Balai Kursus</b>
                <small>UPI · Balai Bahasa</small>
            </span>
        </a>

        <div class="bk-gate__pitch">
            <p class="bk-hello__kicker">Pusat pembelajaran bahasa</p>
            <h2>Satu tempat untuk belajar, mengajar, dan mengurus kelas.</h2>
            <p>Program, jadwal, absensi, nilai, dan sertifikat tersimpan dalam satu alur yang bisa ditelusuri.</p>

            <ol class="bk-gate__steps">
                <li><b>01</b><span>Pilih program yang sesuai kebutuhan Anda.</span></li>
                <li><b>02</b><span>Ikuti tes penempatan untuk menentukan level.</span></li>
                <li><b>03</b><span>Mulai belajar bersama instruktur di kelas Anda.</span></li>
            </ol>
        </div>

        <p class="bk-gate__foot">Sistem administrasi dan pembelajaran Balai Kursus.</p>
    </aside>

    <main class="bk-gate__main">
        <div class="bk-gate__top">
            <a href="/" class="bk-gate__logo">
                <img src="{{ asset('images/logo.png') }}" alt="">
                <span>Balai Kursus</span>
            </a>

            <nav class="bk-tools" aria-label="Navigasi masuk">
                <a href="{{ route('information-board') }}" class="bk-btn bk-btn--sm">
                    <i class="bi bi-display" aria-hidden="true"></i>
                    <span class="bk-only-lebar">Papan Informasi</span>
                </a>
                <div class="bk-gate__swap">
                    <a href="{{ route('login') }}"
                       @click.prevent="switchAuth('{{ route('login') }}', 'login')"
                       :class="activeAuth === 'login' && 'is-aktif'"
                       class="{{ $daftar ? '' : 'is-aktif' }}">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           @click.prevent="switchAuth('{{ route('register') }}', 'register')"
                           :class="activeAuth === 'register' && 'is-aktif'"
                           class="{{ $daftar ? 'is-aktif' : '' }}">Daftar</a>
                    @endif
                </div>
            </nav>
        </div>

        <div class="bk-gate__box">
            <p data-auth-kicker class="bk-gate__kicker">{{ $kicker ?? ($daftar ? 'Mulai di sini' : 'Selamat datang kembali') }}</p>
            <h1 data-auth-heading>{{ $judul ?? ($daftar ? 'Buat akun peserta.' : 'Masuk ke ruang Anda.') }}</h1>
            <p data-auth-description>{{ $lede ?? ($daftar ? 'Lengkapi data di bawah untuk mendaftar sebagai peserta.' : 'Gunakan akun Anda untuk melanjutkan.') }}</p>

            <div data-auth-content class="bk-gate__card" :class="loading && 'is-muat'">
                {{ $slot }}
            </div>

            <p class="bk-gate__fine">Dengan melanjutkan, Anda menyetujui penggunaan sistem ini untuk keperluan pembelajaran dan administrasi kursus.</p>
        </div>
    </main>
</div>
</body>
</html>
