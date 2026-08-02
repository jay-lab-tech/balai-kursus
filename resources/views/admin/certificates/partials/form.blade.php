@php
    $certificate = $certificate ?? null;

    $courseTerpilih = old('course_id', $certificate?->course_id);
    $pesertaTerpilih = old('participant_id', $certificate?->participant_id);
@endphp

@if ($errors->any())
    <div class="bk-note bk-note--buruk">
        <i class="bi bi-exclamation-octagon-fill bk-note__icon" aria-hidden="true"></i>
        <div>
            <b>Sertifikat belum bisa disimpan</b>
            <ul style="margin:.35rem 0 0;padding-left:1.1rem">
                @foreach ($errors->all() as $pesan)
                    <li>{{ $pesan }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@unless ($activeTemplate)
    <div class="bk-note bk-note--perlu">
        <i class="bi bi-exclamation-triangle-fill bk-note__icon" aria-hidden="true"></i>
        <div>
            <b>Belum ada template aktif</b>
            <p style="margin:.2rem 0 0">Sertifikat tidak bisa disimpan sampai satu template ditandai aktif.
                <a href="{{ route('admin.templates.index') }}" class="bk-linkbtn">Atur template sertifikat</a>
            </p>
        </div>
    </div>
@endunless

<form method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <section class="bk-panel">
        <div class="bk-panel__head">
            <div>
                <h2 class="bk-panel__title">Isi sertifikat</h2>
                <p class="bk-panel__subtitle">Nama program, jam pelajaran, dan tanggal kelas diambil otomatis dari kelas yang dipilih.</p>
            </div>
        </div>

        <div class="bk-panel__body">
            <div class="bk-fields">
                <div class="bk-field--wide">
                    <label for="certificate_name" class="bk-label">Judul sertifikat</label>
                    <input type="text" id="certificate_name" name="certificate_name" class="bk-input"
                           value="{{ old('certificate_name', $certificate?->certificate_name) }}"
                           placeholder="Contoh: Sertifikat IELTS Preparation Course" required>
                    @error('certificate_name')
                        <p class="bk-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="course_id" class="bk-label">Kelas</label>
                    <select id="course_id" name="course_id" class="bk-select" required>
                        <option value="">Pilih kelas</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @selected($courseTerpilih == $course->id)>
                                {{ $course->nama }}{{ $course->program ? ' — ' . $course->program->nama : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <p class="bk-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="issued_date" class="bk-label">Tanggal terbit</label>
                    <input type="date" id="issued_date" name="issued_date" class="bk-input"
                           value="{{ old('issued_date', $certificate?->issued_date?->toDateString() ?? now()->toDateString()) }}" required>
                    <p class="bk-hint">Tahun pada tanggal ini ikut membentuk nomor sertifikat.</p>
                    @error('issued_date')
                        <p class="bk-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bk-field--wide">
                    <label for="participant_id" class="bk-label">Peserta</label>
                    <select id="participant_id" name="participant_id" class="bk-select" required
                            data-terpilih="{{ $pesertaTerpilih }}">
                        {{-- Daftar peserta dimuat lewat fetch setelah kelas dipilih.
                             Peserta yang sedang tersimpan tetap dirender di sini
                             supaya pilihannya tidak hilang kalau permintaan gagal. --}}
                        @if ($certificate?->participant)
                            <option value="{{ $certificate->participant->id }}" selected>
                                {{ $certificate->participant->nomor_peserta }} — {{ $certificate->participant->user->name ?? 'Tanpa nama' }}
                            </option>
                        @else
                            <option value="">Pilih kelas dulu</option>
                        @endif
                    </select>
                    <p class="bk-hint">Yang muncul hanya peserta kelas itu dengan pendaftaran berstatus aktif atau selesai.</p>
                    @error('participant_id')
                        <p class="bk-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bk-panel__foot">
            <div class="bk-row">
                <button type="submit" class="bk-btn bk-btn--pri" @disabled(! $activeTemplate)>
                    <i class="bi bi-check-lg" aria-hidden="true"></i> {{ $submitLabel }}
                </button>
                @if ($certificate)
                    <a href="{{ route('admin.certificates.preview', $certificate->id) }}" target="_blank" rel="noopener" class="bk-btn">
                        <i class="bi bi-file-earmark-pdf" aria-hidden="true"></i> Pratinjau PDF
                    </a>
                @endif
                <a href="{{ route('admin.certificates.index') }}" class="bk-btn">Batal</a>
            </div>
        </div>
    </section>
</form>

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Template yang dipakai</h2>
            <p class="bk-panel__subtitle">Isi template ini disalin ke sertifikat saat disimpan, jadi tidak ikut berubah kalau templatenya diedit nanti.</p>
        </div>
        @if ($activeTemplate)
            <a href="{{ route('admin.templates.edit', $activeTemplate) }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-sliders" aria-hidden="true"></i> Ubah template
            </a>
        @endif
    </div>

    <div class="bk-panel__body">
        @if ($activeTemplate)
            <dl class="bk-kv">
                <div>
                    <dt>Template</dt>
                    <dd>{{ $activeTemplate->name }}</dd>
                </div>
                <div>
                    <dt>Lembaga</dt>
                    <dd style="font-size:.92rem">{{ $activeTemplate->institution_name }} — {{ $activeTemplate->unit_name }}</dd>
                </div>
                <div>
                    <dt>Kota terbit</dt>
                    <dd>{{ $activeTemplate->city }}</dd>
                </div>
                <div>
                    <dt>Penandatangan</dt>
                    <dd style="font-size:.92rem">{{ $activeTemplate->signer_name }}</dd>
                </div>
                <div>
                    <dt>Jabatan</dt>
                    <dd style="font-size:.92rem">{{ $activeTemplate->signer_title }}</dd>
                </div>
                <div>
                    <dt>Kode nomor</dt>
                    <dd>{{ $activeTemplate->certificate_prefix }}</dd>
                </div>
            </dl>
        @else
            <p class="bk-muted">Belum ada template aktif yang bisa dipakai.</p>
        @endif
    </div>
</section>
