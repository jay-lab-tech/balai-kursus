@extends('layouts.admin')

@section('title', 'Master Lokasi')

@section('page-title', 'Lokasi')

@section('content')
<div class="space-y-6">
    <section class="admin-panel admin-panel--hero">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-3xl space-y-4">
                <span class="admin-eyebrow">
                    <i class="bi bi-geo-alt"></i>
                    Master Operasional
                </span>
                <div class="space-y-3">
                    <h2 class="text-3xl font-semibold tracking-tight text-white">Kelola lokasi pelaksanaan kursus.</h2>
                    <p class="max-w-2xl text-sm leading-6 text-slate-300">Simpan alamat, kota, nomor telepon, dan catatan operasional setiap lokasi agar penjadwalan lebih akurat.</p>
                </div>
            </div>

            <a href="{{ route('admin.lokasi.create') }}" class="admin-btn admin-btn-primary"><i class="bi bi-plus-circle"></i>Tambah Lokasi</a>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-3">
        <article class="admin-stat-card"><span class="admin-stat-card__label">Total Lokasi</span><div class="admin-stat-card__value">{{ $lokasis->total() }}</div><p class="admin-stat-card__hint">Lokasi aktif yang siap dipakai untuk kelas dan jadwal.</p></article>
        <article class="admin-stat-card"><span class="admin-stat-card__label">Kota Tercatat</span><div class="admin-stat-card__value">{{ $lokasis->pluck('kota')->filter()->unique()->count() }}</div><p class="admin-stat-card__hint">Persebaran kota dari lokasi yang tampil di halaman ini.</p></article>
        <article class="admin-stat-card"><span class="admin-stat-card__label">Halaman Aktif</span><div class="admin-stat-card__value">{{ $lokasis->currentPage() }}</div><p class="admin-stat-card__hint">Gunakan pagination untuk meninjau semua lokasi operasional.</p></article>
    </section>

    @if(session('success'))
        <div class="admin-alert admin-alert-success"><i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span></div>
    @endif

    <section class="admin-panel overflow-hidden">
        <div class="admin-panel__header"><div><h3 class="admin-panel__title">Daftar Lokasi</h3><p class="admin-panel__subtitle">Pantau ringkasan alamat, kota, dan kontak setiap lokasi dengan cepat.</p></div></div>

        @if($lokasis->isEmpty())
            <div class="admin-empty-state">
                <div class="admin-empty-state__icon"><i class="bi bi-geo-alt"></i></div>
                <h3>Belum ada lokasi</h3>
                <p>Tambahkan lokasi pertama untuk mendukung penjadwalan dan pengelolaan ruang belajar.</p>
                <a href="{{ route('admin.lokasi.create') }}" class="admin-btn admin-btn-primary"><i class="bi bi-plus-circle"></i>Tambah Lokasi</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead><tr><th>No</th><th>Lokasi</th><th>Alamat</th><th>Kota</th><th>Kontak</th><th class="text-right">Aksi</th></tr></thead>
                    <tbody>
                        @foreach($lokasis as $key => $lokasi)
                            <tr>
                                <td>{{ $lokasis->firstItem() + $key }}</td>
                                <td><div class="space-y-1"><div class="font-semibold text-white">{{ $lokasi->nama }}</div><div class="text-xs text-slate-400">{{ $lokasi->provinsi ?: 'Provinsi belum diisi' }}</div></div></td>
                                <td><div class="max-w-xs text-sm text-slate-300">{{ $lokasi->alamat }}</div></td>
                                <td><span class="admin-badge admin-badge-warning">{{ $lokasi->kota }}</span></td>
                                <td><div class="text-sm text-slate-200">{{ $lokasi->no_telp }}</div></td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.lokasi.show', $lokasi->id) }}" class="admin-btn admin-btn-ghost admin-btn-sm"><i class="bi bi-eye"></i>Detail</a>
                                        <a href="{{ route('admin.lokasi.edit', $lokasi->id) }}" class="admin-btn admin-btn-ghost admin-btn-sm"><i class="bi bi-pencil-square"></i>Edit</a>
                                        <form action="{{ route('admin.lokasi.destroy', $lokasi->id) }}" method="POST" onsubmit="return confirm('Yakin?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn-danger admin-btn-sm"><i class="bi bi-trash3"></i>Hapus</button></form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($lokasis->hasPages())
                <div class="border-t border-white/10 px-4 py-5 sm:px-6">{{ $lokasis->links() }}</div>
            @endif
        @endif
    </section>
</div>
@endsection
