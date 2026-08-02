@extends('layouts.admin')

@section('title', 'Jadwal Kelas')
@section('page-context', 'Akademik · Kelas ' . $kursus->nama)
@section('page-title', 'Jadwal pertemuan')
@section('page-description', ($kursus->program->nama ?? 'Program') . ' · ' . ($kursus->level->nama ?? 'Level') . ' — rangkaian pertemuan yang dipakai untuk absensi dan risalah.')

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
            <h2 class="bk-panel__title">Daftar pertemuan</h2>
            <p class="bk-panel__subtitle">{{ $jadwals->total() }} pertemuan tersusun untuk kelas ini.</p>
        </div>
        <div class="bk-row">
            <a href="{{ route('admin.jadwal.create', $kursus->id) }}" class="bk-btn bk-btn--pri bk-btn--sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah jadwal
            </a>
            <a href="{{ route('admin.kursus.index') }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Daftar kelas
            </a>
        </div>
    </div>

    @if ($jadwals->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-calendar-x" aria-hidden="true"></i></span>
            <h3>Belum ada pertemuan</h3>
            <p>Susun pertemuan pertama agar absensi dan risalah punya tanggal untuk ditempeli.</p>
            <a href="{{ route('admin.jadwal.create', $kursus->id) }}" class="bk-btn bk-btn--pri">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah jadwal
            </a>
        </div>
    @else
        <table class="bk-table">
            <thead>
                <tr>
                    <th class="r">Ke</th>
                    <th class="nw">Tanggal</th>
                    <th class="nw">Waktu</th>
                    <th>Lokasi</th>
                    <th>Ruang</th>
                    <th>Hari</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jadwals as $jadwal)
                    <tr>
                        <td class="r">{{ $jadwal->pertemuan_ke ?? '—' }}</td>
                        <td class="nw">{{ $jadwal->tgl_pertemuan?->translatedFormat('j M Y') ?? '—' }}</td>
                        <td class="nw">{{ $jadwal->jam_mulai ? substr($jadwal->jam_mulai, 0, 5) : '—' }}–{{ $jadwal->jam_selesai ? substr($jadwal->jam_selesai, 0, 5) : '—' }}</td>
                        <td>{{ $jadwal->lokasi->nama ?? '—' }}</td>
                        <td>{{ $jadwal->kela->nama ?? '—' }}</td>
                        <td>{{ $jadwal->hari->nama ?? '—' }}</td>
                        <td class="r nw">
                            <a href="{{ route('admin.jadwal.edit', [$kursus->id, $jadwal->id]) }}" class="bk-iconbtn" title="Ubah pertemuan">
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                <span class="bk-sr">Ubah</span>
                            </a>
                            <form method="POST" action="{{ route('admin.jadwal.destroy', [$kursus->id, $jadwal->id]) }}" style="display:inline"
                                  onsubmit="return confirm('Hapus pertemuan ini? Absensi dan risalah yang menempel bisa ikut terdampak.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bk-iconbtn bk-iconbtn--danger" title="Hapus pertemuan">
                                    <i class="bi bi-trash3" aria-hidden="true"></i>
                                    <span class="bk-sr">Hapus</span>
                                </button>
                            </form>
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
