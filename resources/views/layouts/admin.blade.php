<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') · Balai Kursus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body>
@php
    /* Navigasi admin. `count` boleh diisi lewat $navCounts dari controller
       atau view composer; kalau kosong, lencana tidak dirender. */
    $navCounts = $navCounts ?? [];

    $navGroups = [
        'Ikhtisar' => [
            ['key' => 'dashboard', 'label' => 'Beranda', 'route' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard'), 'icon' => 'bi-house-door'],
        ],
        'Akademik' => [
            ['key' => 'program', 'label' => 'Program', 'route' => route('admin.program.index'), 'active' => request()->routeIs('admin.program.*'), 'icon' => 'bi-diagram-3'],
            ['key' => 'level', 'label' => 'Level', 'route' => route('admin.level.index'), 'active' => request()->routeIs('admin.level.*'), 'icon' => 'bi-bar-chart-steps'],
            ['key' => 'kursus', 'label' => 'Kelas Program', 'route' => route('admin.kursus.index'), 'active' => request()->routeIs('admin.kursus.*'), 'icon' => 'bi-journal-bookmark'],
            ['key' => 'jadwal', 'label' => 'Jadwal', 'route' => route('admin.jadwal.all'), 'active' => request()->routeIs('admin.jadwal.*'), 'icon' => 'bi-calendar3'],
            ['key' => 'lokasi', 'label' => 'Lokasi & Ruang', 'route' => route('admin.lokasi.index'), 'active' => request()->routeIs('admin.lokasi.*') || request()->routeIs('admin.kelas.*'), 'icon' => 'bi-geo-alt'],
        ],
        'Peserta' => [
            ['key' => 'peserta', 'label' => 'Peserta', 'route' => route('admin.peserta.index'), 'active' => request()->routeIs('admin.peserta.*'), 'icon' => 'bi-people'],
            ['key' => 'nilai', 'label' => 'Nilai', 'route' => route('admin.score.index'), 'active' => request()->routeIs('admin.score.*'), 'icon' => 'bi-clipboard-data'],
            ['key' => 'absensi', 'label' => 'Risalah & Absensi', 'route' => route('admin.risalah.all'), 'active' => request()->routeIs('admin.risalah.*') || request()->routeIs('admin.absensi.*'), 'icon' => 'bi-journal-text'],
            ['key' => 'sertifikat', 'label' => 'Sertifikat', 'route' => route('admin.certificates.index'), 'active' => request()->routeIs('admin.certificates.*') || request()->routeIs('admin.templates.*'), 'icon' => 'bi-patch-check'],
        ],
        'Sumber Daya' => [
            ['key' => 'instruktur', 'label' => 'Instruktur', 'route' => route('admin.instruktur.index'), 'active' => request()->routeIs('admin.instruktur.*'), 'icon' => 'bi-person-badge'],
        ],
    ];

    $me = Auth::user();
    $inisial = collect(explode(' ', trim($me->name)))->take(2)->map(fn ($p) => mb_substr($p, 0, 1))->implode('');
@endphp
<div x-data="{ open: false }" class="bk-shell">

    <aside class="bk-side" :class="open && 'is-buka'">
        <a href="{{ route('admin.dashboard') }}" class="bk-side__brand">
            <span class="bk-side__glyph"><img src="{{ asset('images/logo.png') }}" alt=""></span>
            <span>
                <b>Balai Kursus</b>
                <small>Panel pengelola</small>
            </span>
        </a>

        <nav class="bk-side__scroll" aria-label="Navigasi admin">
            @foreach ($navGroups as $group => $items)
                <div class="bk-side__sec">{{ $group }}</div>
                @foreach ($items as $item)
                    <a href="{{ $item['route'] }}"
                       class="bk-nav {{ $item['active'] ? 'is-aktif' : '' }}"
                       @if ($item['active']) aria-current="page" @endif
                       @click="open = false">
                        <i class="bi {{ $item['icon'] }}" aria-hidden="true"></i>
                        <span>{{ $item['label'] }}</span>
                        @if (! empty($navCounts[$item['key']]))
                            <span class="bk-nav__cnt">{{ $navCounts[$item['key']] }}</span>
                        @endif
                    </a>
                @endforeach
            @endforeach
        </nav>

        <div class="bk-side__foot">
            <div class="bk-side__sec">Tautan</div>
            <a href="{{ url('/papan-informasi') }}" class="bk-side__out">
                <span><i class="bi bi-display" aria-hidden="true"></i> Papan Informasi</span>
                <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
            </a>
            <a href="{{ url('/') }}" class="bk-side__out">
                <span><i class="bi bi-globe2" aria-hidden="true"></i> Lihat Situs</span>
                <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
            </a>

            {{-- Admin sebelumnya tidak punya jalan ke halaman profilnya sendiri. --}}
            <a href="{{ route('profile.edit') }}" class="bk-side__me">
                <span class="bk-side__av">{{ $inisial }}</span>
                <span style="min-width:0">
                    <b>{{ $me->name }}</b>
                    <span>Administrator</span>
                </span>
            </a>
        </div>
    </aside>

    <div x-cloak x-show="open" class="bk-overlay" @click="open = false"></div>

    <main class="bk-main">
        <header class="bk-topbar">
            <div style="display:flex;align-items:center;gap:.75rem;min-width:0">
                <button type="button" class="bk-menubtn" @click="open = true" aria-label="Buka menu">
                    <i class="bi bi-list" aria-hidden="true"></i>
                </button>
                <div style="min-width:0">
                    <div class="bk-topbar__crumb">@yield('page-context', 'Admin')</div>
                    <h1 class="bk-topbar__title">@yield('page-title', 'Beranda')</h1>
                    @hasSection('page-description')
                        <p class="bk-topbar__note">@yield('page-description')</p>
                    @endif
                </div>
            </div>
            <div class="bk-tools">
                <span class="bk-muted bk-nowrap" style="font-size:.78rem">{{ now()->translatedFormat('l, j F Y') }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bk-btn bk-btn--sm">
                        <i class="bi bi-box-arrow-right" aria-hidden="true"></i> Keluar
                    </button>
                </form>
            </div>
        </header>

        <section class="bk-content">@yield('content')</section>
    </main>
</div>
@yield('scripts')
<script>
    /* Tabel berubah jadi daftar bertumpuk di layar kecil; tiap sel butuh label
       kolomnya sendiri. Diisi di sini supaya view tidak perlu mengulang. */
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.bk-table').forEach((t) => {
            const heads = [...t.querySelectorAll('thead th')].map((x) => x.textContent.trim());
            if (!heads.length) return;
            t.querySelectorAll('tbody tr').forEach((r) => {
                [...r.children].forEach((c, i) => {
                    if (!c.dataset.label) c.dataset.label = heads[i] || '';
                });
            });
        });
    });
</script>
</body>
</html>
