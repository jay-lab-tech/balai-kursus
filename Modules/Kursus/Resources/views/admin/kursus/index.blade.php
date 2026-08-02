@extends('layouts.admin')

@section('title', 'Kelas Program')
@section('page-context', 'Akademik · Kelas Program')
@section('page-title', 'Kelas program')
@section('page-description', 'Satu kelas adalah gabungan program, level, dan periode — tempat peserta ditempatkan setelah lulus tes.')

@section('content')

@if (session('success'))
    <div class="bk-note bk-note--baik">
        <i class="bi bi-check-circle-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@php
    $terisi = $kursus->sum('pendaftarans_count');
    $kapasitas = $kursus->sum('kuota');
    $penuh = $kursus->filter(fn ($item) => $item->pendaftarans_count >= $item->kuota)->count();
@endphp

<div class="bk-stats bk-stats--3">
    <article class="bk-stat">
        <span class="bk-stat__icon"><i class="bi bi-mortarboard" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Kelas tercatat</span>
        <p class="bk-stat__value">{{ $kursus->total() }}</p>
        <p class="bk-stat__hint">Seluruh kelas program, termasuk yang sudah tutup.</p>
    </article>
    <article class="bk-stat bk-stat--amber">
        <span class="bk-stat__icon"><i class="bi bi-people" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Keterisian halaman ini</span>
        <p class="bk-stat__value">{{ $terisi }}</p>
        <p class="bk-stat__hint">Peserta terdaftar dari {{ $kapasitas }} kursi yang tersedia.</p>
    </article>
    <article class="bk-stat bk-stat--terra">
        <span class="bk-stat__icon"><i class="bi bi-exclamation-diamond" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Kelas penuh</span>
        <p class="bk-stat__value">{{ $penuh }}</p>
        <p class="bk-stat__hint">Sudah mencapai kuota; pendaftar baru perlu kelas lain.</p>
    </article>
</div>

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Daftar kelas</h2>
            <p class="bk-panel__subtitle">{{ $kursus->total() }} kelas dikelola. Keterisian dihitung dari pendaftaran yang belum dibatalkan.</p>
        </div>
        <div class="bk-row">
            <a href="{{ route('admin.jadwal.all') }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-calendar3" aria-hidden="true"></i> Semua jadwal
            </a>
            <a href="{{ route('admin.kursus.create') }}" class="bk-btn bk-btn--pri bk-btn--sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah kelas
            </a>
        </div>
    </div>

    @if ($kursus->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-mortarboard" aria-hidden="true"></i></span>
            <h3>Belum ada kelas program</h3>
            <p>Tambahkan kelas agar peserta yang lulus tes punya tempat untuk ditempatkan.</p>
            <a href="{{ route('admin.kursus.create') }}" class="bk-btn bk-btn--pri">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah kelas
            </a>
        </div>
    @else
        <table class="bk-table">
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>Program / level</th>
                    <th class="nw">Periode</th>
                    <th class="r nw">Terisi</th>
                    <th>Status</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kursus as $kelas)
                    <tr>
                        <td>
                            <b>{{ $kelas->nama }}</b><br>
                            <span class="bk-muted">Rp {{ number_format($kelas->harga, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            {{ $kelas->program->nama ?? '—' }}<br>
                            <span class="bk-muted">{{ $kelas->level->nama ?? 'Tanpa level' }}</span>
                        </td>
                        <td class="nw">{{ $kelas->periode ?: '—' }}</td>
                        <td class="r nw">
                            <b>{{ $kelas->pendaftarans_count }}</b><span class="bk-muted">/{{ $kelas->kuota }}</span>
                        </td>
                        <td>
                            <span class="bk-tag {{ ['buka' => 'bk-tag--info', 'berjalan' => 'bk-tag--jalan'][$kelas->status] ?? 'bk-tag--diam' }}">
                                {{ ucfirst($kelas->status) }}
                            </span>
                        </td>
                        <td class="r nw">
                            <a href="{{ route('admin.kursus.peserta', $kelas) }}" class="bk-iconbtn" title="Peserta {{ $kelas->nama }}">
                                <i class="bi bi-people" aria-hidden="true"></i>
                                <span class="bk-sr">Peserta</span>
                            </a>
                            <a href="{{ route('admin.jadwal.index', $kelas) }}" class="bk-iconbtn" title="Jadwal {{ $kelas->nama }}">
                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                <span class="bk-sr">Jadwal</span>
                            </a>
                            <a href="{{ route('admin.kursus.edit', $kelas) }}" class="bk-iconbtn" title="Ubah {{ $kelas->nama }}">
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                <span class="bk-sr">Ubah</span>
                            </a>
                            <form method="POST" action="{{ route('admin.kursus.destroy', $kelas) }}" style="display:inline"
                                  onsubmit="return confirm('Hapus kelas {{ $kelas->nama }}? Pendaftaran, jadwal, dan risalahnya ikut terdampak.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bk-iconbtn bk-iconbtn--danger" title="Hapus {{ $kelas->nama }}">
                                    <i class="bi bi-trash3" aria-hidden="true"></i>
                                    <span class="bk-sr">Hapus</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($kursus->hasPages())
            <div class="bk-panel__foot">{{ $kursus->links() }}</div>
        @endif
    @endif
</section>
@endsection
