{{-- Dipakai bersama oleh create dan edit. $level null saat menambah. --}}
@php ($level = $level ?? null)

<div class="bk-fields">
    <div>
        <label for="nama" class="bk-label">Nama level</label>
        <input type="text" id="nama" name="nama" class="bk-input"
               value="{{ old('nama', $level?->nama) }}" placeholder="Misal: Dasar 1" required>
        @error('nama')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="urutan" class="bk-label">Urutan</label>
        <input type="number" id="urutan" name="urutan" class="bk-input"
               value="{{ old('urutan', $level?->urutan ?? 1) }}" min="1" required>
        <p class="bk-hint">Menentukan posisi level saat ditampilkan, dari yang paling dasar.</p>
        @error('urutan')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="nilai_min" class="bk-label">Nilai minimum</label>
        <input type="number" step="0.01" id="nilai_min" name="nilai_min" class="bk-input"
               value="{{ old('nilai_min', $level?->nilai_min) }}" required>
        @error('nilai_min')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="nilai_max" class="bk-label">Nilai maksimum</label>
        <input type="number" step="0.01" id="nilai_max" name="nilai_max" class="bk-input"
               value="{{ old('nilai_max', $level?->nilai_max) }}" required>
        @error('nilai_max')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="bk-field--wide">
        <label for="deskripsi" class="bk-label">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="4" class="bk-textarea"
                  placeholder="Gambaran singkat kemampuan peserta di jenjang ini.">{{ old('deskripsi', $level?->deskripsi) }}</textarea>
        @error('deskripsi')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>
</div>
