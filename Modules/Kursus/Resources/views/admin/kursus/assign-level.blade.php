@extends('layouts.admin')

@section('title', 'Tetapkan Level Peserta')
@section('page-context', 'Akademik · Kelas ' . $kursus->nama)
@section('page-title', 'Tetapkan level peserta')
@section('page-description', 'Menyimpan level akan memindahkan peserta ke kelas ini dan mengubah statusnya menjadi menunggu pembayaran.')

@section('content')

@if ($errors->any())
    <div class="bk-note bk-note--buruk">
        <i class="bi bi-exclamation-triangle-fill bk-note__icon" aria-hidden="true"></i>
        <span>Ada yang perlu diperbaiki: {{ $errors->first() }}</span>
    </div>
@endif

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">{{ $pendaftaran->peserta->user->name ?? 'Peserta' }}</h2>
            <p class="bk-panel__subtitle">Pilih level yang sesuai dengan hasil tes penempatan peserta.</p>
        </div>
    </div>

    <div class="bk-panel__body">
        <dl class="bk-kv">
            <div>
                <dt>Kelas tujuan</dt>
                <dd>{{ $kursus->nama }}</dd>
            </div>
            <div>
                <dt>Nilai tes</dt>
                <dd>{{ $pendaftaran->placementScore?->final_score ?? 'Belum ada' }}</dd>
            </div>
            <div>
                <dt>Biaya yang akan ditagihkan</dt>
                <dd>Rp {{ number_format($kursus->harga, 0, ',', '.') }}</dd>
            </div>
        </dl>
    </div>

    <form method="POST" action="{{ route('admin.kursus.assignLevel', [$kursus->id, $pendaftaran->id]) }}" class="bk-panel__body">
        @csrf

        <div class="bk-fields">
            <div class="bk-field--wide">
                <label for="level_id" class="bk-label">Level penempatan</label>
                <select id="level_id" name="level_id" class="bk-select" required>
                    <option value="">Pilih level</option>
                    @foreach ($levels as $level)
                        <option value="{{ $level->id }}" @selected(old('level_id', $pendaftaran->level_id) == $level->id)>{{ $level->nama }}</option>
                    @endforeach
                </select>
                @error('level_id')
                    <p class="bk-error">{{ $message }}</p>
                @enderror
                <p class="bk-hint">Peserta akan diminta melunasi biaya kelas setelah level disimpan.</p>
            </div>
        </div>

        <div class="bk-row" style="margin-top:1.5rem">
            <button type="submit" class="bk-btn bk-btn--pri">
                <i class="bi bi-check2" aria-hidden="true"></i> Simpan level
            </button>
            <a href="{{ route('admin.kursus.peserta', $kursus->id) }}" class="bk-btn">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Batal
            </a>
        </div>
    </form>
</section>
@endsection
