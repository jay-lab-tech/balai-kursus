@extends('layouts.admin')

@section('title', 'Risalah Kelas')
@section('page-context', 'Akademik · Kelas ' . $kursus->nama)
@section('page-title', 'Risalah kelas')
@section('page-description', ($kursus->program->nama ?? 'Program') . ' · ' . ($kursus->level->nama ?? 'Level') . ' — catatan materi tiap pertemuan yang diisi instruktur.')

@section('content')

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Daftar risalah</h2>
            <p class="bk-panel__subtitle">{{ $risalahs->total() }} pertemuan terdokumentasi untuk kelas ini.</p>
        </div>
        <div class="bk-row">
            <a href="{{ route('admin.kursus.absensi', $kursus) }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-clipboard-check" aria-hidden="true"></i> Absensi kelas
            </a>
            <a href="{{ route('admin.kursus.index') }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Daftar kelas
            </a>
        </div>
    </div>

    @if ($risalahs->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-journal-text" aria-hidden="true"></i></span>
            <h3>Belum ada risalah</h3>
            <p>Risalah muncul di sini setelah instruktur mengisi catatan pertemuan kelas ini.</p>
        </div>
    @else
        <table class="bk-table">
            <thead>
                <tr>
                    <th class="r nw">Pertemuan</th>
                    <th class="nw">Tanggal</th>
                    <th>Instruktur</th>
                    <th>Materi</th>
                    <th class="r nw">Absensi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($risalahs as $risalah)
                    <tr>
                        <td class="r nw"><b>{{ $risalah->pertemuan_ke ?? '—' }}</b></td>
                        <td class="nw">{{ $risalah->tgl_pertemuan?->translatedFormat('j M Y') ?? '—' }}</td>
                        <td>{{ $risalah->instruktur->nama_instr ?? '—' }}</td>
                        <td>{{ $risalah->materi ?: '—' }}</td>
                        <td class="r nw">{{ $risalah->absensis_count }} peserta</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($risalahs->hasPages())
            <div class="bk-panel__foot">{{ $risalahs->links() }}</div>
        @endif
    @endif
</section>
@endsection
