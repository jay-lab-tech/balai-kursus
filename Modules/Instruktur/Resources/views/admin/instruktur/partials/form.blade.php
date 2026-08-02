{{-- Dipakai bersama oleh create dan edit. $instruktur null saat menambah;
     kata sandi hanya diminta saat membuat akun baru. --}}
@php
    $instruktur = $instruktur ?? null;
    $baru = $instruktur === null;
@endphp

<div class="bk-fields">
    <div>
        <label for="name" class="bk-label">Nama akun</label>
        <input type="text" id="name" name="name" class="bk-input"
               value="{{ old('name', $instruktur?->user?->name) }}" required>
        <p class="bk-hint">Nama yang tampil saat instruktur masuk ke sistem.</p>
        @error('name')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="bk-label">Alamat email</label>
        <input type="email" id="email" name="email" class="bk-input"
               value="{{ old('email', $instruktur?->user?->email) }}"
               placeholder="nama@contoh.com" required>
        @error('email')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    @if ($baru)
        <div class="bk-field--wide">
            <label for="password" class="bk-label">Kata sandi awal</label>
            <input type="password" id="password" name="password" class="bk-input"
                   minlength="6" autocomplete="new-password" required>
            <p class="bk-hint">Minimal 6 karakter. Akun langsung terverifikasi dan bisa dipakai masuk.</p>
            @error('password')
                <p class="bk-error">{{ $message }}</p>
            @enderror
        </div>
    @endif

    <div>
        <label for="nama_instr" class="bk-label">Nama pengajar</label>
        <input type="text" id="nama_instr" name="nama_instr" class="bk-input"
               value="{{ old('nama_instr', $instruktur?->nama_instr) }}" required>
        <p class="bk-hint">Nama beserta gelar yang dicetak di jadwal dan sertifikat.</p>
        @error('nama_instr')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="spesialisasi" class="bk-label">Spesialisasi</label>
        <input type="text" id="spesialisasi" name="spesialisasi" class="bk-input"
               value="{{ old('spesialisasi', $instruktur?->spesialisasi) }}"
               placeholder="Misal: Conversation, IELTS Preparation" required>
        @error('spesialisasi')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>
</div>
