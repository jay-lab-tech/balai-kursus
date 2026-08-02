{{-- Dipakai bersama oleh create dan edit. $peserta null saat menambah;
     kata sandi hanya diminta saat membuat akun baru. --}}
@php
    $peserta = $peserta ?? null;
    $baru = $peserta === null;
@endphp

<div class="bk-fields">
    <div>
        <label for="nama" class="bk-label">Nama lengkap</label>
        <input type="text" id="nama" name="nama" class="bk-input"
               value="{{ old('nama', $peserta?->user?->name) }}" required>
        @error('nama')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="bk-label">Alamat email</label>
        <input type="email" id="email" name="email" class="bk-input"
               value="{{ old('email', $peserta?->user?->email) }}"
               placeholder="nama@contoh.com" required>
        <p class="bk-hint">Dipakai peserta untuk masuk ke akunnya.</p>
        @error('email')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    @if ($baru)
        <div class="bk-field--wide">
            <label for="password" class="bk-label">Kata sandi awal</label>
            <input type="password" id="password" name="password" class="bk-input"
                   minlength="6" autocomplete="new-password" required>
            <p class="bk-hint">Minimal 6 karakter. Sampaikan ke peserta agar bisa segera mengganti sendiri.</p>
            @error('password')
                <p class="bk-error">{{ $message }}</p>
            @enderror
        </div>
    @endif

    <div>
        <label for="nomor_peserta" class="bk-label">Nomor peserta</label>
        <input type="text" id="nomor_peserta" name="nomor_peserta" class="bk-input"
               value="{{ old('nomor_peserta', $peserta?->nomor_peserta) }}" required>
        <p class="bk-hint">Harus unik; dipakai sebagai rujukan pada sertifikat.</p>
        @error('nomor_peserta')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="no_hp" class="bk-label">Nomor HP</label>
        <input type="text" id="no_hp" name="no_hp" class="bk-input" inputmode="tel"
               value="{{ old('no_hp', $peserta?->no_hp) }}" placeholder="08xxxxxxxxxx" required>
        @error('no_hp')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="bk-field--wide">
        <label for="instansi" class="bk-label">Instansi <span class="bk-muted">(opsional)</span></label>
        <input type="text" id="instansi" name="instansi" class="bk-input"
               value="{{ old('instansi', $peserta?->instansi) }}"
               placeholder="Asal sekolah, kampus, atau kantor">
        @error('instansi')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>
</div>
