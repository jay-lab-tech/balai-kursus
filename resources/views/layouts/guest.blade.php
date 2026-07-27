<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Balai Kursus') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body x-data="guestAuthNavigation()" class="min-h-screen bg-[#f5f7fa] font-sans text-slate-900 antialiased">
        <div class="min-h-screen lg:grid lg:grid-cols-[1.02fr_.98fr]">
            <aside class="relative hidden overflow-hidden bg-[#102a43] px-10 py-10 text-white lg:flex lg:flex-col xl:px-16">
                <div class="absolute -right-28 -top-28 h-80 w-80 rounded-full border-[32px] border-white/5"></div>
                <div class="absolute -bottom-36 -left-24 h-96 w-96 rounded-full border-[48px] border-amber-400/10"></div>

                <a href="/" class="relative flex items-center gap-4">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white p-2 shadow-xl shadow-black/10">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Balai Kursus" class="h-full w-full object-contain">
                    </span>
                    <span>
                        <span class="block text-[11px] font-semibold uppercase tracking-[0.28em] text-amber-300">UPI · Balai Bahasa</span>
                        <span class="mt-1 block text-lg font-bold tracking-tight">Balai Kursus</span>
                    </span>
                </a>

                <div class="relative my-auto max-w-xl py-16">
                    <p class="mb-5 text-sm font-semibold uppercase tracking-[0.22em] text-sky-200">Pusat pembelajaran bahasa</p>
                    <h1 class="max-w-lg text-4xl font-extrabold leading-[1.1] tracking-tight xl:text-5xl">
                        Ruang belajar yang membuat progres terasa nyata.
                    </h1>
                    <p class="mt-6 max-w-md text-base leading-7 text-slate-300">
                        Kelola program, kelas, jadwal, materi, dan hasil belajar dalam satu alur yang rapi.
                    </p>

                    <div class="mt-10 grid max-w-md grid-cols-2 gap-3">
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-sm">
                            <p class="text-2xl font-bold text-white">01</p>
                            <p class="mt-1 text-xs leading-5 text-slate-300">Pilih program belajar</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-sm">
                            <p class="text-2xl font-bold text-amber-300">02</p>
                            <p class="mt-1 text-xs leading-5 text-slate-300">Tumbuh bersama mentor</p>
                        </div>
                    </div>
                </div>

                <p class="relative text-xs text-slate-400">Platform administrasi dan pembelajaran Balai Kursus.</p>
            </aside>

            <main class="flex min-h-screen flex-col px-5 py-6 sm:px-8 lg:px-12 xl:px-20">
                <div class="flex items-center justify-between lg:justify-end">
                    <a href="/" class="flex items-center gap-3 lg:hidden">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white p-1.5 shadow-sm ring-1 ring-slate-200">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo Balai Kursus" class="h-full w-full object-contain">
                        </span>
                        <span class="text-sm font-bold text-[#102a43]">Balai Kursus</span>
                    </a>

                    <nav class="flex items-center gap-2" aria-label="Navigasi autentikasi">
                        <a href="{{ route('information-board') }}" class="inline-flex rounded-full border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-sky-200 hover:text-sky-800 sm:px-4"><span class="sm:hidden">Info</span><span class="hidden sm:inline">Papan Informasi</span></a>
                        <div class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white p-1.5 shadow-sm">
                        <a href="{{ route('login') }}" @click.prevent="switchAuth('{{ route('login') }}', 'login')" :class="activeAuth === 'login' ? 'bg-[#102a43] text-white shadow-sm' : 'text-slate-500 hover:text-slate-900'" class="rounded-full px-4 py-2 text-sm font-semibold transition">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" @click.prevent="switchAuth('{{ route('register') }}', 'register')" :class="activeAuth === 'register' ? 'bg-amber-400 text-slate-950 shadow-sm' : 'text-slate-500 hover:text-slate-900'" class="rounded-full px-4 py-2 text-sm font-semibold transition">Daftar</a>
                        @endif
                        </div>
                    </nav>
                </div>

                <div class="mx-auto flex w-full max-w-lg flex-1 flex-col justify-center py-12">
                    <div class="mb-8">
                        <p data-auth-kicker class="mb-3 text-xs font-bold uppercase tracking-[0.2em] text-amber-600">{{ request()->routeIs('register') ? 'Mulai perjalananmu' : 'Selamat datang kembali' }}</p>
                        <h2 data-auth-heading class="text-3xl font-extrabold tracking-tight text-[#102a43] sm:text-4xl">
                            {{ request()->routeIs('register') ? 'Buat akun baru.' : 'Masuk ke ruang belajarmu.' }}
                        </h2>
                        <p data-auth-description class="mt-3 max-w-md text-sm leading-6 text-slate-500">
                            {{ request()->routeIs('register') ? 'Lengkapi data di bawah untuk membuat akun peserta.' : 'Gunakan akunmu untuk melanjutkan pengelolaan kelas dan pembelajaran.' }}
                        </p>
                    </div>

                    <div data-auth-content class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-[0_24px_70px_-35px_rgba(16,42,67,0.35)] transition-opacity sm:p-8" :class="loading ? 'pointer-events-none opacity-60' : ''">
                        {{ $slot }}
                    </div>

                    <p class="mt-7 text-center text-xs leading-5 text-slate-400">Dengan melanjutkan, kamu menyetujui penggunaan platform untuk kebutuhan pembelajaran dan administrasi kursus.</p>
                </div>
            </main>
        </div>
    </body>
</html>
