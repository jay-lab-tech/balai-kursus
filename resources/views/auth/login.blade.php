<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if (session('error'))
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 space-y-3">
        <a href="{{ route('login.google') }}" class="inline-flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
            <svg class="mr-3 h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="#EA4335" d="M12 10.2v3.9h5.5c-.2 1.3-1.5 3.9-5.5 3.9-3.3 0-6.1-2.7-6.1-6s2.8-6 6.1-6c1.9 0 3.2.8 3.9 1.4l2.7-2.6C16.9 3.2 14.7 2.2 12 2.2 6.9 2.2 2.8 6.3 2.8 11.4S6.9 20.6 12 20.6c6 0 9.9-4.2 9.9-10.1 0-.7-.1-1.2-.2-1.7H12z"/>
                <path fill="#34A853" d="M3.9 7.4l3.2 2.3C8 7.9 9.8 6.8 12 6.8c1.9 0 3.2.8 3.9 1.4l2.7-2.6C16.9 3.2 14.7 2.2 12 2.2 8.3 2.2 5.1 4.3 3.9 7.4z"/>
                <path fill="#FBBC05" d="M12 20.6c2.6 0 4.8-.9 6.4-2.5l-3-2.5c-.8.6-1.9 1-3.4 1-4 0-5.3-2.7-5.5-3.9l-3.2 2.4c1.3 3.2 4.4 5.5 8.7 5.5z"/>
                <path fill="#4285F4" d="M21.9 10.5c0-.7-.1-1.2-.2-1.7H12v3.9h5.5c-.3 1.4-1.2 2.5-2.1 3.2l3 2.5c1.7-1.6 2.8-4 2.8-6.9z"/>
            </svg>
            Masuk dengan Google
        </a>

    </div>

    <div class="relative mb-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-xs uppercase">
            <span class="bg-white px-2 text-gray-500">Masuk dengan email</span>
        </div>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6 flex items-center justify-between gap-3">
            <a href="{{ route('login.cas') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Login SSO UPI
            </a>

            <x-primary-button>
                {{ __('Masuk') }}
            </x-primary-button>
        </div>
    </form>

    <details class="mt-6 rounded-xl border border-gray-200 bg-gray-50 p-4">
        <summary class="cursor-pointer text-sm font-semibold text-gray-700">
            Pakai password untuk admin, instruktur, atau device baru
        </summary>

        <form method="POST" action="{{ route('login') }}" class="mt-4">
            @csrf

            <div>
                <x-input-label for="fallback_email" :value="__('Email')" />
                <x-text-input id="fallback_email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="ms-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </details>

    @if (Route::has('register'))
        <div class="mt-4 text-center text-sm text-gray-600">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-yellow-600 hover:text-yellow-700">
                Register di sini
            </a>
        </div>
    @endif
</x-guest-layout>
