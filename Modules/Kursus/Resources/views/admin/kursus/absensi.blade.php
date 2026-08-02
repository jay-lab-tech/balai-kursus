@extends('layouts.admin')

@section('title', 'Absensi Kelas')
@section('page-context', 'Akademik · Kelas ' . $kursus->nama)
@section('page-title', 'Absensi kelas')
@section('page-description', ($kursus->program->nama ?? 'Program') . ' · ' . ($kursus->level->nama ?? 'Level') . ' — kehadiran peserta yang tercatat pada tiap pertemuan.')

@section('content')

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Daftar kehadiran</h2>
            <p class="bk-panel__subtitle">{{ $absensis->total() }} baris kehadiran tercatat, terbaru di atas.</p>
        </div>
        <div class="bk-row">
            <a href="{{ route('admin.kursus.risalah', $kursus) }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-journal-text" aria-hidden="true"></i> Risalah kelas
            </a>
            <a href="{{ route('admin.kursus.index') }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Daftar kelas
            </a>
        </div>
    </div>

    @if ($absensis->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-clipboard-check" aria-hidden="true"></i></span>
            <h3>Belum ada data kehadiran</h3>
            <p>Kehadiran tercatat setelah instruktur mengisi absensi pada risalah pertemuan.</p>
        </div>
    @else
        <table class="bk-table">
            <thead>
                <tr>
                    <th>Peserta</th>
                    <th class="nw">Pertemuan</th>
                    <th>Status</th>
                    <th class="nw">Jam datang</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($absensis as $absensi)
                    <tr>
                        <td><b>{{ $absensi->pendaftaran->peserta->user->name ?? '—' }}</b></td>
                        <td class="nw">
                            Pertemuan {{ $absensi->risalah->pertemuan_ke ?? '—' }}<br>
                            <span class="bk-muted">{{ $absensi->risalah->tgl_pertemuan?->translatedFormat('j M Y') ?? '—' }}</span>
                        </td>
                        <td>@include('kursus::admin.kursus.partials.absensi-status')</td>
                        <td class="nw">{{ $absensi->jam_datang ?: '—' }}</td>
                        <td>{{ $absensi->catatan ?: '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($absensis->hasPages())
            <div class="bk-panel__foot">{{ $absensis->links() }}</div>
        @endif
    @endif
</section>
@endsection
