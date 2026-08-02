@extends('instruktur::layouts.master')

@section('title', 'Ubah risalah pertemuan '.$risalah->pertemuan_ke)
@section('page-context', 'Instruktur · '.($risalah->kursus->nama ?? 'Kursus'))
@section('page-description', 'Catat materi yang disampaikan dan lampirkan dokumen pendukung bila ada.')

@section('content')

@if ($errors->any())
    <div class="bk-note bk-note--perlu">
        <i class="bi bi-exclamation-triangle-fill bk-note__icon" aria-hidden="true"></i>
        <div>
            <b>Ada isian yang perlu diperbaiki.</b>
            <ul>
                @foreach ($errors->all() as $pesan)
                    <li>{{ $pesan }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form method="POST" action="{{ route('instruktur.risalah.update', $risalah) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <section class="bk-panel">
        <div class="bk-panel__head">
            <div>
                <h2 class="bk-panel__title">Pertemuan {{ $risalah->pertemuan_ke }}</h2>
                <p class="bk-panel__subtitle">
                    {{ $risalah->kursus->program->nama ?? 'Tanpa program' }} ·
                    {{ $risalah->kursus->level->nama ?? 'Level belum ditentukan' }} ·
                    {{ $risalah->tgl_pertemuan ? \Carbon\Carbon::parse($risalah->tgl_pertemuan)->translatedFormat('j F Y') : 'Tanggal belum ditentukan' }}
                </p>
            </div>
        </div>

        <div class="bk-panel__body">
            <div class="bk-fields">
                <div class="bk-field bk-field--wide">
                    <label class="bk-label" for="materi">Materi <span aria-hidden="true">*</span></label>
                    <input type="text" id="materi" name="materi" class="bk-input"
                           value="{{ old('materi', $risalah->materi) }}" required
                           placeholder="Misal: Simple past tense dan latihan percakapan">
                    @error('materi')<p class="bk-error">{{ $message }}</p>@enderror
                </div>

                <div class="bk-field bk-field--wide">
                    <label class="bk-label" for="catatan">Catatan pertemuan</label>
                    <textarea id="catatan" name="catatan" rows="6" class="bk-textarea"
                              placeholder="Hal yang perlu diingat: peserta yang tertinggal, tugas yang diberikan, rencana pertemuan berikutnya.">{{ old('catatan', $risalah->catatan) }}</textarea>
                    <p class="bk-hint">Opsional. Hanya dibaca oleh Anda dan admin.</p>
                    @error('catatan')<p class="bk-error">{{ $message }}</p>@enderror
                </div>

                <div class="bk-field bk-field--wide">
                    <label class="bk-label" for="dokumen">Dokumen pendukung</label>
                    @if ($risalah->dokumen)
                        <p class="bk-hint">
                            Sudah ada berkas terlampir —
                            <a href="{{ route('instruktur.risalah.download', $risalah) }}" class="bk-linkbtn">unduh berkas saat ini</a>.
                            Mengunggah berkas baru akan menggantikannya.
                        </p>
                    @endif
                    <input type="file" id="dokumen" name="dokumen" class="bk-input"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                    <p class="bk-hint">PDF, Word, Excel, PowerPoint, atau gambar. Maksimal 5 MB.</p>
                    @error('dokumen')<p class="bk-error">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="bk-panel__foot">
            <a href="{{ route('instruktur.risalah.index', $risalah->kursus_id) }}" class="bk-linkbtn">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke daftar pertemuan
            </a>
            <span class="bk-row">
                <a href="{{ route('instruktur.risalah.index', $risalah->kursus_id) }}" class="bk-btn bk-btn--sm">Batal</a>
                <button type="submit" class="bk-btn bk-btn--pri bk-btn--sm">
                    <i class="bi bi-check2" aria-hidden="true"></i> Simpan perubahan
                </button>
            </span>
        </div>
    </section>
</form>
@endsection
