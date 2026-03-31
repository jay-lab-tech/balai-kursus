<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instruktur Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex min-h-screen bg-yellow-50">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md flex flex-col">
            <div class="h-16 flex items-center justify-center border-b bg-yellow-400">
                <span class="font-bold text-lg text-black">Instruktur Panel</span>
            </div>
            <nav class="flex-1 py-6 bg-yellow-100">
                <ul class="space-y-2">
                    <li>
                        <a href="/instruktur/dashboard" class="block px-6 py-2 bg-yellow-400 text-black font-bold rounded hover:bg-yellow-500 transition flex items-center border border-yellow-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali ke Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile.edit') }}" class="block px-6 py-2 rounded font-semibold text-black hover:bg-yellow-200 {{ request()->routeIs('profile.edit') ? 'bg-white border border-yellow-400' : 'bg-yellow-100' }}">Profile</a>
                    </li>
                </ul>
            </nav>
            <div class="p-6 border-t">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 text-white py-2 rounded">Logout</button>
                </form>
            </div>
        </aside>
        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>
</body>
</html>
