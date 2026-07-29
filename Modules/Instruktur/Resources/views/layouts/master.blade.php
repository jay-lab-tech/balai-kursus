<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Balai Kursus') - Instruktur</title>
    @php
        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
        $jsFile = $manifest['resources/js/app.js']['file'] ?? null;
    @endphp
    @if($cssFile)
        <link rel="stylesheet" href="{{ asset('build/' . $cssFile) }}">
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
        body { min-height: 100vh; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0b2035; }
        ::-webkit-scrollbar-thumb { background: #0ea5e9; border-radius: 999px; }
        .role-shell { background: #0b2035; }
        .role-nav { background: rgba(16, 42, 67, .96); border-color: rgba(125, 211, 252, .16); backdrop-filter: blur(18px); }
        .role-nav-link { color: #cbd5e1; transition: .2s ease; }
        .role-nav-link:hover { color: #fff; background: rgba(14, 165, 233, .12); }
        .role-nav-link.active { color: #082f49; background: #fbbf24; box-shadow: 0 8px 20px rgba(251, 191, 36, .14); }
        .role-card { border: 1px solid rgba(148, 163, 184, .16); background: rgba(16, 42, 67, .72); box-shadow: 0 20px 50px rgba(2, 18, 34, .25); }

        .role-shell { background: #f7f8f6; color: #173f5f; }
        .role-nav { background: rgba(255, 255, 255, .96); border-color: #dce7e5; box-shadow: 0 8px 24px rgba(23, 63, 95, .06); }
        .role-nav-link { color: #40627d; }
        .role-nav-link:hover { color: #0f766e; background: #e8f7f4; }
        .role-nav-link.active { color: #0f766e; background: #e8f7f4; box-shadow: none; }
        .role-shell main { color: #173f5f; }
        .role-shell main [class~="text-white"] { color: #173f5f !important; }
        .role-shell main [class~="text-gray-300"],
        .role-shell main [class~="text-slate-300"] { color: #40627d !important; }
        .role-shell main [class~="text-gray-400"],
        .role-shell main [class~="text-gray-500"],
        .role-shell main [class~="text-slate-400"] { color: #718596 !important; }
        .role-shell main [class~="bg-white/5"],
        .role-shell main [class~="bg-black/20"],
        .role-shell main [class~="bg-gray-900/60"],
        .role-shell main [class~="bg-gray-900/70"] { background: #ffffff !important; }
        .role-shell main [class~="border-white/10"],
        .role-shell main [class~="border-gray-700"] { border-color: #dce7e5 !important; }
        .role-shell main [class*="bg-gradient"] { background-image: none !important; background-color: #ffffff !important; border: 1px solid #dce7e5; box-shadow: 0 14px 35px rgba(23, 63, 95, .08); }
        .role-shell main [class~="bg-[#0b2035]"],
        .role-shell main [class~="bg-[#102a43]"] { background: #f7f8f6 !important; }
        .role-shell main [class~="text-yellow-300"],
        .role-shell main [class~="text-yellow-400"] { color: #b45309 !important; }
        .role-shell main [class~="text-sky-300"],
        .role-shell main [class~="text-sky-400"] { color: #0f766e !important; }
        .role-shell main [class~="bg-sky-600"],
        .role-shell main [class~="bg-sky-700"] { background: #0d9488 !important; }
        .role-shell main > [class*="bg-gradient"] { background: #f7f8f6 !important; border: 0; box-shadow: none; }
        .role-shell main [class~="bg-gray-800"],
        .role-shell main [class~="bg-gray-900"],
        .role-shell main [class~="bg-slate-800"],
        .role-shell main [class~="bg-slate-900"] { background: #ffffff !important; }
        .role-shell main [class~="bg-gray-700"],
        .role-shell main [class~="bg-slate-950"] { background: #f3f7f6 !important; }
        .role-shell main [class~="text-gray-900"],
        .role-shell main [class~="text-slate-950"] { color: #173f5f !important; }
        .role-shell main [class*="border-gray-"],
        .role-shell main [class*="border-slate-"] { border-color: #dce7e5 !important; }
        .role-shell main a[class*="bg-gradient"],
        .role-shell main button[class*="bg-gradient"] { background-image: none !important; background: #0d9488 !important; border: 0; color: #ffffff !important; }
        .role-shell main [class*="bg-gradient"][style*="width"] { background: #0d9488 !important; }
        .role-shell main [style*="linear-gradient"] { background: #173f5f !important; }
        footer { background: #173f5f !important; border-color: #285574 !important; }

        .workspace-body { background: #f5f2ea; color: #1e2d36; }
        .workspace-app { min-height: 100vh; display: grid; grid-template-columns: 15.5rem minmax(0, 1fr); }
        .workspace-sidebar { background: #173f5f; color: #fff; padding: 1.5rem 1rem; display: flex; flex-direction: column; }
        .workspace-sidebar-fixed { position: fixed; inset: 0 auto 0 0; z-index: 40; width: 15.5rem; }
        .workspace-brand { display: flex; align-items: center; gap: .75rem; padding: .2rem .65rem 1.75rem; border-bottom: 1px solid rgba(255,255,255,.16); }
        .workspace-brand img { width: 2.35rem; height: 2.35rem; object-fit: contain; background: #fff; padding: .3rem; border-radius: .4rem; }
        .workspace-brand small { display: block; color: #9ddbd4; font: 600 .62rem/1.2 'IBM Plex Mono', monospace; letter-spacing: .12em; text-transform: uppercase; }
        .workspace-brand strong { display: block; margin-top: .2rem; font: 700 1rem/1.2 'Source Serif 4', serif; }
        .workspace-nav-label { margin: 1.75rem .7rem .55rem; color: #b8cedb; font: 600 .65rem/1.2 'IBM Plex Mono', monospace; letter-spacing: .16em; text-transform: uppercase; }
        .workspace-link { display: flex; align-items: center; gap: .7rem; padding: .75rem .7rem; color: #d9e6ec; border-left: 3px solid transparent; font-size: .88rem; font-weight: 600; text-decoration: none; transition: color .18s ease, background .18s ease, border-color .18s ease; }
        .workspace-link:hover { color: #fff; background: rgba(255,255,255,.09); }
        .workspace-link.active { color: #fff; background: rgba(13,148,136,.32); border-left-color: #f0a36c; }
        .workspace-link i { width: 1.1rem; text-align: center; color: #9ddbd4; }
        .workspace-account { margin-top: auto; padding: 1rem .7rem 0; border-top: 1px solid rgba(255,255,255,.16); color: #d9e6ec; font-size: .78rem; }
        .workspace-account span { display: block; color: #9ddbd4; font: .7rem 'IBM Plex Mono', monospace; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .workspace-column { min-width: 0; background: #f5f2ea; }
        .workspace-topbar { min-height: 4.4rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: .85rem clamp(1rem, 3vw, 2.5rem); background: #fffefa; border-bottom: 1px solid #cfc8bb; }
        .workspace-topbar-fixed { position: fixed; inset: 0 0 auto 15.5rem; z-index: 30; }
        .workspace-topbar small { color: #6c7c82; font: 600 .68rem 'IBM Plex Mono', monospace; letter-spacing: .13em; text-transform: uppercase; }
        .workspace-topbar h1 { margin: .2rem 0 0; color: #173f5f; font: 600 1.4rem/1.2 'Source Serif 4', serif; }
        .workspace-topbar-actions { display: flex; align-items: center; gap: .65rem; }
        .workspace-profile { color: #40627d; font-size: .82rem; font-weight: 600; text-decoration: none; }
        .workspace-logout { border: 1px solid #cfc8bb; background: transparent; color: #a84a2a; padding: .55rem .75rem; font-size: .78rem; font-weight: 700; cursor: pointer; }
        .workspace-mobile-toggle { display: none; border: 1px solid #cfc8bb; background: #fffefa; color: #173f5f; padding: .5rem .7rem; cursor: pointer; }
        .workspace-content { min-width: 0; }
        .workspace-footer { padding: 1.2rem clamp(1rem, 3vw, 2.5rem); border-top: 1px solid #cfc8bb; background: #fffefa; color: #6c7c82; font-size: .72rem; }
        .workspace-footer a { color: #0d9488; font-weight: 700; }
        .workspace-body > nav { display: none !important; }
        .workspace-body > main, .workspace-body > footer { margin-left: 15.5rem; }
        .workspace-body > main { padding-top: 4.4rem; }
        @media (max-width: 800px) {
            .workspace-sidebar-fixed { display: none; }
            .workspace-topbar-fixed { inset: 0; }
            .workspace-body > main, .workspace-body > footer { margin-left: 0; }
            .workspace-mobile-toggle { display: inline-flex; }
            .workspace-mobile-menu { position: absolute; inset: 4.4rem 0 auto 0; z-index: 30; padding: .75rem 1rem 1rem; background: #173f5f; box-shadow: 0 12px 24px rgba(23,63,95,.2); }
            .workspace-mobile-menu .workspace-link { color: #d9e6ec; }
        }
    </style>
</head>
<body class="workspace-body role-shell overflow-x-hidden text-white">
    <aside class="workspace-sidebar workspace-sidebar-fixed">
        <a href="{{ route('instruktur.dashboard') }}" class="workspace-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Balai Kursus">
            <span><small>Ruang Instruktur</small><strong>Balai Kursus</strong></span>
        </a>
        <div class="workspace-nav-label">Workspace</div>
        <nav aria-label="Navigasi instruktur">
            <a href="{{ route('instruktur.dashboard') }}" class="workspace-link {{ request()->is('instruktur/dashboard*') ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i>Ringkasan</a>
            <a href="{{ route('instruktur.kursus.index') }}" class="workspace-link {{ request()->is('instruktur/kursus*') ? 'active' : '' }}"><i class="bi bi-journal-bookmark"></i>Kursus Saya</a>
            <a href="{{ route('instruktur.jadwal.index') }}" class="workspace-link {{ request()->is('instruktur/jadwal*') ? 'active' : '' }}"><i class="bi bi-calendar3"></i>Jadwal</a>
        </nav>
        <div class="workspace-account"><span>{{ Auth::user()->email ?? '' }}</span>{{ Auth::user()->name ?? 'Instruktur' }}</div>
    </aside>
    <header class="workspace-topbar workspace-topbar-fixed">
        <div><small>Balai Kursus / Instruktur</small><h1>@yield('title', 'Ruang kerja instruktur')</h1></div>
        <div class="workspace-topbar-actions"><a href="{{ route('profile.edit') }}" class="workspace-profile"><i class="bi bi-person-circle"></i> {{ Auth::user()->name ?? 'Profil' }}</a><form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form><button type="submit" form="logout-form" class="workspace-logout">Keluar</button></div>
    </header>
    <nav x-data="{ mobileMenuOpen: false }" class="role-nav sticky top-0 z-50 border-b shadow-2xl">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex min-h-[4.75rem] items-center justify-between gap-4">
                <a href="{{ route('instruktur.dashboard') }}" class="flex min-w-0 items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white p-1.5 shadow-lg">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Balai Kursus" class="h-full w-full object-contain">
                    </span>
                    <span class="min-w-0">
                        <span class="block text-[10px] font-bold uppercase tracking-[0.22em] text-sky-300">Ruang Instruktur</span>
                        <span class="block truncate text-base font-bold text-white">Balai Kursus</span>
                    </span>
                </a>

                <div class="hidden items-center gap-1 md:flex">
                    <a href="{{ route('instruktur.dashboard') }}" class="role-nav-link {{ request()->is('instruktur/dashboard*') ? 'active' : '' }} rounded-xl px-4 py-2.5 text-sm font-semibold"><i class="bi bi-grid-1x2-fill mr-2"></i>Ringkasan</a>
                    <a href="{{ url('/instruktur/kursus') }}" class="role-nav-link {{ request()->is('instruktur/kursus*') ? 'active' : '' }} rounded-xl px-4 py-2.5 text-sm font-semibold"><i class="bi bi-journal-bookmark-fill mr-2"></i>Kursus</a>
                    <a href="{{ url('/instruktur/jadwal') }}" class="role-nav-link {{ request()->is('instruktur/jadwal*') ? 'active' : '' }} rounded-xl px-4 py-2.5 text-sm font-semibold"><i class="bi bi-calendar3 mr-2"></i>Jadwal</a>
                </div>

                <div class="hidden items-center gap-2 md:flex">
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-white/10"><i class="bi bi-person-circle text-sky-300"></i>{{ Auth::user()->name ?? 'Instruktur' }}</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
                    <button type="submit" form="logout-form" class="inline-flex items-center gap-2 rounded-xl bg-sky-600 px-3 py-2 text-sm font-bold text-white transition hover:bg-sky-500"><i class="bi bi-box-arrow-right"></i>Keluar</button>
                </div>

                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-sky-300/20 bg-sky-400/10 text-sky-200 md:hidden" @click="mobileMenuOpen = !mobileMenuOpen" :aria-expanded="mobileMenuOpen.toString()" aria-label="Buka menu navigasi">
                    <i class="bi text-xl" :class="mobileMenuOpen ? 'bi-x-lg' : 'bi-list'"></i>
                </button>
            </div>

            <div x-cloak x-show="mobileMenuOpen" x-transition.opacity class="border-t border-white/10 py-4 md:hidden">
                <div class="grid gap-2">
                    <a href="{{ route('instruktur.dashboard') }}" class="role-nav-link {{ request()->is('instruktur/dashboard*') ? 'active' : '' }} rounded-xl px-4 py-3 text-sm font-semibold"><i class="bi bi-grid-1x2-fill mr-3"></i>Ringkasan</a>
                    <a href="{{ url('/instruktur/kursus') }}" class="role-nav-link {{ request()->is('instruktur/kursus*') ? 'active' : '' }} rounded-xl px-4 py-3 text-sm font-semibold"><i class="bi bi-journal-bookmark-fill mr-3"></i>Kursus</a>
                    <a href="{{ url('/instruktur/jadwal') }}" class="role-nav-link {{ request()->is('instruktur/jadwal*') ? 'active' : '' }} rounded-xl px-4 py-3 text-sm font-semibold"><i class="bi bi-calendar3 mr-3"></i>Jadwal</a>
                </div>
                <div class="mt-4 grid gap-2 sm:grid-cols-2">
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold"><i class="bi bi-person-circle mr-2 text-sky-300"></i>Profil Saya</a>
                    <button type="submit" form="logout-form" class="inline-flex items-center justify-center rounded-xl bg-sky-600 px-4 py-3 text-sm font-bold"><i class="bi bi-box-arrow-right mr-2"></i>Keluar</button>
                </div>
            </div>
        </div>
    </nav>

    <main class="min-h-[calc(100vh-4.75rem)] overflow-x-hidden">@yield('content')</main>

    <footer class="border-t border-sky-300/10 bg-[#061a2b]">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6 text-xs text-slate-400 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <span>Balai Kursus · Ruang Instruktur</span>
            <a href="{{ url('/papan-informasi') }}" class="font-semibold text-sky-300 hover:text-amber-300">Papan Informasi Publik</a>
        </div>
    </footer>

    @if($jsFile)
        <script src="{{ asset('build/' . $jsFile) }}" type="module"></script>
    @endif
</body>
</html>
