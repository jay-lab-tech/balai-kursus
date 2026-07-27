<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-sky-100 bg-sky-50 px-4 py-3.5 text-sm leading-5 text-sky-900">
        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-sky-600 text-xs font-bold text-white">i</span>
        <p>Untuk akun lokal, isi email dan password. Perangkat baru akan dikenali setelah login berhasil.</p>
    </div>

    @if (session('error'))
        <div class="mb-5 rounded-2xl border border-red-100 bg-red-50 px-4 py-3 text-sm leading-5 text-red-700">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Alamat email" class="!font-semibold !text-slate-700" />
            <x-text-input id="email" class="mt-2 block w-full !rounded-xl !border-slate-200 !px-4 !py-3.5 !text-sm focus:!border-sky-500 focus:!ring-sky-500/20" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@contoh.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" value="Password" class="!font-semibold !text-slate-700" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-semibold text-sky-700 hover:text-sky-900" href="{{ route('password.request') }}">Lupa password?</a>
                @endif
            </div>
            <x-text-input id="password" class="mt-2 block w-full !rounded-xl !border-slate-200 !px-4 !py-3.5 !text-sm focus:!border-sky-500 focus:!ring-sky-500/20" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-sky-600 shadow-sm focus:ring-sky-500" name="remember">
                <span class="ms-2 text-sm text-slate-500">Ingat saya</span>
            </label>
        </div>

        <x-primary-button class="w-full justify-center !rounded-xl !bg-[#102a43] !py-3.5 text-sm hover:!bg-[#173f5f]">
            Masuk dengan email dan password
        </x-primary-button>
    </form>

    <div class="my-7 flex items-center gap-3 text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">
        <div class="h-px flex-1 bg-slate-200"></div>
        <span>atau lanjutkan dengan</span>
        <div class="h-px flex-1 bg-slate-200"></div>
    </div>

    <div class="grid gap-3 sm:grid-cols-2">
        <a href="{{ route('login.google') }}" class="inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
            Masuk dengan Google
        </a>
        <a href="{{ route('login.cas') }}" class="inline-flex w-full items-center justify-center rounded-xl border border-amber-400 bg-amber-400 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-amber-300">
            Masuk dengan SSO UPI
        </a>
    </div>

    @if (Route::has('register'))
        <div class="mt-7 text-center text-sm text-slate-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-bold text-sky-700 hover:text-sky-900">
                Daftar sebagai peserta
            </a>
        </div>
    @endif
</x-guest-layout>
