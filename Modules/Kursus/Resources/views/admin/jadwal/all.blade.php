@extends('layouts.admin')

@section('title', 'Semua Jadwal')
@section('page-context', 'Akademik · Jadwal')
@section('page-title', 'Semua jadwal')
@section('page-description', 'Seluruh pertemuan dari semua kelas dalam satu daftar, tanpa perlu membuka kelas satu per satu.')

@section('content')

@if (session('success'))
    <div class="bk-note bk-note--baik">
        <i class="bi bi-check-circle-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Agenda lintas kelas</h2>
            <p class="bk-panel__subtitle">{{ $jadwals->total() }} pertemuan tercatat di seluruh kelas.</p>
        </div>
        <a href="{{ route('admin.kursus.index') }}" class="bk-btn bk-btn--sm">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Daftar kelas
        </a>
    </div>

    @if ($jadwals->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-calendar-x" aria-hidden="true"></i></span>
            <h3>Belum ada jadwal tersimpan</h3>
            <p>Susun pertemuan dari halaman kelas agar agenda mulai terisi di sini.</p>
            <a href="{{ route('admin.kursus.index') }}" class="bk-btn bk-btn--pri">
                <i class="bi bi-mortarboard" aria-hidden="true"></i> Buka daftar kelas
            </a>
        </div>
    @else
        <table class="bk-table">
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th class="r">Ke</th>
                    <th class="nw">Tanggal</th>
                    <th class="nw">Waktu</th>
                    <th>Lokasi</th>
                    <th>Ruang</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jadwals as $jadwal)
                    <tr>
                        <td>
                            <b>{{ $jadwal->kursus->nama ?? '—' }}</b><br>
                            <span class="bk-muted">{{ $jadwal->kursus->program->nama ?? 'Tanpa program' }}</span>
                        </td>
                        <td class="r">{{ $jadwal->pertemuan_ke ?? '—' }}</td>
                        <td class="nw">{{ $jadwal->tgl_pertemuan?->translatedFormat('j M Y') ?? '—' }}</td>
                        <td class="nw">{{ $jadwal->jam_mulai ? substr($jadwal->jam_mulai, 0, 5) : '—' }}–{{ $jadwal->jam_selesai ? substr($jadwal->jam_selesai, 0, 5) : '—' }}</td>
                        <td>{{ $jadwal->lokasi->nama ?? '—' }}</td>
                        <td>{{ $jadwal->kela->nama ?? '—' }}</td>
                        <td class="r nw">
                            <a href="{{ route('admin.jadwal.edit', [$jadwal->kursus_id, $jadwal->id]) }}" class="bk-iconbtn" title="Ubah pertemuan">
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                <span class="bk-sr">Ubah</span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($jadwals->hasPages())
            <div class="bk-panel__foot">{{ $jadwals->links() }}</div>
        @endif
    @endif
</section>
@endsection
