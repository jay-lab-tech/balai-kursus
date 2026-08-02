@extends('instruktur::layouts.master')

@section('title', 'Jadwal mengajar')
@section('page-context', 'Instruktur · Kalender')
@section('page-description', 'Semua pertemuan dari kelas yang ditugaskan kepada Anda, diurutkan dari yang paling awal.')

@section('content')

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Agenda kelas</h2>
            <p class="bk-panel__subtitle">{{ $jadwals->count() }} pertemuan terjadwal.</p>
        </div>
        <a href="{{ route('instruktur.dashboard') }}" class="bk-btn bk-btn--sm">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Ringkasan
        </a>
    </div>

    @if ($jadwals->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-calendar-x" aria-hidden="true"></i></span>
            <h3>Belum ada jadwal</h3>
            <p>Jadwal mengajar muncul di sini setelah admin menetapkannya untuk kelas Anda.</p>
        </div>
    @else
        <table class="bk-table is-padat">
            <thead>
                <tr>
                    <th class="nw">Tanggal</th>
                    <th class="nw">Waktu</th>
                    <th class="r nw">Pertemuan</th>
                    <th>Kelas</th>
                    <th>Tempat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jadwals as $j)
                    <tr>
                        <td class="nw">
                            <b>{{ optional($j->tgl_pertemuan)->translatedFormat('j M Y') ?? '—' }}</b><br>
                            <span class="bk-muted">{{ $j->hari->nama ?? '—' }}</span>
                        </td>
                        <td class="nw">
                            @if ($j->jam_mulai && $j->jam_selesai)
                                <span class="bk-code">{{ substr($j->jam_mulai, 0, 5) }}–{{ substr($j->jam_selesai, 0, 5) }}</span>
                            @else
                                <span class="bk-muted">—</span>
                            @endif
                        </td>
                        <td class="r nw"><span class="bk-num">{{ $j->pertemuan_ke ?? '—' }}</span></td>
                        <td>
                            <b>{{ $j->kursus->nama ?? '—' }}</b><br>
                            <span class="bk-muted">{{ $j->kursus->program->nama ?? '' }}</span>
                        </td>
                        <td>
                            {{ $j->lokasi->nama ?? '—' }}<br>
                            <span class="bk-muted">{{ $j->kela->nama ?? 'Ruang belum ditentukan' }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</section>
@endsection
