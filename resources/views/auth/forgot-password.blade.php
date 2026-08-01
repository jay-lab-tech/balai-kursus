<x-guest-layout judul="Pulihkan kata sandi." kicker="Tidak bisa masuk"
                lede="Masukkan alamat email akun Anda. Kami kirimkan tautan untuk membuat kata sandi baru.">
    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="bk-gate__form">
        @csrf

        <div>
            <x-input-label for="email" value="Alamat email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')"
                          required autofocus autocomplete="username" placeholder="nama@contoh.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <x-primary-button class="bk-btn--blok">Kirim tautan pemulihan</x-primary-button>
    </form>

    <p class="bk-gate__ask">
        Ingat kata sandinya?
        <a href="{{ route('login') }}">Kembali ke halaman masuk</a>
    </p>
</x-guest-layout>
