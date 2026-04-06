<!DOCTYPE html>
<html lang="en">
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
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1f2937;
        }

        ::-webkit-scrollbar-thumb {
            background: #dc2626;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #991b1b;
        }

        /* Smooth transitions */
        html {
            scroll-behavior: smooth;
        }

        /* Custom animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }

        .animate-slide-in-right {
            animation: slideInRight 0.5s ease-out;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="overflow-x-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white">
    <!-- Navigation -->
    <nav x-data="{ mobileMenuOpen: false, syncDesktopMenu() { if (window.innerWidth >= 768) this.mobileMenuOpen = false } }"
         x-init="syncDesktopMenu()"
         @resize.window="syncDesktopMenu()"
         class="sticky top-0 z-50 border-b border-red-600/30 bg-gradient-to-r from-black to-gray-900 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex min-h-20 items-center justify-between gap-4 py-4">
                <div class="flex min-w-0 items-center">
                    <a href="/peserta/dashboard" class="whitespace-nowrap text-xl font-bold text-white transition-colors hover:text-yellow-400 sm:text-2xl">
                        <i class="bi bi-mortarboard mr-2 text-yellow-400"></i>Balai Kursus
                    </a>
                </div>

                <div class="hidden items-center space-x-1 md:flex">
                    <a href="/peserta/dashboard" class="px-4 py-2 rounded-lg hover:bg-red-600/20 hover:text-yellow-400 transition-all duration-200 {{ request()->is('peserta/dashboard*') ? 'bg-red-600/30 text-yellow-400' : 'text-gray-300' }}">
                        <i class="bi bi-speedometer2 mr-2"></i>Dashboard
                    </a>
                    <a href="/peserta/program" class="px-4 py-2 rounded-lg hover:bg-red-600/20 hover:text-yellow-400 transition-all duration-200 {{ request()->is('peserta/program*') ? 'bg-red-600/30 text-yellow-400' : 'text-gray-300' }}">
                        <i class="bi bi-diagram-3 mr-2"></i>Program
                    </a>
                    <a href="/peserta/pendaftaran" class="px-4 py-2 rounded-lg hover:bg-red-600/20 hover:text-yellow-400 transition-all duration-200 {{ request()->is('peserta/pendaftaran*') ? 'bg-red-600/30 text-yellow-400' : 'text-gray-300' }}">
                        <i class="bi bi-clipboard mr-2"></i>Pendaftaran
                    </a>
                    <a href="/peserta/kursus/saya" class="px-4 py-2 rounded-lg hover:bg-red-600/20 hover:text-yellow-400 transition-all duration-200 {{ request()->is('peserta/kursus/saya*') ? 'bg-red-600/30 text-yellow-400' : 'text-gray-300' }}">
                        <i class="bi bi-door-open mr-2"></i>Kelas Saya
                    </a>
                </div>

                <div class="hidden items-center space-x-4 md:flex">
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 bg-red-600/20 rounded-lg border border-red-500/30 focus:outline-none">
                        <i class="bi bi-person-circle text-yellow-400 mr-2"></i>
                        <span class="text-sm font-semibold">{{ Auth::user()->name ?? 'Peserta' }}</span>
                    </a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition-colors duration-200 transform hover:scale-105">
                        <i class="bi bi-box-arrow-right mr-2"></i>Keluar
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>

                <button type="button"
                        class="ml-auto inline-flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl border border-red-500/30 bg-red-600/10 text-yellow-400 transition hover:bg-red-600/20 md:hidden"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        :aria-expanded="mobileMenuOpen.toString()"
                        aria-label="Buka menu navigasi">
                    <i class="bi text-2xl" :class="mobileMenuOpen ? 'bi-x-lg' : 'bi-list'"></i>
                </button>
            </div>

            <div x-cloak
                 x-show="mobileMenuOpen"
                 x-transition.opacity.duration.200ms
                 class="border-t border-red-600/20 pb-4 pt-3 md:hidden">
                <div class="space-y-2">
                    <a href="/peserta/dashboard" class="block rounded-xl px-4 py-3 text-base transition-all duration-200 {{ request()->is('peserta/dashboard*') ? 'bg-red-600/30 text-yellow-400' : 'text-gray-300 hover:bg-red-600/10 hover:text-yellow-400' }}">
                        <i class="bi bi-speedometer2 mr-3"></i>Dashboard
                    </a>
                    <a href="/peserta/program" class="block rounded-xl px-4 py-3 text-base transition-all duration-200 {{ request()->is('peserta/program*') ? 'bg-red-600/30 text-yellow-400' : 'text-gray-300 hover:bg-red-600/10 hover:text-yellow-400' }}">
                        <i class="bi bi-diagram-3 mr-3"></i>Program
                    </a>
                    <a href="/peserta/pendaftaran" class="block rounded-xl px-4 py-3 text-base transition-all duration-200 {{ request()->is('peserta/pendaftaran*') ? 'bg-red-600/30 text-yellow-400' : 'text-gray-300 hover:bg-red-600/10 hover:text-yellow-400' }}">
                        <i class="bi bi-clipboard mr-3"></i>Pendaftaran
                    </a>
                    <a href="/peserta/kursus/saya" class="block rounded-xl px-4 py-3 text-base transition-all duration-200 {{ request()->is('peserta/kursus/saya*') ? 'bg-red-600/30 text-yellow-400' : 'text-gray-300 hover:bg-red-600/10 hover:text-yellow-400' }}">
                        <i class="bi bi-door-open mr-3"></i>Kelas Saya
                    </a>
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <a href="{{ route('profile.edit') }}" class="flex items-center justify-center rounded-xl border border-red-500/30 bg-red-600/20 px-4 py-3 text-sm font-semibold text-white">
                        <i class="bi bi-person-circle mr-2 text-yellow-400"></i>{{ Auth::user()->name ?? 'Peserta' }}
                    </a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center justify-center rounded-xl bg-red-600 px-4 py-3 text-sm font-semibold text-white transition-colors duration-200 hover:bg-red-700">
                        <i class="bi bi-box-arrow-right mr-2"></i>Keluar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="overflow-x-hidden">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-black to-gray-900 border-t border-red-600/30 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="text-lg font-bold text-yellow-400 mb-4">Balai Kursus</h3>
                    <p class="text-gray-400">Platform pembelajaran berkualitas tinggi untuk pengembangan skill Anda.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Navigasi Cepat</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/peserta/dashboard" class="hover:text-yellow-400 transition-colors">Dashboard</a></li>
                        <li><a href="/peserta/program" class="hover:text-yellow-400 transition-colors">Daftar Program</a></li>
                        <li><a href="/peserta/pendaftaran" class="hover:text-yellow-400 transition-colors">Pendaftaran Saya</a></li>
                        <li><a href="/peserta/kursus/saya" class="hover:text-yellow-400 transition-colors">Kelas Saya</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Hubungi Kami</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><i class="bi bi-telephone mr-2 text-red-500"></i>+62 123 456 789</li>
                        <li><i class="bi bi-envelope mr-2 text-red-500"></i>info@balaikursus.com</li>
                        <li><i class="bi bi-geo-alt mr-2 text-red-500"></i>Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 pt-8 text-center text-gray-400">
                <p>&copy; 2024 Balai Kursus. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    @if($jsFile)
        <script src="{{ asset('build/' . $jsFile) }}" type="module"></script>
    @endif
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
</body>
</html>
