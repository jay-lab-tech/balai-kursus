@extends('layouts.admin')

@section('title', 'Detail Tes Penempatan')
@section('page-context', 'Peserta · Nilai')
@section('page-title', 'Hasil tes ' . ($score->pendaftaran->peserta->user->name ?? 'peserta'))
@section('page-description', ($score->pendaftaran->nomor ?? '—') . ' · ' . ($score->pendaftaran->program->nama ?? 'Tanpa program') . ' — rincian komponen nilai dan keputusan penempatan yang dihasilkan.')

@section('content')

@php
    $komponen = [
        'listening' => 'Listening',
        'speaking' => 'Speaking',
        'reading' => 'Reading',
        'writing' => 'Writing',
        'assignment' => 'Assignment',
    ];

    $rupaHasil = match ($score->status) {
        'pass' => '',
        'fail' => 'bk-tag--gagal',
        default => 'bk-tag--jalan',
    };

    $labelHasil = match ($score->status) {
        'pass' => 'Lulus',
        'fail' => 'Tidak lulus',
        default => 'Tertunda',
    };
@endphp

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Komponen nilai</h2>
            <p class="bk-panel__subtitle">Lima komponen tes, masing-masing berskala 0–100.</p>
        </div>
        <div class="bk-row">
            <a href="{{ route('admin.score.edit', $score) }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-pencil" aria-hidden="true"></i> Ubah nilai
            </a>
            <a href="{{ route('admin.score.index') }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Daftar nilai
            </a>
        </div>
    </div>

    <div class="bk-panel__body">
        <dl class="bk-kv">
            @foreach ($komponen as $kolom => $label)
                <div>
                    <dt>{{ $label }}</dt>
                    <dd>{{ $score->{$kolom} }}</dd>
                </div>
            @endforeach
            <div>
                <dt>Nilai akhir</dt>
                <dd><b>{{ $score->final_score }}</b></dd>
            </div>
        </dl>
    </div>
</section>

<section class="bk-panel" style="margin-top:1.5rem">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Keputusan penempatan</h2>
            <p class="bk-panel__subtitle">Hasil yang dijalankan sistem berdasarkan nilai akhir dan kuota kelas.</p>
        </div>
        <span class="bk-tag {{ $rupaHasil }}">{{ $labelHasil }}</span>
    </div>

    <div class="bk-panel__body">
        <dl class="bk-kv">
            <div>
                <dt>Level</dt>
                <dd>{{ $score->pendaftaran->level->nama ?? 'Belum terpetakan' }}</dd>
            </div>
            <div>
                <dt>Kelas</dt>
                <dd>{{ $score->pendaftaran->kursus->nama ?? 'Belum ditempatkan' }}</dd>
            </div>
            <div>
                <dt>Status pendaftaran</dt>
                <dd>{{ ucfirst(str_replace('_', ' ', $score->pendaftaran->status_pendaftaran)) }}</dd>
            </div>
            <div>
                <dt>Penguji</dt>
                <dd>{{ $score->evaluator->nama_instr ?? '—' }}</dd>
            </div>
            <div>
                <dt>Tanggal evaluasi</dt>
                <dd>{{ $score->evaluated_at?->translatedFormat('j F Y') ?? '—' }}</dd>
            </div>
        </dl>
    </div>
</section>

@if ($score->keterangan)
    <section class="bk-panel" style="margin-top:1.5rem">
        <div class="bk-panel__head">
            <div>
                <h2 class="bk-panel__title">Catatan penguji</h2>
            </div>
        </div>
        <div class="bk-panel__body">
            <p>{{ $score->keterangan }}</p>
        </div>
    </section>
@endif

<section class="bk-panel" style="margin-top:1.5rem">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Hapus hasil tes</h2>
            <p class="bk-panel__subtitle">Menghapus nilai akan mereset penempatan peserta ke keadaan sebelum tes.</p>
        </div>
        <form method="POST" action="{{ route('admin.score.destroy', $score) }}"
              onsubmit="return confirm('Hapus hasil tes ini? Penempatan peserta akan direset.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bk-btn bk-btn--sm bk-btn--danger">
                <i class="bi bi-trash3" aria-hidden="true"></i> Hapus hasil tes
            </button>
        </form>
    </div>
</section>
@endsection
