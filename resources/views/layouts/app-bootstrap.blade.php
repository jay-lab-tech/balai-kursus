<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Balai Kursus'))</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <style>
            :root {
                --primary-color: #667eea;
                --secondary-color: #764ba2;
                --accent-color: #4f46e5;
            }

            body {
                font-family: 'Figtree', sans-serif;
                background-color: #f8f9fa;
            }

            .navbar {
                background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }

            .navbar .navbar-brand {
                font-weight: 700;
                font-size: 1.25rem;
                color: white !important;
            }

            .navbar .nav-link {
                color: rgba(255,255,255,0.8) !important;
                transition: color 0.3s ease;
            }

            .navbar .nav-link:hover {
                color: white !important;
            }

            .card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .card:hover {
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
                transform: translateY(-2px);
            }

            .btn-primary {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }

            .btn-primary:hover {
                background-color: var(--accent-color);
                border-color: var(--accent-color);
            }

            main {
                min-height: calc(100vh - 200px);
            }

            .dropdown-item.active,
            .dropdown-item:active {
                background-color: var(--primary-color);
            }

            .navbar-nav .nav-link {
                font-weight: 500;
            }

            .navbar-nav .nav-link.active {
                border-bottom: 3px solid white;
                padding-bottom: 9px;
            }

            .navbar-nav .dropdown-menu {
                border: none;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            }

            .navbar-nav .dropdown-menu .dropdown-item:hover {
                background-color: #f0f0f0;
            }
        </style>

        @yield('styles')
    </head>
    <body class="font-sans antialiased">
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="bi bi-mortarboard me-2"></i>Balai Kursus
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        @auth
                            @if(Auth::user()->role === 'peserta')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/peserta/dashboard') }}">
                                        <i class="bi bi-house me-1"></i>Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/peserta/kursus') }}">
                                        <i class="bi bi-book me-1"></i>Kursus
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/peserta/pendaftaran') }}">
                                        <i class="bi bi-clipboard-check me-1"></i>Pendaftaran
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/peserta/riwayat') }}">
                                        <i class="bi bi-clock-history me-1"></i>Riwayat
                                    </a>
                                </li>
                            @elseif(Auth::user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/admin/dashboard') }}">
                                        <i class="bi bi-speedometer2 me-1"></i>Dashboard
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-gear me-1"></i>Master Data
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                                        <li><a class="dropdown-item" href="{{ url('/admin/peserta') }}"><i class="bi bi-people me-2"></i>Peserta</a></li>
                                        <li><a class="dropdown-item" href="{{ url('/admin/program') }}"><i class="bi bi-book me-2"></i>Program</a></li>
                                        <li><a class="dropdown-item" href="{{ url('/admin/level') }}"><i class="bi bi-pyramid me-2"></i>Level</a></li>
                                        <li><a class="dropdown-item" href="{{ url('/admin/kursus') }}"><i class="bi bi-bookmark me-2"></i>Kursus</a></li>
                                        <li><a class="dropdown-item" href="{{ url('/admin/instruktur') }}"><i class="bi bi-mortarboard me-2"></i>Instruktur</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/admin/pembayaran') }}">
                                        <i class="bi bi-cash-coin me-1"></i>Pembayaran
                                    </a>
                                </li>
                            @elseif(Auth::user()->role === 'instruktur')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/instruktur/dashboard') }}">
                                        <i class="bi bi-house me-1"></i>Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/instruktur/kursus') }}">
                                        <i class="bi bi-book me-1"></i>Kursus
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/instruktur/risalah') }}">
                                        <i class="bi bi-file-earmark me-1"></i>Risalah
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-gear me-2"></i>Profile
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger" style="border: none; background: none; cursor: pointer;">
                                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Header (Optional) -->
        @if (isset($header))
            <div class="bg-light border-bottom py-3">
                <div class="container-fluid">
                    {{ $header }}
                </div>
            </div>
        @endif

        <!-- Breadcrumb (Optional) -->
        @if (isset($breadcrumb))
            <div class="container-fluid py-2">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        {{ $breadcrumb }}
                    </ol>
                </nav>
            </div>
        @endif

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-light border-top py-4 mt-5">
            <div class="container-fluid">
                <div class="text-center text-muted">
                    <small>&copy; {{ date('Y') }} Balai Kursus. All rights reserved.</small>
                </div>
            </div>
        </footer>

        <!-- Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        @yield('scripts')
    </body>
</html>
