@extends('instruktur::layouts.master')

@section('title', 'Ringkasan mengajar')
@section('page-context', 'Instruktur · Ruang kerja')
@section('page-description', 'Kelas yang ditugaskan kepada Anda beserta jumlah peserta dan catatan pertemuannya.')

@section('content')

<div class="bk-stats bk-stats--3">
    <article class="bk-stat">
        <span class="bk-stat__icon"><i class="bi bi-journal-bookmark" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Kelas diampu</span>
        <p class="bk-stat__value">{{ $kursus->count() }}</p>
        <p class="bk-stat__hint">Kelas yang saat ini menjadi tanggung jawab Anda.</p>
    </article>
    <article class="bk-stat bk-stat--amber">
        <span class="bk-stat__icon"><i class="bi bi-people" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Peserta</span>
        <p class="bk-stat__value">{{ $jumlahPeserta }}</p>
        <p class="bk-stat__hint">Terdaftar di seluruh kelas Anda.</p>
    </article>
    <article class="bk-stat bk-stat--terra">
        <span class="bk-stat__icon"><i class="bi bi-journal-text" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Pertemuan</span>
        <p class="bk-stat__value">{{ $jumlahPertemuan }}</p>
        <p class="bk-stat__hint">Catatan pertemuan yang sudah tersedia.</p>
    </article>
</div>

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Kelas yang Anda ajarkan</h2>
            <p class="bk-panel__subtitle">Buka satu kelas untuk mengisi risalah, absensi, dan nilai peserta.</p>
        </div>
        <a href="{{ route('instruktur.jadwal.index') }}" class="bk-btn bk-btn--sm">
            <i class="bi bi-calendar3" aria-hidden="true"></i> Jadwal mengajar
        </a>
    </div>

    @if ($kursus->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-journal-x" aria-hidden="true"></i></span>
            <h3>Belum ada penugasan</h3>
            <p>Kelas akan muncul di sini setelah admin menugaskan Anda sebagai pengajarnya.</p>
        </div>
    @else
        <table class="bk-table">
            <thead>
                <tr>
                    <th class="r">No</th>
                    <th>Kelas</th>
                    <th>Level yang diampu</th>
                    <th class="r nw">Peserta</th>
                    <th class="r nw">Pertemuan</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kursus as $urutan => $k)
                    <tr>
                        <td class="r">{{ $urutan + 1 }}</td>
                        <td>
                            <b>{{ $k->nama }}</b><br>
                            <span class="bk-muted">{{ $k->program->nama ?? 'Tanpa program' }}</span>
                        </td>
                        <td>
                            @forelse ($levelPerKursus[$k->id] ?? [] as $level)
                                <span class="bk-chip">{{ $level }}</span>
                            @empty
                                <span class="bk-muted">Level belum ditentukan</span>
                            @endforelse
                        </td>
                        <td class="r nw"><span class="bk-num">{{ $k->jumlah_peserta }}</span></td>
                        <td class="r nw"><span class="bk-num">{{ $k->jumlah_pertemuan }}</span></td>
                        <td class="r nw">
                            <a href="{{ route('instruktur.kursus.show', $k) }}" class="bk-btn bk-btn--sm">
                                Buka kelas <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</section>
@endsection
