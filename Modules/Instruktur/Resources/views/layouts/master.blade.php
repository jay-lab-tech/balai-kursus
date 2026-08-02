<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ruang kerja') · Balai Kursus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body>
@php
    $navItems = [
        ['label' => 'Ringkasan', 'url' => route('instruktur.dashboard'), 'active' => request()->is('instruktur/dashboard*'), 'icon' => 'bi-house-door'],
        ['label' => 'Kursus Saya', 'url' => route('instruktur.kursus.index'), 'active' => request()->is('instruktur/kursus*'), 'icon' => 'bi-journal-bookmark'],
        ['label' => 'Jadwal', 'url' => route('instruktur.jadwal.index'), 'active' => request()->is('instruktur/jadwal*'), 'icon' => 'bi-calendar3'],
    ];

    $me = Auth::user();
    $inisial = $me ? collect(explode(' ', trim($me->name)))->take(2)->map(fn ($p) => mb_substr($p, 0, 1))->implode('') : 'I';
@endphp
<div x-data="{ open: false }" class="bk-shell">

    <aside class="bk-side" :class="open && 'is-buka'">
        <a href="{{ route('instruktur.dashboard') }}" class="bk-side__brand">
            <span class="bk-side__glyph"><img src="{{ asset('images/logo.png') }}" alt=""></span>
            <span>
                <b>Balai Kursus</b>
                <small>Ruang instruktur</small>
            </span>
        </a>

        <nav class="bk-side__scroll" aria-label="Navigasi instruktur">
            <div class="bk-side__sec">Ruang kerja</div>
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
                    <b>{{ $me->name ?? 'Instruktur' }}</b>
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
                    <div class="bk-topbar__crumb">@yield('page-context', 'Instruktur')</div>
                    <h1 class="bk-topbar__title">@yield('title', 'Ruang kerja')</h1>
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
