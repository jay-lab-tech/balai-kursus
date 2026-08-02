{{-- Dipakai bersama oleh create dan edit. $kela null saat menambah. --}}
@php ($kela = $kela ?? null)

<div class="bk-fields">
    <div>
        <label for="nama" class="bk-label">Nama kelas</label>
        <input type="text" id="nama" name="nama" class="bk-input"
               value="{{ old('nama', $kela?->nama) }}" placeholder="Misal: Ruang Melati" required>
        @error('nama')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="kapasitas" class="bk-label">Kapasitas</label>
        <input type="number" id="kapasitas" name="kapasitas" class="bk-input" min="1"
               value="{{ old('kapasitas', $kela?->kapasitas) }}" required>
        <p class="bk-hint">Jumlah peserta yang bisa ditampung sekaligus.</p>
        @error('kapasitas')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="bk-field--wide">
        <label for="fasilitas" class="bk-label">Fasilitas</label>
        <input type="text" id="fasilitas" name="fasilitas" class="bk-input"
               value="{{ old('fasilitas', $kela?->fasilitas) }}"
               placeholder="Misal: Proyektor, AC, papan tulis" required>
        @error('fasilitas')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="bk-field--wide">
        <label for="keterangan" class="bk-label">Keterangan <span class="bk-muted">(opsional)</span></label>
        <textarea id="keterangan" name="keterangan" rows="3" class="bk-textarea"
                  placeholder="Catatan kondisi ruang atau aturan pemakaian.">{{ old('keterangan', $kela?->keterangan) }}</textarea>
        @error('keterangan')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>
</div>
