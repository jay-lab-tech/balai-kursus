@extends('layouts.admin')

@section('title', 'Lokasi')
@section('page-context', 'Akademik · Lokasi & Ruang')
@section('page-title', 'Lokasi')
@section('page-description', 'Gedung atau tempat penyelenggaraan kursus yang bisa dipilih saat menyusun jadwal pertemuan.')

@section('content')

@if (session('success'))
    <div class="bk-note bk-note--baik">
        <i class="bi bi-check-circle-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="bk-stats bk-stats--3">
    <article class="bk-stat">
        <span class="bk-stat__icon"><i class="bi bi-geo-alt" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Lokasi tercatat</span>
        <p class="bk-stat__value">{{ $lokasis->total() }}</p>
        <p class="bk-stat__hint">Siap dipakai saat menyusun jadwal pertemuan.</p>
    </article>
    <article class="bk-stat bk-stat--amber">
        <span class="bk-stat__icon"><i class="bi bi-buildings" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Kota di halaman ini</span>
        <p class="bk-stat__value">{{ $lokasis->pluck('kota')->filter()->unique()->count() }}</p>
        <p class="bk-stat__hint">Sebaran kota dari lokasi yang sedang ditampilkan.</p>
    </article>
    <article class="bk-stat bk-stat--terra">
        <span class="bk-stat__icon"><i class="bi bi-map" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Provinsi di halaman ini</span>
        <p class="bk-stat__value">{{ $lokasis->pluck('provinsi')->filter()->unique()->count() }}</p>
        <p class="bk-stat__hint">Berguna untuk melihat jangkauan penyelenggaraan.</p>
    </article>
</div>

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Daftar lokasi</h2>
            <p class="bk-panel__subtitle">Alamat dan kontak setiap tempat penyelenggaraan.</p>
        </div>
        <a href="{{ route('admin.lokasi.create') }}" class="bk-btn bk-btn--pri bk-btn--sm">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah lokasi
        </a>
    </div>

    @if ($lokasis->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-geo-alt" aria-hidden="true"></i></span>
            <h3>Belum ada lokasi</h3>
            <p>Tambahkan lokasi pertama agar jadwal pertemuan punya tempat untuk ditautkan.</p>
            <a href="{{ route('admin.lokasi.create') }}" class="bk-btn bk-btn--pri">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah lokasi
            </a>
        </div>
    @else
        <table class="bk-table">
            <thead>
                <tr>
                    <th class="r">No</th>
                    <th>Lokasi</th>
                    <th>Alamat</th>
                    <th>Kota</th>
                    <th class="nw">Telepon</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lokasis as $key => $lokasi)
                    <tr>
                        <td class="r">{{ $lokasis->firstItem() + $key }}</td>
                        <td>
                            <b>{{ $lokasi->nama }}</b><br>
                            <span class="bk-muted">{{ $lokasi->provinsi ?: 'Provinsi belum diisi' }}</span>
                        </td>
                        <td>{{ $lokasi->alamat }}</td>
                        <td><span class="bk-tag bk-tag--info">{{ $lokasi->kota }}</span></td>
                        <td class="nw">{{ $lokasi->no_telp }}</td>
                        <td class="r nw">
                            <a href="{{ route('admin.lokasi.show', $lokasi->id) }}" class="bk-iconbtn" title="Detail {{ $lokasi->nama }}">
                                <i class="bi bi-eye" aria-hidden="true"></i>
                                <span class="bk-sr">Detail</span>
                            </a>
                            <a href="{{ route('admin.lokasi.edit', $lokasi->id) }}" class="bk-iconbtn" title="Ubah {{ $lokasi->nama }}">
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                <span class="bk-sr">Ubah</span>
                            </a>
                            <form method="POST" action="{{ route('admin.lokasi.destroy', $lokasi->id) }}" style="display:inline"
                                  onsubmit="return confirm('Hapus lokasi {{ $lokasi->nama }}? Ruang kelas dan jadwal yang memakainya bisa ikut terdampak.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bk-iconbtn bk-iconbtn--danger" title="Hapus {{ $lokasi->nama }}">
                                    <i class="bi bi-trash3" aria-hidden="true"></i>
                                    <span class="bk-sr">Hapus</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($lokasis->hasPages())
            <div class="bk-panel__foot">{{ $lokasis->links() }}</div>
        @endif
    @endif
</section>
@endsection
