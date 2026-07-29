<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Balai Kursus') - Peserta</title>
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
    </style>
</head>
<body class="workspace-body role-shell overflow-x-hidden text-white">
    <aside class="workspace-sidebar-fixed">
        <a href="{{ route('peserta.dashboard') }}" class="workspace-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Balai Kursus">
            <span><small>Ruang Peserta</small><strong>Balai Kursus</strong></span>
        </a>
        <div class="workspace-nav-label">Ruang belajar</div>
        <nav aria-label="Navigasi peserta">
            <a href="{{ route('peserta.dashboard') }}" class="workspace-link {{ request()->is('peserta/dashboard*') ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i>Beranda</a>
            <a href="{{ url('/peserta/program') }}" class="workspace-link {{ request()->is('peserta/program*') ? 'active' : '' }}"><i class="bi bi-compass"></i>Program</a>
            <a href="{{ url('/peserta/kursus/saya') }}" class="workspace-link {{ request()->is('peserta/kursus*') ? 'active' : '' }}"><i class="bi bi-journal-bookmark"></i>Kelas Saya</a>
            <a href="{{ url('/peserta/pendaftaran') }}" class="workspace-link {{ request()->is('peserta/pendaftaran*') ? 'active' : '' }}"><i class="bi bi-clipboard-check"></i>Pendaftaran</a>
            <a href="{{ url('/peserta/riwayat-pembayaran') }}" class="workspace-link {{ request()->is('peserta/riwayat-pembayaran*') ? 'active' : '' }}"><i class="bi bi-receipt"></i>Pembayaran</a>
            <a href="{{ route('profile.certificates') }}" class="workspace-link {{ request()->is('profile/certificates*') ? 'active' : '' }}"><i class="bi bi-award"></i>Sertifikat</a>
        </nav>
        <div class="workspace-account"><span>{{ Auth::user()->email ?? '' }}</span>{{ Auth::user()->name ?? 'Peserta' }}</div>
    </aside>
    <header class="workspace-topbar-fixed workspace-topbar">
        <div><small>Balai Kursus / Peserta</small><h1>@yield('title', 'Ruang belajar peserta')</h1></div>
        <div class="workspace-topbar-actions"><a href="{{ route('profile.edit') }}" class="workspace-profile"><i class="bi bi-person-circle"></i> {{ Auth::user()->name ?? 'Profil' }}</a><form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form><button type="submit" form="logout-form" class="workspace-logout">Keluar</button></div>
    </header>
    <nav x-data="{ mobileMenuOpen: false }" class="role-nav sticky top-0 z-50 border-b shadow-2xl">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex min-h-[4.75rem] items-center justify-between gap-4">
                <a href="{{ route('peserta.dashboard') }}" class="flex min-w-0 items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white p-1.5 shadow-lg">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Balai Kursus" class="h-full w-full object-contain">
                    </span>
                    <span class="min-w-0">
                        <span class="block text-[10px] font-bold uppercase tracking-[0.22em] text-sky-300">Ruang Peserta</span>
                        <span class="block truncate text-base font-bold text-white">Balai Kursus</span>
                    </span>
                </a>

                <div class="hidden items-center gap-1 md:flex">
                    <a href="{{ route('peserta.dashboard') }}" class="role-nav-link {{ request()->is('peserta/dashboard*') ? 'active' : '' }} rounded-xl px-4 py-2.5 text-sm font-semibold"><i class="bi bi-grid-1x2-fill mr-2"></i>Ringkasan</a>
                    <a href="{{ url('/peserta/program') }}" class="role-nav-link {{ request()->is('peserta/program*') ? 'active' : '' }} rounded-xl px-4 py-2.5 text-sm font-semibold"><i class="bi bi-compass-fill mr-2"></i>Program</a>
                    <a href="{{ url('/peserta/pendaftaran') }}" class="role-nav-link {{ request()->is('peserta/pendaftaran*') ? 'active' : '' }} rounded-xl px-4 py-2.5 text-sm font-semibold"><i class="bi bi-clipboard-check-fill mr-2"></i>Pendaftaran</a>
                    <a href="{{ url('/peserta/kursus/saya') }}" class="role-nav-link {{ request()->is('peserta/kursus/saya*') ? 'active' : '' }} rounded-xl px-4 py-2.5 text-sm font-semibold"><i class="bi bi-journal-bookmark-fill mr-2"></i>Kelas Saya</a>
                </div>

                <div class="hidden items-center gap-2 md:flex">
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-white/10"><i class="bi bi-person-circle text-sky-300"></i>{{ Auth::user()->name ?? 'Peserta' }}</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
                    <button type="submit" form="logout-form" class="inline-flex items-center gap-2 rounded-xl bg-sky-600 px-3 py-2 text-sm font-bold text-white transition hover:bg-sky-500"><i class="bi bi-box-arrow-right"></i>Keluar</button>
                </div>

                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-sky-300/20 bg-sky-400/10 text-sky-200 md:hidden" @click="mobileMenuOpen = !mobileMenuOpen" :aria-expanded="mobileMenuOpen.toString()" aria-label="Buka menu navigasi">
                    <i class="bi text-xl" :class="mobileMenuOpen ? 'bi-x-lg' : 'bi-list'"></i>
                </button>
            </div>

            <div x-cloak x-show="mobileMenuOpen" x-transition.opacity class="border-t border-white/10 py-4 md:hidden">
                <div class="grid gap-2">
                    <a href="{{ route('peserta.dashboard') }}" class="role-nav-link {{ request()->is('peserta/dashboard*') ? 'active' : '' }} rounded-xl px-4 py-3 text-sm font-semibold"><i class="bi bi-grid-1x2-fill mr-3"></i>Ringkasan</a>
                    <a href="{{ url('/peserta/program') }}" class="role-nav-link {{ request()->is('peserta/program*') ? 'active' : '' }} rounded-xl px-4 py-3 text-sm font-semibold"><i class="bi bi-compass-fill mr-3"></i>Program</a>
                    <a href="{{ url('/peserta/pendaftaran') }}" class="role-nav-link {{ request()->is('peserta/pendaftaran*') ? 'active' : '' }} rounded-xl px-4 py-3 text-sm font-semibold"><i class="bi bi-clipboard-check-fill mr-3"></i>Pendaftaran</a>
                    <a href="{{ url('/peserta/kursus/saya') }}" class="role-nav-link {{ request()->is('peserta/kursus/saya*') ? 'active' : '' }} rounded-xl px-4 py-3 text-sm font-semibold"><i class="bi bi-journal-bookmark-fill mr-3"></i>Kelas Saya</a>
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
            <span>Balai Kursus · Ruang Peserta</span>
            <a href="{{ url('/papan-informasi') }}" class="font-semibold text-sky-300 hover:text-amber-300">Papan Informasi Publik</a>
        </div>
    </footer>

    @if($jsFile)
        <script src="{{ asset('build/' . $jsFile) }}" type="module"></script>
    @endif
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
</body>
</html>
