@extends('layouts.admin')

@section('title', 'Semua Risalah')
@section('page-context', 'Peserta · Risalah & Absensi')
@section('page-title', 'Semua risalah')
@section('page-description', 'Catatan materi tiap pertemuan dari seluruh kelas, dipakai untuk memeriksa kelengkapan dokumentasi instruktur.')

@section('content')

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Daftar risalah</h2>
            <p class="bk-panel__subtitle">{{ $risalahs->total() }} risalah tercatat, pertemuan terbaru di atas.</p>
        </div>
        <div class="bk-row">
            <a href="{{ route('admin.absensi.all') }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-clipboard-check" aria-hidden="true"></i> Semua absensi
            </a>
        </div>
    </div>

    @if ($risalahs->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-journal-text" aria-hidden="true"></i></span>
            <h3>Belum ada risalah</h3>
            <p>Risalah muncul setelah instruktur mulai mengisi catatan pertemuan kelasnya.</p>
        </div>
    @else
        <table class="bk-table">
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th class="r nw">Pertemuan</th>
                    <th class="nw">Tanggal</th>
                    <th>Instruktur</th>
                    <th>Materi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($risalahs as $risalah)
                    <tr>
                        <td>
                            <b>{{ $risalah->kursus->nama ?? '—' }}</b><br>
                            <span class="bk-muted">{{ $risalah->kursus->program->nama ?? 'Tanpa program' }}</span>
                        </td>
                        <td class="r nw">{{ $risalah->pertemuan_ke ?? '—' }}</td>
                        <td class="nw">{{ $risalah->tgl_pertemuan?->translatedFormat('j M Y') ?? '—' }}</td>
                        <td>{{ $risalah->instruktur->nama_instr ?? '—' }}</td>
                        <td>{{ $risalah->materi ?: '—' }}</td>
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
