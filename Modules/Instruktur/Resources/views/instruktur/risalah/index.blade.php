@extends('instruktur::layouts.master')

@section('title', 'Pertemuan & risalah')
@section('page-context', 'Instruktur · '.$kursus->nama)
@section('page-description', 'Catatan materi dan kehadiran untuk setiap pertemuan kelas ini.')

@section('content')

@include('instruktur::instruktur.partials.tab-kursus', ['kursus' => $kursus])

@if (session('success'))
    <div class="bk-note bk-note--baik">
        <i class="bi bi-check-circle-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Risalah pertemuan</h2>
            <p class="bk-panel__subtitle">{{ $risalahs->count() }} pertemuan tercatat pada kelas ini.</p>
        </div>
    </div>

    @if ($risalahs->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-journal-x" aria-hidden="true"></i></span>
            <h3>Belum ada risalah</h3>
            <p>Pertemuan dibuat admin lewat penjadwalan. Begitu ada, Anda bisa mengisi materi dan catatannya di sini.</p>
        </div>
    @else
        <table class="bk-table">
            <thead>
                <tr>
                    <th class="r nw">Ke-</th>
                    <th>Materi &amp; catatan</th>
                    <th class="nw">Tanggal</th>
                    <th class="r nw">Absensi</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($risalahs as $r)
                    <tr>
                        <td class="r nw"><span class="bk-num">{{ $r->pertemuan_ke }}</span></td>
                        <td>
                            <b>{{ $r->materi ?: 'Materi belum diisi' }}</b>
                            @if ($r->catatan)
                                <br><span class="bk-muted">{{ Str::limit($r->catatan, 150) }}</span>
                            @endif
                        </td>
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
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                <span class="bk-sr">Ubah risalah</span>
                            </a>
                            <a href="{{ route('instruktur.absensi.show', $r) }}" class="bk-iconbtn" title="Isi absensi pertemuan {{ $r->pertemuan_ke }}">
                                <i class="bi bi-clipboard-check" aria-hidden="true"></i>
                                <span class="bk-sr">Absensi</span>
                            </a>
                            @if ($r->dokumen)
                                <a href="{{ route('instruktur.risalah.download', $r) }}" class="bk-iconbtn" title="Unduh dokumen pertemuan {{ $r->pertemuan_ke }}">
                                    <i class="bi bi-download" aria-hidden="true"></i>
                                    <span class="bk-sr">Unduh dokumen</span>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</section>
@endsection
