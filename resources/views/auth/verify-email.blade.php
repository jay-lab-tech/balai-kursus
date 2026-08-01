<x-guest-layout judul="Periksa email Anda." kicker="Satu langkah lagi"
                lede="Kami mengirim tautan verifikasi ke alamat email yang Anda daftarkan. Buka tautan itu untuk mengaktifkan akun.">
    @if (session('status') == 'verification-link-sent')
        <div class="bk-note bk-note--baik">
            <i class="bi bi-check-circle-fill bk-note__icon" aria-hidden="true"></i>
            <span>Tautan verifikasi baru sudah dikirim ke email Anda.</span>
        </div>
    @endif

    <div class="bk-gate__form">
        <p class="bk-muted">Belum menerima emailnya? Periksa folder spam, atau minta kami mengirim ulang.</p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="bk-btn--blok">Kirim ulang tautan verifikasi</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bk-btn bk-btn--blok">Keluar</button>
        </form>
    </div>
</x-guest-layout>
