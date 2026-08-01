<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Papan Informasi Kursus · Balai Kursus</title>
    @if ($displayMode)
        <meta http-equiv="refresh" content="60">
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite(['resources/css/app.css'])
</head>
<body class="bk-page {{ $displayMode ? 'is-tv' : '' }}">

<header class="bk-page__bar">
    <a href="{{ url('/') }}" class="bk-page__brand">
        <img src="{{ asset('images/logo.png') }}" alt="">
        <span>
            <b>Balai Kursus</b>
            <small>UPI · Balai Bahasa</small>
        </span>
    </a>

    <nav class="bk-tools" aria-label="Navigasi papan informasi">
        <a href="{{ url('/papan-informasi') }}" class="bk-btn bk-btn--sm {{ $displayMode ? '' : 'bk-btn--pri' }}">
            <i class="bi bi-window" aria-hidden="true"></i>
            <span class="bk-only-lebar">Mode Normal</span>
        </a>
        <a href="{{ url('/papan-informasi?display=1') }}" class="bk-btn bk-btn--sm {{ $displayMode ? 'bk-btn--pri' : '' }}">
            <i class="bi bi-tv" aria-hidden="true"></i>
            <span class="bk-only-lebar">Mode Display</span>
        </a>
        @if ($displayMode)
            <button type="button" id="fullscreen-toggle" class="bk-btn bk-btn--sm">
                <i class="bi bi-arrows-fullscreen" aria-hidden="true"></i>
                <span class="bk-only-lebar">Layar Penuh</span>
            </button>
        @else
            <a href="{{ route('login') }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>
                <span class="bk-only-lebar">Masuk</span>
            </a>
        @endif
    </nav>
</header>

<main class="bk-page__body">

    <section class="bk-board__top">
        <div>
            <p class="bk-hello__kicker">Informasi publik</p>
            <h1>Papan Informasi Kursus</h1>
            <p>Jadwal kursus untuk {{ $todayLabel }}.
                {{ $displayMode
                    ? 'Mode display aktif. Halaman diperbarui otomatis setiap 60 detik.'
                    : 'Gunakan mode display untuk monitor lobi atau televisi informasi.' }}</p>
        </div>

        <div class="bk-board__clock">
            <time id="live-clock" datetime="{{ $generatedAt->format('H:i:s') }}">{{ $generatedAt->format('H:i:s') }}</time>
            <span>Waktu saat ini</span>
        </div>
    </section>

    <div class="bk-stats bk-stats--3">
        <article class="bk-stat">
            <span class="bk-stat__icon"><i class="bi bi-calendar3" aria-hidden="true"></i></span>
            <p class="bk-stat__label">Jadwal hari ini</p>
            <p class="bk-stat__value">{{ $jadwals->count() }}</p>
            <p class="bk-stat__hint">Total sesi yang terjadwal hari ini.</p>
        </article>
        <article class="bk-stat bk-stat--terra">
            <span class="bk-stat__icon"><i class="bi bi-broadcast" aria-hidden="true"></i></span>
            <p class="bk-stat__label">Sedang Berlangsung</p>
            <p class="bk-stat__value">{{ $ongoingJadwals->count() }}</p>
            <p class="bk-stat__hint">Sesi yang sedang berjalan sekarang.</p>
        </article>
        <article class="bk-stat bk-stat--amber">
            <span class="bk-stat__icon"><i class="bi bi-tv" aria-hidden="true"></i></span>
            <p class="bk-stat__label">Mode tampilan</p>
            <p class="bk-stat__value">{{ $displayMode ? 'Display' : 'Normal' }}</p>
            <p class="bk-stat__hint">
                {{ $displayMode ? 'Diperbarui otomatis tiap 60 detik.' : 'Mode biasa untuk peramban.' }}
            </p>
        </article>
    </div>

    <div id="board-sections">
        @if ($ongoingJadwals->isNotEmpty())
            <section class="bk-board__sec">
                <h2><i class="bi bi-broadcast" aria-hidden="true"></i>Sedang Berlangsung</h2>
                <p>{{ $ongoingJadwals->count() }} sesi aktif, ditaruh paling atas agar cepat terlihat.</p>

                <div class="bk-board__grid">
                    @foreach ($ongoingJadwals as $jadwal)
                        @include('public.partials.information-board-card', ['jadwal' => $jadwal])
                    @endforeach
                </div>
            </section>
        @endif

        <section class="bk-board__sec">
            <h2><i class="bi bi-calendar3" aria-hidden="true"></i>Jadwal Hari Ini</h2>
            <p>{{ $jadwals->count() }} sesi, berurutan menurut waktu mulai.</p>

            @if ($jadwals->isEmpty())
                <div class="bk-empty">
                    <span class="bk-empty__icon"><i class="bi bi-calendar-x" aria-hidden="true"></i></span>
                    <h3>Tidak ada jadwal kursus hari ini</h3>
                    <p>Silakan periksa kembali besok untuk informasi jadwal terbaru.</p>
                </div>
            @else
                <div class="bk-board__grid">
                    @foreach ($jadwals as $jadwal)
                        @include('public.partials.information-board-card', ['jadwal' => $jadwal])
                    @endforeach
                </div>
            @endif
        </section>
    </div>

</main>

<script>
    const clockEl = document.getElementById('live-clock');
    const fullscreenToggle = document.getElementById('fullscreen-toggle');
    const boardSections = document.getElementById('board-sections');

    if (clockEl) {
        const updateClock = () => {
            clockEl.textContent = new Date().toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
            });
        };

        updateClock();
        window.setInterval(updateClock, 1000);
    }

    if (fullscreenToggle) {
        const label = fullscreenToggle.querySelector('span');

        const syncFullscreenLabel = () => {
            const penuh = Boolean(document.fullscreenElement);
            fullscreenToggle.querySelector('i').className = penuh ? 'bi bi-fullscreen-exit' : 'bi bi-arrows-fullscreen';
            if (label) {
                label.textContent = penuh ? 'Keluar Layar Penuh' : 'Layar Penuh';
            }
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

    // Mode lobi: gulir bolak-balik perlahan supaya seluruh papan terbaca.
    if (document.body.classList.contains('is-tv') && boardSections
        && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        let scrollInterval = null;
        let direction = 1;

        const enableScrollMode = () => {
            if (scrollInterval) {
                window.clearInterval(scrollInterval);
                scrollInterval = null;
            }

            const maxScroll = Math.max(document.body.scrollHeight - window.innerHeight, 0);
            window.scrollTo({ top: 0, behavior: 'auto' });

            if (maxScroll < 120) {
                return;
            }

            direction = 1;
            scrollInterval = window.setInterval(() => {
                window.scrollTo({ top: window.scrollY + direction * 1.25, behavior: 'auto' });

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
