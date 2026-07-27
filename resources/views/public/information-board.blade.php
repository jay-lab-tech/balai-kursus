<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Papan Informasi Kursus</title>
    @if ($displayMode)
        <meta http-equiv="refresh" content="60">
    @endif
    @php
        $manifestPath = public_path('build/manifest.json');
        $manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
    @endphp
    @if ($cssFile)
        <link rel="stylesheet" href="{{ asset('build/' . $cssFile) }}">
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0b2035;
        }

        ::-webkit-scrollbar-thumb {
            background: #f59e0b;
            border-radius: 999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #d97706;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.32);
            backdrop-filter: blur(16px);
        }

        .hero-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: minmax(0, 1.35fr) minmax(280px, 0.65fr);
        }

        .brand-mark {
            width: 5.25rem;
            height: 5.25rem;
        }

        .brand-mark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .schedule-card {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.09), rgba(7, 29, 49, 0.56));
            box-shadow: 0 20px 48px rgba(0, 0, 0, 0.28);
            opacity: 0;
            transform: translateY(18px);
            animation: fadeInUp 0.55s ease-out forwards;
        }

        .schedule-card::after {
            content: "";
            position: absolute;
            inset: auto 0 0 0;
            height: 4px;
            background: linear-gradient(90deg, rgba(250, 204, 21, 0.95), rgba(14, 165, 233, 0.95));
        }

        .schedule-card.ongoing {
            border-color: rgba(250, 204, 21, 0.35);
            box-shadow: 0 26px 56px rgba(14, 165, 233, 0.2);
        }

        .schedule-card.upcoming {
            border-color: rgba(250, 204, 21, 0.18);
        }

        .schedule-card.finished,
        .schedule-card.unscheduled {
            opacity: 0.92;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border-radius: 999px;
            padding: 0.5rem 0.9rem;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .status-pill.ongoing {
            background: rgba(250, 204, 21, 0.18);
            color: #fde68a;
        }

        .status-pill.upcoming {
            background: rgba(59, 130, 246, 0.18);
            color: #bfdbfe;
        }

        .status-pill.finished,
        .status-pill.unscheduled {
            background: rgba(255, 255, 255, 0.08);
            color: #d1d5db;
        }

        .display-board .page-shell {
            max-width: 100rem;
        }

        .display-board .hero-grid {
            grid-template-columns: minmax(0, 1.2fr) minmax(360px, 0.8fr);
        }

        .display-board .display-title {
            font-size: clamp(2.5rem, 4vw, 4.4rem);
        }

        .display-board .clock-value {
            font-size: clamp(2.75rem, 4.2vw, 4.8rem);
        }

        .display-board .schedule-grid {
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        }

        .display-board .schedule-card {
            min-height: 18rem;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1024px) {
            .hero-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            .schedule-card {
                animation: none;
                opacity: 1;
                transform: none;
            }
        }
    </style>
</head>
<body class="{{ $displayMode ? 'display-board' : '' }} overflow-x-hidden bg-gradient-to-br from-[#102a43] via-[#173f5f] to-[#061a2b] text-white">
    <div class="page-shell mx-auto min-h-screen max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <header class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white p-1.5 shadow-lg shadow-black/10">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Balai Kursus" class="h-full w-full object-contain">
                </span>
                <span>
                    <span class="block text-[10px] font-semibold uppercase tracking-[0.24em] text-amber-300">UPI · Balai Bahasa</span>
                    <span class="mt-0.5 block text-base font-bold text-white">Balai Kursus</span>
                </span>
            </a>
            <nav class="flex items-center gap-2" aria-label="Navigasi papan informasi">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/20">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Kembali ke Login</span>
                </a>
            </nav>
        </header>

        <section class="glass-panel rounded-[2rem] p-6 sm:p-8">
            <div class="hero-grid items-stretch">
                <div class="flex h-full flex-col justify-between gap-8">
                    <div class="flex items-start gap-4">
                        <div class="brand-mark flex shrink-0 items-center justify-center rounded-3xl bg-white p-3 shadow-2xl shadow-black/30">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo Balai Kursus">
                        </div>
                        <div class="min-w-0">
                            <div class="inline-flex items-center gap-2 rounded-full border border-sky-400/30 bg-sky-400/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] text-amber-300">
                                <i class="bi bi-broadcast-pin text-sky-300"></i>
                                Informasi Publik
                            </div>
                            <h1 class="display-title mt-5 text-4xl font-bold leading-tight text-white">
                                Papan Informasi Kursus
                            </h1>
                            <p class="mt-3 max-w-3xl text-base text-gray-300 sm:text-lg">
                                Jadwal kursus untuk {{ $todayLabel }} dengan tampilan yang selaras dengan portal peserta Balai Kursus.
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-3xl bg-gradient-to-br from-yellow-400 to-yellow-500 p-5 text-gray-900 shadow-xl">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] opacity-80">Jadwal Hari Ini</p>
                            <p class="mt-3 text-4xl font-bold">{{ $jadwals->count() }}</p>
                            <p class="mt-2 text-sm opacity-80">Total sesi yang terjadwal hari ini.</p>
                        </div>
                        <div class="rounded-3xl bg-gradient-to-br from-sky-600 to-sky-700 p-5 text-white shadow-xl">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-100">Sedang Berlangsung</p>
                            <p class="mt-3 text-4xl font-bold">{{ $ongoingJadwals->count() }}</p>
                            <p class="mt-2 text-sm text-sky-100/90">Sesi aktif yang sedang berjalan sekarang.</p>
                        </div>
                        <div class="rounded-3xl border border-yellow-400/20 bg-gradient-to-br from-gray-900 to-black p-5 text-white shadow-xl">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-yellow-300">Mode Tampilan</p>
                            <p class="mt-3 text-2xl font-bold">{{ $displayMode ? 'Display' : 'Normal' }}</p>
                            <p class="mt-2 text-sm text-gray-300">
                                {{ $displayMode ? 'Auto-refresh aktif setiap 60 detik.' : 'Mode biasa untuk browser umum.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex h-full flex-col justify-between gap-4 rounded-[1.75rem] border border-white/10 bg-black/25 p-6 shadow-2xl">
                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-gray-400">Waktu Saat Ini</p>
                            @if ($displayMode)
                                <span class="inline-flex items-center gap-2 rounded-full border border-sky-400/30 bg-sky-400/10 px-3 py-1 text-xs font-semibold text-amber-300">
                                    <span class="h-2 w-2 rounded-full bg-yellow-400"></span>
                                    Auto refresh
                                </span>
                            @endif
                        </div>
                        <div class="clock-value mt-4 text-5xl font-bold leading-none text-white" id="live-clock">{{ $generatedAt->format('H:i:s') }}</div>
                        <p class="mt-4 text-base text-gray-300">{{ $todayLabel }}</p>
                        <p class="mt-2 text-sm text-gray-400">
                            {{ $displayMode ? 'Mode display aktif. Halaman diperbarui otomatis setiap 60 detik.' : 'Gunakan mode display untuk monitor, lobi, atau televisi informasi.' }}
                        </p>
                    </div>

                    <div class="grid gap-3 {{ $displayMode ? '' : 'sm:grid-cols-2' }}">
                        <a class="inline-flex items-center justify-center rounded-2xl border px-4 py-3 text-sm font-semibold transition duration-200 {{ $displayMode ? 'border-white/10 bg-white/5 text-gray-200 hover:bg-white/10' : 'border-sky-400/30 bg-sky-500/20 text-amber-300' }}"
                           href="{{ url('/papan-informasi') }}">
                            <i class="bi bi-window mr-2"></i>Mode Normal
                        </a>
                        <a class="inline-flex items-center justify-center rounded-2xl border px-4 py-3 text-sm font-semibold transition duration-200 {{ $displayMode ? 'border-sky-400/30 bg-sky-600 text-white hover:bg-sky-500' : 'border-white/10 bg-white/5 text-gray-200 hover:bg-white/10' }}"
                           href="{{ url('/papan-informasi?display=1') }}">
                            <i class="bi bi-tv mr-2"></i>Mode Display
                        </a>
                        @if ($displayMode)
                            <button type="button" id="fullscreen-toggle" class="inline-flex items-center justify-center rounded-2xl border border-amber-400/20 bg-amber-400/10 px-4 py-3 text-sm font-semibold text-amber-300 transition duration-200 hover:bg-amber-400/20">
                                <i class="bi bi-arrows-fullscreen mr-2"></i>Layar Penuh
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <div class="mt-8 space-y-8" id="board-sections">
            @if ($ongoingJadwals->isNotEmpty())
                <section class="glass-panel rounded-[2rem] p-6 sm:p-8">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h2 class="text-3xl font-bold text-white">
                                <i class="bi bi-lightning-charge-fill mr-3 text-yellow-400"></i>Sedang Berlangsung
                            </h2>
                            <p class="mt-2 text-gray-400">Sesi yang sedang aktif saat ini ditonjolkan di bagian atas agar mudah terlihat.</p>
                        </div>
                        <span class="inline-flex items-center rounded-full border border-yellow-400/20 bg-yellow-400/10 px-4 py-2 text-sm font-semibold text-yellow-300">
                            {{ $ongoingJadwals->count() }} jadwal aktif
                        </span>
                    </div>

                    <div class="schedule-grid mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($ongoingJadwals as $index => $jadwal)
                            @include('public.partials.information-board-card', ['jadwal' => $jadwal, 'delayIndex' => $index])
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-white">
                            <i class="bi bi-calendar3 mr-3 text-sky-300"></i>Jadwal Hari Ini
                        </h2>
                        <p class="mt-2 text-gray-400">Semua sesi hari ini ditampilkan berurutan berdasarkan waktu mulai.</p>
                    </div>
                    <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-gray-200">
                        {{ $jadwals->count() }} jadwal ditemukan
                    </span>
                </div>

                @if ($jadwals->isEmpty())
                    <div class="mt-6 rounded-[1.75rem] border border-dashed border-white/10 bg-black/20 px-6 py-16 text-center">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white/5 text-4xl text-yellow-300">
                            <i class="bi bi-calendar-x"></i>
                        </div>
                        <h2 class="mt-6 text-3xl font-bold text-white">Tidak ada jadwal kursus hari ini</h2>
                        <p class="mt-3 text-base text-gray-400">Silakan cek kembali besok untuk informasi jadwal terbaru.</p>
                    </div>
                @else
                    <div class="schedule-grid mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($jadwals as $index => $jadwal)
                            @include('public.partials.information-board-card', ['jadwal' => $jadwal, 'delayIndex' => $ongoingJadwals->count() + $index])
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>

    <script>
        const clockEl = document.getElementById('live-clock');
        const fullscreenToggle = document.getElementById('fullscreen-toggle');
        const boardSections = document.getElementById('board-sections');

        if (clockEl) {
            const updateClock = () => {
                const now = new Date();
                clockEl.textContent = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                });
            };

            updateClock();
            window.setInterval(updateClock, 1000);
        }

        if (fullscreenToggle) {
            const syncFullscreenLabel = () => {
                fullscreenToggle.innerHTML = document.fullscreenElement
                    ? '<i class="bi bi-fullscreen-exit mr-2"></i>Keluar Fullscreen'
                    : '<i class="bi bi-arrows-fullscreen mr-2"></i>Layar Penuh';
            };

            fullscreenToggle.addEventListener('click', async () => {
                if (!document.fullscreenElement) {
                    await document.documentElement.requestFullscreen?.();
                } else {
                    await document.exitFullscreen?.();
                }
                syncFullscreenLabel();
            });

            document.addEventListener('fullscreenchange', syncFullscreenLabel);
            syncFullscreenLabel();
        }

        if (document.body.classList.contains('display-board') && boardSections && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            let scrollInterval;
            let direction = 1;

            const clearBoardScroll = () => {
                if (scrollInterval) {
                    window.clearInterval(scrollInterval);
                    scrollInterval = null;
                }
            };

            const enableScrollMode = () => {
                clearBoardScroll();

                const maxScroll = Math.max(document.body.scrollHeight - window.innerHeight, 0);
                if (maxScroll < 120) {
                    window.scrollTo({ top: 0, behavior: 'auto' });
                    return;
                }

                window.scrollTo({ top: 0, behavior: 'auto' });
                direction = 1;

                scrollInterval = window.setInterval(() => {
                    const nextTop = window.scrollY + (direction * 1.25);
                    window.scrollTo({ top: nextTop, behavior: 'auto' });

                    if (window.scrollY >= maxScroll) {
                        direction = -1;
                    } else if (window.scrollY <= 0) {
                        direction = 1;
                    }
                }, 32);
            };

            enableScrollMode();
            window.addEventListener('resize', enableScrollMode);
        }
    </script>
</body>
</html>
