@php
    $score = $score ?? null;
    $selectedPendaftaranId = $selectedPendaftaranId ?? null;

    $komponen = [
        'listening' => 'Listening',
        'speaking' => 'Speaking',
        'reading' => 'Reading',
        'writing' => 'Writing',
        'assignment' => 'Assignment',
    ];

    $statusKini = old('status', $score->status ?? 'pass');
@endphp

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Pendaftaran yang dinilai</h2>
            <p class="bk-panel__subtitle">Satu pendaftaran hanya boleh punya satu hasil tes penempatan.</p>
        </div>
    </div>

    <div class="bk-panel__body">
        <div class="bk-fields">
            <div class="bk-field--wide">
                <label for="pendaftaran_id" class="bk-label">Pendaftaran program</label>
                <select id="pendaftaran_id" name="pendaftaran_id" class="bk-select" required>
                    <option value="">Pilih pendaftaran</option>
                    @foreach ($pendaftarans as $pendaftaran)
                        <option value="{{ $pendaftaran->id }}"
                            @selected(old('pendaftaran_id', $score->pendaftaran_id ?? $selectedPendaftaranId) == $pendaftaran->id)>
                            {{ $pendaftaran->nomor }} — {{ $pendaftaran->peserta->user->name ?? 'Tanpa nama' }} ({{ $pendaftaran->program->nama ?? 'Tanpa program' }})
                        </option>
                    @endforeach
                </select>
                @error('pendaftaran_id')
                    <p class="bk-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</section>

<section class="bk-panel" style="margin-top:1.5rem"
         x-data="{
             komponen: {
                 @foreach ($komponen as $kolom => $label)
                     {{ $kolom }}: {{ json_encode(old($kolom, $score->{$kolom} ?? null) ?? '') }},
                 @endforeach
             },
             get rerata() {
                 const angka = Object.values(this.komponen)
                     .filter(nilai => nilai !== '' && nilai !== null)
                     .map(Number)
                     .filter(nilai => ! isNaN(nilai));

                 if (! angka.length) return null;

                 return Math.round((angka.reduce((a, b) => a + b, 0) / angka.length) * 100) / 100;
             },
         }">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Komponen nilai</h2>
            <p class="bk-panel__subtitle">Lima komponen tes penempatan, masing-masing berskala 0–100.</p>
        </div>
    </div>

    <div class="bk-panel__body">
        <div class="bk-fields">
            @foreach ($komponen as $kolom => $label)
                <div>
                    <label for="{{ $kolom }}" class="bk-label">{{ $label }}</label>
                    <input type="number" id="{{ $kolom }}" name="{{ $kolom }}" class="bk-input"
                           min="0" max="100" x-model.number="komponen.{{ $kolom }}"
                           value="{{ old($kolom, $score->{$kolom} ?? '') }}" required>
                    @error($kolom)
                        <p class="bk-error">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>
    </div>

    <div class="bk-panel__head" style="border-top:1px solid var(--bk-sand-100)">
        <div>
            <h2 class="bk-panel__title">Hasil evaluasi</h2>
            <p class="bk-panel__subtitle">Nilai akhir inilah yang dipakai sistem untuk menentukan level dan kelas peserta.</p>
        </div>
    </div>

    <div class="bk-panel__body">
        <div class="bk-fields">
            <div>
                <label for="final_score" class="bk-label">Nilai akhir</label>
                <input type="number" step="0.01" id="final_score" name="final_score" class="bk-input"
                       min="0" max="100" value="{{ old('final_score', $score->final_score ?? '') }}" required>
                @error('final_score')
                    <p class="bk-error">{{ $message }}</p>
                @enderror
                <p class="bk-hint" x-show="rerata !== null" x-cloak>
                    Rata-rata komponen di atas: <b x-text="rerata"></b>.
                    <button type="button" class="bk-linkbtn"
                            @click="document.getElementById('final_score').value = rerata">Pakai angka itu</button>
                </p>
            </div>

            <div>
                <label for="status" class="bk-label">Status hasil</label>
                <select id="status" name="status" class="bk-select" required>
                    <option value="pass" @selected($statusKini === 'pass')>Lulus — peserta ditempatkan</option>
                    <option value="fail" @selected($statusKini === 'fail')>Tidak lulus</option>
                    <option value="pending" @selected($statusKini === 'pending')>Tertunda — masih ditinjau</option>
                </select>
                @error('status')
                    <p class="bk-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="evaluated_by" class="bk-label">Penguji</label>
                <select id="evaluated_by" name="evaluated_by" class="bk-select" required>
                    <option value="">Pilih penguji</option>
                    @foreach ($instrukturs as $instruktur)
                        <option value="{{ $instruktur->id }}" @selected(old('evaluated_by', $score->evaluated_by ?? null) == $instruktur->id)>
                            {{ $instruktur->nama_instr ?? $instruktur->user->name ?? 'Tanpa nama' }}
                        </option>
                    @endforeach
                </select>
                @error('evaluated_by')
                    <p class="bk-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="evaluated_at" class="bk-label">Tanggal evaluasi</label>
                <input type="date" id="evaluated_at" name="evaluated_at" class="bk-input"
                       value="{{ old('evaluated_at', $score?->evaluated_at?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                @error('evaluated_at')
                    <p class="bk-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="bk-field--wide">
                <label for="keterangan" class="bk-label">Catatan <span class="bk-muted">(opsional)</span></label>
                <textarea id="keterangan" name="keterangan" rows="3" class="bk-input">{{ old('keterangan', $score->keterangan ?? '') }}</textarea>
                @error('keterangan')
                    <p class="bk-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</section>
