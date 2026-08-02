<x-guest-layout judul="Masuk ke ruang Anda." kicker="Selamat datang kembali"
                lede="Gunakan akun Anda untuk melanjutkan ke ruang kerja.">
    <x-auth-session-status :status="session('status')" />

    @if (session('error'))
        <div class="bk-note bk-note--buruk">
            <i class="bi bi-exclamation-triangle-fill bk-note__icon" aria-hidden="true"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="bk-gate__form">
        @csrf

        <div>
            <x-input-label for="email" value="Alamat email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')"
                          required autofocus autocomplete="username" placeholder="nama@contoh.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <div class="bk-gate__lbl">
                <x-input-label for="password" value="Kata sandi" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Lupa kata sandi?</a>
                @endif
            </div>
            {{-- Sengaja tanpa required. LoginRequest membolehkan kata sandi kosong
                 bila perangkat ini sudah tepercaya, dan atribut required membuat
                 peramban menahan kiriman sehingga jalan pintas itu tak pernah
                 tercapai. Perangkat yang belum tepercaya tetap ditolak di server
                 dengan pesan auth.password_required. --}}
            <x-text-input id="password" type="password" name="password"
                          autocomplete="current-password" placeholder="Masukkan kata sandi" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <label for="remember_me" class="bk-gate__check">
            <input id="remember_me" type="checkbox" name="remember">
            <span>Ingat saya di perangkat ini</span>
        </label>

        <x-primary-button class="bk-btn--blok">Masuk</x-primary-button>
    </form>

    <p class="bk-gate__or">atau lanjutkan dengan</p>

    <div class="bk-gate__alt">
        <a href="{{ route('login.google') }}" class="bk-btn bk-btn--blok">
            <i class="bi bi-google" aria-hidden="true"></i>
            Akun Google
        </a>
        <a href="{{ route('login.cas') }}" class="bk-btn bk-btn--blok">
            <i class="bi bi-mortarboard" aria-hidden="true"></i>
            SSO UPI
        </a>
    </div>

    @if (Route::has('register'))
        <p class="bk-gate__ask">
            Belum punya akun?
            <a href="{{ route('register') }}">Daftar sebagai peserta</a>
        </p>
    @endif
</x-guest-layout>
