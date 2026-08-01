<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ruang belajar') · Balai Kursus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* SEMENTARA — dihapus di akhir rollout view peserta (Fase 4).
           Sebagian view peserta masih memakai utilitas tema gelap lama.
           Blok ini menyelaraskannya dengan palet Balai Hangat sampai tiap
           view ditulis ulang. Cari "SEMENTARA" untuk menemukannya kembali. */
        .bk-content .text-white,
        .bk-content .text-slate-100, .bk-content .text-slate-200,
        .bk-content .text-sky-100 { color: var(--bk-ink); }
        .bk-content .text-slate-300, .bk-content .text-slate-400,
        .bk-content .text-gray-300, .bk-content .text-gray-400 { color: var(--bk-ink-2); }
        .bk-content .text-sky-300, .bk-content .text-sky-400,
        .bk-content .text-yellow-300, .bk-content .text-yellow-400,
        .bk-content .text-emerald-300 { color: var(--bk-sage-700); }
        .bk-content .bg-white\/5, .bk-content .bg-white\/10,
        .bk-content .bg-black\/20, .bk-content .bg-slate-900,
        .bk-content .bg-slate-800 { background-color: var(--bk-sand-100); }
        .bk-content [class*="bg-gradient"] { background-image: none; }
        .bk-content .border-white\/10, .bk-content .border-white\/5 { border-color: var(--bk-sand); }
        .bk-content .rounded-3xl, .bk-content .rounded-2xl { border-radius: var(--bk-r); }
    </style>
    @yield('styles')
</head>
<body>
@php
    $navItems = [
        ['label' => 'Beranda', 'url' => route('peserta.dashboard'), 'active' => request()->is('peserta/dashboard*'), 'icon' => 'bi-house-door'],
        ['label' => 'Program', 'url' => url('/peserta/program'), 'active' => request()->is('peserta/program*'), 'icon' => 'bi-compass'],
        ['label' => 'Kelas Saya', 'url' => url('/peserta/kursus/saya'), 'active' => request()->is('peserta/kursus*'), 'icon' => 'bi-journal-bookmark'],
        ['label' => 'Pendaftaran', 'url' => url('/peserta/pendaftaran'), 'active' => request()->is('peserta/pendaftaran*'), 'icon' => 'bi-clipboard-check'],
        ['label' => 'Pembayaran', 'url' => url('/peserta/riwayat-pembayaran'), 'active' => request()->is('peserta/riwayat-pembayaran*'), 'icon' => 'bi-receipt'],
        ['label' => 'Sertifikat', 'url' => route('profile.certificates'), 'active' => request()->is('profile/certificates*'), 'icon' => 'bi-patch-check'],
    ];

    $me = Auth::user();
    $inisial = $me ? collect(explode(' ', trim($me->name)))->take(2)->map(fn ($p) => mb_substr($p, 0, 1))->implode('') : 'P';
@endphp
<div x-data="{ open: false }" class="bk-shell">

    <aside class="bk-side" :class="open && 'is-buka'">
        <a href="{{ route('peserta.dashboard') }}" class="bk-side__brand">
            <span class="bk-side__glyph"><img src="{{ asset('images/logo.png') }}" alt=""></span>
            <span>
                <b>Balai Kursus</b>
                <small>Ruang peserta</small>
            </span>
        </a>

        <nav class="bk-side__scroll" aria-label="Navigasi peserta">
            <div class="bk-side__sec">Ruang belajar</div>
            @foreach ($navItems as $item)
                <a href="{{ $item['url'] }}"
                   class="bk-nav {{ $item['active'] ? 'is-aktif' : '' }}"
                   @if ($item['active']) aria-current="page" @endif
                   @click="open = false">
                    <i class="bi {{ $item['icon'] }}" aria-hidden="true"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="bk-side__foot">
            <div class="bk-side__sec">Tautan</div>
            <a href="{{ url('/papan-informasi') }}" class="bk-side__out">
                <span><i class="bi bi-display" aria-hidden="true"></i> Papan Informasi</span>
                <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
            </a>

            <a href="{{ route('profile.edit') }}" class="bk-side__me">
                <span class="bk-side__av">{{ $inisial }}</span>
                <span style="min-width:0">
                    <b>{{ $me->name ?? 'Peserta' }}</b>
                    <span>{{ $me->email ?? '' }}</span>
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
                    <div class="bk-topbar__crumb">Peserta</div>
                    <h1 class="bk-topbar__title">@yield('title', 'Ruang belajar')</h1>
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
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.bk-table, .admin-table').forEach((t) => {
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
