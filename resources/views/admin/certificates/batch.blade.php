@extends('layouts.admin')

@section('title', 'Sertifikat Sekelas')
@section('page-context', 'Peserta · Sertifikat')
@section('page-title', 'Buat sertifikat sekelas')
@section('page-description', 'Buat draft sertifikat sekaligus untuk seluruh peserta satu kelas yang memenuhi syarat, lalu terbitkan bersamaan.')

@section('content')

@if ($errors->any())
    <div class="bk-note bk-note--buruk">
        <i class="bi bi-exclamation-octagon-fill bk-note__icon" aria-hidden="true"></i>
        <div>
            <b>Belum bisa diproses</b>
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
            <p style="margin:.2rem 0 0">Draft tidak bisa dibuat sampai satu template ditandai aktif.
                <a href="{{ route('admin.templates.index') }}" class="bk-linkbtn">Atur template sertifikat</a>
            </p>
        </div>
    </div>
@endunless

<form method="POST" action="{{ route('admin.certificates.batch.store') }}">
    @csrf

    <section class="bk-panel">
        <div class="bk-panel__head">
            <div>
                <h2 class="bk-panel__title">Langkah 1 · Buat draft</h2>
                <p class="bk-panel__subtitle">Peserta yang sudah punya sertifikat terbit dilewati, tidak ditimpa. Draft lama di kelas yang sama akan diperbarui.</p>
            </div>
            @if ($activeTemplate)
                <span class="bk-tag bk-tag--diam">Template: {{ $activeTemplate->name }}</span>
            @endif
        </div>

        <div class="bk-panel__body">
            <div class="bk-fields">
                <div>
                    <label for="course_id" class="bk-label">Kelas</label>
                    <select id="course_id" name="course_id" class="bk-select" required>
                        <option value="">Pilih kelas</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>
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
                           value="{{ old('issued_date', now()->toDateString()) }}" required>
                    @error('issued_date')
                        <p class="bk-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bk-field--wide">
                    <label for="certificate_name" class="bk-label">Judul sertifikat <span class="bk-muted">(opsional)</span></label>
                    <input type="text" id="certificate_name" name="certificate_name" class="bk-input"
                           value="{{ old('certificate_name') }}" placeholder="Sertifikat {nama kelas}">
                    <p class="bk-hint">Kalau dikosongkan, judulnya jadi <span class="bk-code">Sertifikat {nama kelas}</span>.</p>
                    @error('certificate_name')
                        <p class="bk-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bk-panel__head" style="border-top:1px solid var(--bk-sand-100)">
            <div>
                <h3 class="bk-panel__title" style="font-size:1.05rem">Siapa yang ikut</h3>
                <p class="bk-panel__subtitle">Tiga saringan ini menentukan peserta mana dari kelas itu yang dibuatkan sertifikat.</p>
            </div>
        </div>

        <div class="bk-panel__body">
            <div class="bk-fields">
                <div>
                    <label for="registration_status" class="bk-label">Status pendaftaran</label>
                    <select id="registration_status" name="registration_status" class="bk-select">
                        <option value="selesai" @selected(old('registration_status', 'selesai') === 'selesai')>Selesai saja</option>
                        <option value="aktif" @selected(old('registration_status') === 'aktif')>Aktif saja</option>
                        <option value="aktif_selesai" @selected(old('registration_status') === 'aktif_selesai')>Aktif dan selesai</option>
                    </select>
                    @error('registration_status')
                        <p class="bk-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_status" class="bk-label">Status pembayaran</label>
                    <select id="payment_status" name="payment_status" class="bk-select">
                        <option value="lunas" @selected(old('payment_status', 'lunas') === 'lunas')>Yang sudah lunas saja</option>
                        <option value="all" @selected(old('payment_status') === 'all')>Tanpa melihat pembayaran</option>
                    </select>
                    @error('payment_status')
                        <p class="bk-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bk-field--wide">
                    <label for="min_attendance_percent" class="bk-label">Kehadiran minimal <span class="bk-muted">(opsional)</span></label>
                    <input type="number" id="min_attendance_percent" name="min_attendance_percent" class="bk-input"
                           min="0" max="100" step="1" value="{{ old('min_attendance_percent') }}" placeholder="75">
                    <p class="bk-hint">Persen kehadiran dihitung dari absensi yang tercatat. Kosongkan untuk tidak menyaring lewat absensi.</p>
                    @error('min_attendance_percent')
                        <p class="bk-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bk-panel__foot">
            <div class="bk-row">
                <button type="submit" class="bk-btn bk-btn--pri" @disabled(! $activeTemplate)>
                    <i class="bi bi-collection" aria-hidden="true"></i> Buat draft sekelas
                </button>
                <a href="{{ route('admin.certificates.index') }}" class="bk-btn">Kembali ke daftar</a>
            </div>
        </div>
    </section>
</form>

{{-- Publikasi massal punya formnya sendiri: endpointnya cuma butuh kelas,
     dan sebelumnya ia menumpang form di atas lewat formaction sehingga
     tampak seperti tombol kedua dari aksi yang sama padahal berbeda. --}}
<form method="POST" action="{{ route('admin.certificates.batch.publish') }}"
      onsubmit="return confirm('Terbitkan semua draft di kelas ini? Peserta langsung bisa mengunduhnya.')">
    @csrf

    <section class="bk-panel">
        <div class="bk-panel__head">
            <div>
                <h2 class="bk-panel__title">Langkah 2 · Terbitkan draft sekelas</h2>
                <p class="bk-panel__subtitle">Semua draft di kelas terpilih diterbitkan sekaligus. Yang sudah terbit atau dicabut tidak ikut tersentuh.</p>
            </div>
        </div>

        <div class="bk-panel__body">
            <div class="bk-fields">
                <div>
                    <label for="course_id_publish" class="bk-label">Kelas</label>
                    <select id="course_id_publish" name="course_id" class="bk-select" required>
                        <option value="">Pilih kelas</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">
                                {{ $course->nama }}{{ $course->program ? ' — ' . $course->program->nama : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="bk-panel__foot">
            <button type="submit" class="bk-btn">
                <i class="bi bi-send" aria-hidden="true"></i> Terbitkan draft kelas ini
            </button>
        </div>
    </section>
</form>
@endsection
