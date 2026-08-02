{{-- Dipakai bersama oleh create dan edit. $kursus null saat menambah. --}}
@php ($kursus = $kursus ?? null)

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Identitas kelas</h2>
            <p class="bk-panel__subtitle">Program dan level menentukan peserta mana yang bisa ditempatkan di sini.</p>
        </div>
    </div>

    <div class="bk-panel__body">
        <div class="bk-fields">
            <div>
                <label for="nama" class="bk-label">Nama kelas</label>
                <input type="text" id="nama" name="nama" class="bk-input"
                       value="{{ old('nama', $kursus?->nama) }}"
                       placeholder="Misal: General English — Beginner Kelas 1" required>
                @error('nama')
                    <p class="bk-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="periode" class="bk-label">Periode <span class="bk-muted">(opsional)</span></label>
                <input type="text" id="periode" name="periode" class="bk-input"
                       value="{{ old('periode', $kursus?->periode) }}" placeholder="Misal: Ganjil 2026">
                @error('periode')
                    <p class="bk-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="program_id" class="bk-label">Program</label>
                <select id="program_id" name="program_id" class="bk-select" required>
                    <option value="">Pilih program</option>
                    @foreach ($program as $item)
                        <option value="{{ $item->id }}" @selected((string) old('program_id', $kursus?->program_id) === (string) $item->id)>{{ $item->nama }}</option>
                    @endforeach
                </select>
                @error('program_id')
                    <p class="bk-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="level_id" class="bk-label">Level</label>
                <select id="level_id" name="level_id" class="bk-select" required>
                    <option value="">Pilih level</option>
                    @foreach ($levels as $level)
                        <option value="{{ $level->id }}" @selected((string) old('level_id', $kursus?->level_id) === (string) $level->id)>{{ $level->nama }} ({{ $level->rentang_nilai }})</option>
                    @endforeach
                </select>
                @error('level_id')
                    <p class="bk-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</section>

<section class="bk-panel" style="margin-top:1.5rem">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Periode, biaya, dan kuota</h2>
            <p class="bk-panel__subtitle">Kuota membatasi jumlah pendaftaran yang bisa diterima kelas ini.</p>
        </div>
    </div>

    <div class="bk-panel__body">
        <div class="bk-fields">
            <div>
                <label for="tanggal_mulai" class="bk-label">Tanggal mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="bk-input"
                       value="{{ old('tanggal_mulai', $kursus?->tanggal_mulai?->format('Y-m-d')) }}" required>
                @error('tanggal_mulai')
                    <p class="bk-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tanggal_selesai" class="bk-label">Tanggal selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="bk-input"
                       value="{{ old('tanggal_selesai', $kursus?->tanggal_selesai?->format('Y-m-d')) }}" required>
                @error('tanggal_selesai')
                    <p class="bk-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="harga" class="bk-label">Harga umum</label>
                <input type="number" id="harga" name="harga" class="bk-input" min="0" step="1000"
                       value="{{ old('harga', $kursus?->harga) }}" required>
                <p class="bk-hint">Dalam rupiah, tanpa titik pemisah.</p>
                @error('harga')
                    <p class="bk-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="harga_upi" class="bk-label">Harga sivitas UPI <span class="bk-muted">(opsional)</span></label>
                <input type="number" id="harga_upi" name="harga_upi" class="bk-input" min="0" step="1000"
                       value="{{ old('harga_upi', $kursus?->harga_upi) }}">
                <p class="bk-hint">Kosongkan bila tidak ada tarif khusus.</p>
                @error('harga_upi')
                    <p class="bk-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="kuota" class="bk-label">Kuota peserta</label>
                <input type="number" id="kuota" name="kuota" class="bk-input" min="1"
                       value="{{ old('kuota', $kursus?->kuota) }}" required>
                @error('kuota')
                    <p class="bk-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="status" class="bk-label">Status</label>
                @php ($statusKini = old('status', $kursus?->status ?? 'buka'))
                <select id="status" name="status" class="bk-select" required>
                    <option value="buka" @selected($statusKini === 'buka')>Buka — menerima pendaftaran</option>
                    <option value="berjalan" @selected($statusKini === 'berjalan')>Berjalan — kelas sedang aktif</option>
                    <option value="tutup" @selected($statusKini === 'tutup')>Tutup — tidak menerima pendaftaran</option>
                </select>
                @error('status')
                    <p class="bk-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</section>
