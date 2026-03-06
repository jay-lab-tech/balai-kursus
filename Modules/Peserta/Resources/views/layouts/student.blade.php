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
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-gradient-to-r from-black to-gray-900 shadow-2xl border-b border-red-600/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <a href="/peserta/dashboard" class="text-2xl font-bold text-white hover:text-yellow-400 transition-colors">
                        <i class="bi bi-mortarboard text-yellow-400 mr-2"></i>Balai Kursus
                    </a>
                </div>

                <!-- Nav Links -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="/peserta/dashboard" class="px-4 py-2 rounded-lg hover:bg-red-600/20 hover:text-yellow-400 transition-all duration-200 {{ request()->is('peserta/dashboard*') ? 'bg-red-600/30 text-yellow-400' : 'text-gray-300' }}">
                        <i class="bi bi-speedometer2 mr-2"></i>Dashboard
                    </a>
                    <a href="/peserta/kursus" class="px-4 py-2 rounded-lg hover:bg-red-600/20 hover:text-yellow-400 transition-all duration-200 {{ request()->is('peserta/kursus*') ? 'bg-red-600/30 text-yellow-400' : 'text-gray-300' }}">
                        <i class="bi bi-book mr-2"></i>Kursus
                    </a>
                    <a href="/peserta/pendaftaran" class="px-4 py-2 rounded-lg hover:bg-red-600/20 hover:text-yellow-400 transition-all duration-200 {{ request()->is('peserta/pendaftaran*') ? 'bg-red-600/30 text-yellow-400' : 'text-gray-300' }}">
                        <i class="bi bi-clipboard mr-2"></i>Pendaftaran
                    </a>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <div class="hidden sm:flex items-center px-4 py-2 bg-red-600/20 rounded-lg border border-red-500/30">
                        <i class="bi bi-person-circle text-yellow-400 mr-2"></i>
                        <span class="text-sm font-semibold">{{ Auth::user()->name ?? 'Peserta' }}</span>
                    </div>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition-colors duration-200 transform hover:scale-105">
                        <i class="bi bi-box-arrow-right mr-2"></i>Keluar
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
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
                        <li><a href="/peserta/kursus" class="hover:text-yellow-400 transition-colors">Daftar Kursus</a></li>
                        <li><a href="/peserta/pendaftaran" class="hover:text-yellow-400 transition-colors">Pendaftaran Saya</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Hubungi Kami</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><i class="bi bi-telephone mr-2 text-red-500"></i>+62 123 456 789</li>
                        <li><i class="bi bi-envelope mr-2 text-red-500"></i>info@balaiku rsus.com</li>
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
        <script src="{{ asset('build/' . $jsFile) }}"></script>
    @endif
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"></script>
</body>
</html>
