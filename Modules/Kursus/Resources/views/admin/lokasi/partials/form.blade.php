{{-- Dipakai bersama oleh create dan edit. $lokasi null saat menambah. --}}
@php ($lokasi = $lokasi ?? null)

<div class="bk-fields">
    <div class="bk-field--wide">
        <label for="nama" class="bk-label">Nama lokasi</label>
        <input type="text" id="nama" name="nama" class="bk-input"
               value="{{ old('nama', $lokasi?->nama) }}"
               placeholder="Misal: Gedung Balai Bahasa UPI" required>
        @error('nama')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="bk-field--wide">
        <label for="alamat" class="bk-label">Alamat</label>
        <textarea id="alamat" name="alamat" rows="3" class="bk-textarea" required>{{ old('alamat', $lokasi?->alamat) }}</textarea>
        @error('alamat')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="kota" class="bk-label">Kota</label>
        <input type="text" id="kota" name="kota" class="bk-input"
               value="{{ old('kota', $lokasi?->kota) }}" required>
        @error('kota')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="provinsi" class="bk-label">Provinsi</label>
        <input type="text" id="provinsi" name="provinsi" class="bk-input"
               value="{{ old('provinsi', $lokasi?->provinsi) }}" required>
        @error('provinsi')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="no_telp" class="bk-label">Nomor telepon</label>
        <input type="text" id="no_telp" name="no_telp" class="bk-input" inputmode="tel"
               value="{{ old('no_telp', $lokasi?->no_telp) }}" required>
        <p class="bk-hint">Nomor yang bisa dihubungi peserta saat mencari lokasi.</p>
        @error('no_telp')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="bk-field--wide">
        <label for="keterangan" class="bk-label">Keterangan <span class="bk-muted">(opsional)</span></label>
        <textarea id="keterangan" name="keterangan" rows="3" class="bk-textarea"
                  placeholder="Patokan arah, akses parkir, atau catatan operasional lain.">{{ old('keterangan', $lokasi?->keterangan) }}</textarea>
        @error('keterangan')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>
</div>
