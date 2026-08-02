@extends('instruktur::layouts.master')

@section('title', $kursus->nama)
@section('page-context', 'Instruktur · Kursus')
@section('page-description', ($kursus->program->nama ?? 'Tanpa program').' · '.($kursus->level->nama ?? 'Level belum ditentukan'))

@section('content')

@include('instruktur::instruktur.partials.tab-kursus', ['kursus' => $kursus])

<div class="bk-stats bk-stats--3">
    <article class="bk-stat">
        <span class="bk-stat__icon"><i class="bi bi-people" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Peserta terdaftar</span>
        <p class="bk-stat__value">{{ $kursus->jumlah_peserta }}</p>
    </article>
    <article class="bk-stat bk-stat--amber">
        <span class="bk-stat__icon"><i class="bi bi-journal-text" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Pertemuan tercatat</span>
        <p class="bk-stat__value">{{ $risalah->count() }}</p>
    </article>
    <article class="bk-stat bk-stat--terra">
        <span class="bk-stat__icon"><i class="bi bi-clipboard-check" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Baris absensi</span>
        <p class="bk-stat__value">{{ $risalah->sum('jumlah_absensi') }}</p>
        <p class="bk-stat__hint">Kehadiran yang sudah Anda isi di seluruh pertemuan.</p>
    </article>
</div>

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Daftar pertemuan</h2>
            <p class="bk-panel__subtitle">Isi risalah untuk mencatat materi, dan absensi untuk mencatat kehadiran.</p>
        </div>
        <a href="{{ route('instruktur.risalah.index', $kursus) }}" class="bk-btn bk-btn--sm">
            Kelola risalah <i class="bi bi-arrow-right" aria-hidden="true"></i>
        </a>
    </div>

    @if ($risalah->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-calendar2-x" aria-hidden="true"></i></span>
            <h3>Belum ada pertemuan</h3>
            <p>Pertemuan dibuat oleh admin lewat penjadwalan. Setelah itu Anda bisa mengisi risalah dan absensinya.</p>
        </div>
    @else
        <table class="bk-table">
            <thead>
                <tr>
                    <th class="r nw">Ke-</th>
                    <th>Materi</th>
                    <th class="nw">Tanggal</th>
                    <th class="r nw">Absensi</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($risalah as $r)
                    <tr>
                        <td class="r nw"><span class="bk-num">{{ $r->pertemuan_ke }}</span></td>
                        <td>{{ $r->materi ?: 'Materi belum diisi' }}</td>
                        <td class="nw">
                            {{ $r->tgl_pertemuan ? \Carbon\Carbon::parse($r->tgl_pertemuan)->translatedFormat('j M Y') : '—' }}
                        </td>
                        <td class="r nw">
                            @if ($r->jumlah_absensi)
                                <span class="bk-tag">{{ $r->jumlah_absensi }} tercatat</span>
                            @else
                                <span class="bk-tag bk-tag--diam">Belum diisi</span>
                            @endif
                        </td>
                        <td class="r nw">
                            <a href="{{ route('instruktur.risalah.edit', $r) }}" class="bk-iconbtn" title="Ubah risalah pertemuan {{ $r->pertemuan_ke }}">
                                <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                                <span class="bk-sr">Risalah</span>
                            </a>
                            <a href="{{ route('instruktur.absensi.show', $r) }}" class="bk-iconbtn" title="Isi absensi pertemuan {{ $r->pertemuan_ke }}">
                                <i class="bi bi-clipboard-check" aria-hidden="true"></i>
                                <span class="bk-sr">Absensi</span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</section>

<section class="bk-panel">
    <div class="bk-panel__head">
        <h2 class="bk-panel__title">Konteks kelas</h2>
    </div>
    <div class="bk-panel__body">
        <dl class="bk-kv">
            <div>
                <dt>Program</dt>
                <dd>{{ $kursus->program->nama ?? '—' }}</dd>
            </div>
            <div>
                <dt>Level</dt>
                <dd>{{ $kursus->level->nama ?? '—' }}</dd>
            </div>
            <div>
                <dt>Kuota kelas</dt>
                <dd>{{ $kursus->kuota ? $kursus->jumlah_peserta.' dari '.$kursus->kuota.' kursi' : $kursus->jumlah_peserta.' peserta' }}</dd>
            </div>
        </dl>
    </div>
</section>
@endsection
