{{-- Dipakai bersama oleh create dan edit. $program null saat menambah. --}}
@php
    $program = $program ?? null;
    $namaAwal = old('nama', $program?->nama ?? '');
    $warnaAwal = old('warna', $program?->warna ?? '#c05f3c');
@endphp

<div x-data="{ nama: @js($namaAwal), warna: @js($warnaAwal) }">
    <div class="bk-fields">
        <div>
            <label for="nama" class="bk-label">Nama program</label>
            <input type="text" id="nama" name="nama" class="bk-input" x-model="nama"
                   placeholder="Misal: English Intensive" required>
            @error('nama')
                <p class="bk-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="warna" class="bk-label">Warna identitas</label>
            <input type="color" id="warna" name="warna" class="bk-input" x-model="warna"
                   style="height:2.9rem;padding:.25rem">
            <p class="bk-hint">Dipakai sebagai penanda program di daftar kursus dan papan informasi.</p>
            @error('warna')
                <p class="bk-error">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="bk-note" style="margin-top:1.25rem;align-items:center">
        {{-- Binding objek, bukan string: Alpine menimpa seluruh atribut style
             kalau diberi string, sehingga ukuran kotaknya ikut hilang. --}}
        <span aria-hidden="true"
              style="flex:none;width:2.5rem;height:2.5rem;border-radius:.75rem;border:1px solid var(--bk-sand)"
              :style="{ backgroundColor: warna }"></span>
        <span>
            <b x-text="nama || 'Nama program akan tampil di sini'"></b><br>
            <span class="bk-muted">Begini penanda program akan terlihat oleh peserta.</span>
        </span>
    </div>
</div>
