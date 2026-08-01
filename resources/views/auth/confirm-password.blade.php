<x-guest-layout judul="Konfirmasi kata sandi." kicker="Area terbatas"
                lede="Bagian ini menyimpan data sensitif. Masukkan kembali kata sandi Anda untuk melanjutkan.">
    <form method="POST" action="{{ route('password.confirm') }}" class="bk-gate__form">
        @csrf

        <div>
            <x-input-label for="password" value="Kata sandi" />
            <x-text-input id="password" type="password" name="password"
                          required autofocus autocomplete="current-password" placeholder="Masukkan kata sandi" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <x-primary-button class="bk-btn--blok">Lanjutkan</x-primary-button>
    </form>
</x-guest-layout>
