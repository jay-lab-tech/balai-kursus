{{--
    Dulu profil punya kerangka sendiri (profile/layout.blade.php) yang memuat
    Tailwind 2 dari CDN, terpisah dari berkas gaya aplikasi, dan menu sampingnya
    selalu bertuliskan "User Panel" walau yang membuka instruktur atau admin.
    Sekarang halaman ini menumpang kerangka milik peran yang sedang masuk,
    sehingga menu samping pengguna tidak hilang saat ia mengurus akunnya.
--}}
@extends($tataLetak)

@section('title', 'Profil saya')
@section('page-title', 'Profil saya')
@section('page-context', 'Akun')
@section('page-description', 'Ubah data akun dan kata sandi Anda.')

@section('content')

@if (session('status') === 'profile-updated')
    <div class="bk-note bk-note--baik">
        <span class="bk-note__icon"><i class="bi bi-check2-circle" aria-hidden="true"></i></span>
        <div>Data profil sudah tersimpan.</div>
    </div>
@endif

@if (session('status') === 'password-updated')
    <div class="bk-note bk-note--baik">
        <span class="bk-note__icon"><i class="bi bi-shield-check" aria-hidden="true"></i></span>
        <div>Kata sandi sudah diganti. Perangkat lain yang masih masuk tidak ikut keluar.</div>
    </div>
@endif

<div class="bk-duo">
    <div>
        <section class="bk-panel">
            <div class="bk-panel__head">
                <div>
                    <h2 class="bk-panel__title">Data akun</h2>
                    <p class="bk-panel__subtitle">Nama di sini yang dipakai pada daftar hadir, risalah, dan sertifikat.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="bk-panel__body">
                    <div class="bk-fields">
                        <div class="bk-field">
                            <label for="name" class="bk-label">Nama lengkap</label>
                            <input type="text" id="name" name="name" class="bk-input"
                                   value="{{ old('name', $user->name) }}" required autocomplete="name">
                            @error('name')
                                <p class="bk-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bk-field">
                            <label for="email" class="bk-label">Alamat email</label>
                            <input type="email" id="email" name="email" class="bk-input"
                                   value="{{ old('email', $user->email) }}" required autocomplete="email">
                            @error('email')
                                <p class="bk-error">{{ $message }}</p>
                            @enderror
                        </div>

                        @if ($peserta)
                            <div class="bk-field">
                                <label for="nomor_peserta" class="bk-label">Nomor peserta</label>
                                <input type="text" id="nomor_peserta" class="bk-input"
                                       value="{{ $peserta->nomor_peserta }}" readonly disabled>
                                <p class="bk-hint">Diterbitkan sistem saat akun dibuat dan tidak bisa diubah sendiri.</p>
                            </div>

                            <div class="bk-field">
                                <label for="no_hp" class="bk-label">Nomor HP / WhatsApp</label>
                                <input type="text" id="no_hp" name="no_hp" class="bk-input"
                                       value="{{ old('no_hp', $peserta->no_hp) }}"
                                       placeholder="08123456789" required>
                                @error('no_hp')
                                    <p class="bk-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="bk-field bk-field--wide">
                                <label for="instansi" class="bk-label">Asal instansi atau sekolah</label>
                                <input type="text" id="instansi" name="instansi" class="bk-input"
                                       value="{{ old('instansi', $peserta->instansi) }}"
                                       placeholder="Universitas Pendidikan Indonesia">
                                @error('instansi')
                                    <p class="bk-error">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bk-panel__foot">
                    <span class="bk-muted">Perubahan email membuat Anda perlu masuk dengan alamat baru.</span>
                    <button type="submit" class="bk-btn bk-btn--pri bk-btn--sm">
                        <i class="bi bi-check2" aria-hidden="true"></i> Simpan perubahan
                    </button>
                </div>
            </form>
        </section>

        {{--
            Formulir ganti kata sandi sebenarnya sudah punya rute (password.update)
            sejak awal, tapi tidak pernah dirender di mana pun — jadi tidak ada
            satu pun jalan bagi pengguna untuk menggantinya sendiri.
        --}}
        <section class="bk-panel">
            <div class="bk-panel__head">
                <div>
                    <h2 class="bk-panel__title">Kata sandi</h2>
                    <p class="bk-panel__subtitle">Isi kata sandi lama sebagai bukti bahwa ini memang Anda.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="bk-panel__body">
                    <div class="bk-fields">
                        <div class="bk-field bk-field--wide">
                            <label for="current_password" class="bk-label">Kata sandi sekarang</label>
                            <input type="password" id="current_password" name="current_password" class="bk-input"
                                   autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <p class="bk-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bk-field">
                            <label for="password" class="bk-label">Kata sandi baru</label>
                            <input type="password" id="password" name="password" class="bk-input"
                                   autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <p class="bk-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bk-field">
                            <label for="password_confirmation" class="bk-label">Ulangi kata sandi baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="bk-input"
                                   autocomplete="new-password">
                            @error('password_confirmation', 'updatePassword')
                                <p class="bk-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="bk-panel__foot">
                    <span class="bk-muted">Minimal 8 karakter.</span>
                    <button type="submit" class="bk-btn bk-btn--pri bk-btn--sm">
                        <i class="bi bi-shield-lock" aria-hidden="true"></i> Ganti kata sandi
                    </button>
                </div>
            </form>
        </section>
    </div>

    <div>
        <section class="bk-panel">
            <div class="bk-panel__head">
                <div><h2 class="bk-panel__title">Akun ini</h2></div>
            </div>
            <dl class="bk-facts">
                <div>
                    <dt>Peran</dt>
                    <dd><span class="bk-tag bk-tag--info">{{ ucfirst($user->role ?? 'peserta') }}</span></dd>
                </div>
                <div>
                    <dt>Bergabung</dt>
                    <dd>{{ $user->created_at?->translatedFormat('j F Y') ?? '-' }}</dd>
                </div>
            </dl>
            <div class="bk-panel__foot">
                <span class="bk-muted">Selesai mengurus akun?</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bk-btn bk-btn--sm">
                        <i class="bi bi-box-arrow-right" aria-hidden="true"></i> Keluar
                    </button>
                </form>
            </div>
        </section>

        {{-- Pintasan hanya berguna bagi peserta; peran lain sudah punya menunya sendiri. --}}
        @if (($user->role ?? 'peserta') === 'peserta')
            <section class="bk-panel">
                <div class="bk-panel__head">
                    <div><h2 class="bk-panel__title">Pintasan</h2></div>
                </div>
                <a href="{{ route('profile.certificates') }}" class="bk-row">
                    <i class="bi bi-patch-check" aria-hidden="true"></i>
                    <span class="bk-row__sp">Sertifikat saya</span>
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
                <a href="{{ route('peserta.pendaftaran.index') }}" class="bk-row">
                    <i class="bi bi-clipboard-check" aria-hidden="true"></i>
                    <span class="bk-row__sp">Status pendaftaran</span>
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
                <a href="{{ route('peserta.riwayat.index') }}" class="bk-row">
                    <i class="bi bi-receipt" aria-hidden="true"></i>
                    <span class="bk-row__sp">Riwayat pembayaran</span>
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </section>
        @endif
    </div>
</div>
@endsection
