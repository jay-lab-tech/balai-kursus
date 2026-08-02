{{-- Dipakai bersama oleh create dan edit. $hari null saat menambah. --}}
@php ($hari = $hari ?? null)

<div class="bk-fields">
    <div>
        <label for="nama" class="bk-label">Nama hari</label>
        <input type="text" id="nama" name="nama" class="bk-input"
               value="{{ old('nama', $hari?->nama) }}" placeholder="Misal: Senin" required>
        @error('nama')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="urutan" class="bk-label">Urutan</label>
        <input type="number" id="urutan" name="urutan" class="bk-input"
               value="{{ old('urutan', $hari?->urutan) }}" min="1" max="7" required>
        <p class="bk-hint">1 untuk Senin sampai 7 untuk Minggu, supaya pilihan hari selalu berurutan.</p>
        @error('urutan')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>
</div>
