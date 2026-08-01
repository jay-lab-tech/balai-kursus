<x-guest-layout judul="Buat akun peserta." kicker="Mulai di sini"
                lede="Lengkapi data di bawah untuk mendaftar sebagai peserta.">
    <form method="POST" action="{{ route('register') }}" class="bk-gate__form">
        @csrf

        <div>
            <x-input-label for="username" value="Nama pengguna" />
            <x-text-input id="username" type="text" name="username" :value="old('username', old('name'))"
                          required autofocus autocomplete="nickname" placeholder="Nama lengkap" />
            <x-input-error :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="email" value="Alamat email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')"
                          required autocomplete="username" placeholder="nama@contoh.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" value="Kata sandi" />
            <x-text-input id="password" type="password" name="password"
                          required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Ulangi kata sandi" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                          required autocomplete="new-password" placeholder="Ketik ulang kata sandi" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <x-primary-button class="bk-btn--blok">Buat akun peserta</x-primary-button>
    </form>

    <p class="bk-gate__ask">
        Sudah punya akun?
        <a href="{{ route('login') }}">Masuk di sini</a>
    </p>
</x-guest-layout>
