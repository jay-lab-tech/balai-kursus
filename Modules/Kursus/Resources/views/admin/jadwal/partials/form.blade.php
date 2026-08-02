{{-- Dipakai bersama oleh create dan edit. $jadwal null saat menambah. --}}
@php ($jadwal = $jadwal ?? null)

<div class="bk-fields">
    <div>
        <label for="pertemuan_ke" class="bk-label">Pertemuan ke <span class="bk-muted">(opsional)</span></label>
        <input type="number" id="pertemuan_ke" name="pertemuan_ke" class="bk-input" min="1"
               value="{{ old('pertemuan_ke', $jadwal?->pertemuan_ke) }}">
        <p class="bk-hint">Nomor urut pertemuan dalam satu kelas.</p>
        @error('pertemuan_ke')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="tgl_pertemuan" class="bk-label">Tanggal pertemuan</label>
        <input type="date" id="tgl_pertemuan" name="tgl_pertemuan" class="bk-input"
               value="{{ old('tgl_pertemuan', $jadwal?->tgl_pertemuan?->format('Y-m-d')) }}" required>
        @error('tgl_pertemuan')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="jam_mulai" class="bk-label">Jam mulai</label>
        <input type="time" id="jam_mulai" name="jam_mulai" class="bk-input"
               value="{{ old('jam_mulai', $jadwal?->jam_mulai) }}">
        @error('jam_mulai')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="jam_selesai" class="bk-label">Jam selesai</label>
        <input type="time" id="jam_selesai" name="jam_selesai" class="bk-input"
               value="{{ old('jam_selesai', $jadwal?->jam_selesai) }}">
        @error('jam_selesai')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="hari_id" class="bk-label">Hari</label>
        <select id="hari_id" name="hari_id" class="bk-select" required>
            <option value="">Pilih hari</option>
            @foreach ($haris as $hari)
                <option value="{{ $hari->id }}" @selected(old('hari_id', $jadwal?->hari_id) == $hari->id)>{{ $hari->nama }}</option>
            @endforeach
        </select>
        @error('hari_id')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="lokasi_id" class="bk-label">Lokasi</label>
        <select id="lokasi_id" name="lokasi_id" class="bk-select" required>
            <option value="">Pilih lokasi</option>
            @foreach ($lokasis as $lokasi)
                <option value="{{ $lokasi->id }}" @selected(old('lokasi_id', $jadwal?->lokasi_id) == $lokasi->id)>{{ $lokasi->nama }} — {{ $lokasi->kota }}</option>
            @endforeach
        </select>
        @error('lokasi_id')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="kela_id" class="bk-label">Ruang kelas</label>
        <select id="kela_id" name="kela_id" class="bk-select" required>
            <option value="">Pilih ruang</option>
            @foreach ($kelas as $k)
                <option value="{{ $k->id }}" @selected(old('kela_id', $jadwal?->kela_id) == $k->id)>{{ $k->nama }} ({{ $k->kapasitas ?? '-' }} kursi)</option>
            @endforeach
        </select>
        @error('kela_id')
            <p class="bk-error">{{ $message }}</p>
        @enderror
    </div>
</div>
