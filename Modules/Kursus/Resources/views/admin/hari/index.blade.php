@extends('layouts.admin')

@section('title', 'Master Hari')

@section('page-title', 'Hari')

@section('content')
<div class="space-y-6">
    <section class="admin-panel admin-panel--hero">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-3xl space-y-4">
                <span class="admin-eyebrow"><i class="bi bi-calendar-week"></i>Master Penjadwalan</span>
                <div class="space-y-3">
                    <h2 class="text-3xl font-semibold tracking-tight text-white">Atur urutan hari untuk kebutuhan jadwal.</h2>
                    <p class="max-w-2xl text-sm leading-6 text-slate-300">Data hari dipakai pada modul penjadwalan agar urutan dan penamaan sesi pertemuan konsisten di seluruh sistem.</p>
                </div>
            </div>
            <a href="{{ route('admin.hari.create') }}" class="admin-btn admin-btn-primary"><i class="bi bi-plus-circle"></i>Tambah Hari</a>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-3">
        <article class="admin-stat-card"><span class="admin-stat-card__label">Total Hari</span><div class="admin-stat-card__value">{{ $haris->total() }}</div><p class="admin-stat-card__hint">Jumlah entri hari yang tersedia untuk penjadwalan.</p></article>
        <article class="admin-stat-card"><span class="admin-stat-card__label">Urutan Terkecil</span><div class="admin-stat-card__value">{{ $haris->min('urutan') ?? '-' }}</div><p class="admin-stat-card__hint">Posisi urutan paling awal pada halaman ini.</p></article>
        <article class="admin-stat-card"><span class="admin-stat-card__label">Urutan Terbesar</span><div class="admin-stat-card__value">{{ $haris->max('urutan') ?? '-' }}</div><p class="admin-stat-card__hint">Posisi urutan paling akhir pada halaman ini.</p></article>
    </section>

    @if(session('success'))
        <div class="admin-alert admin-alert-success"><i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span></div>
    @endif

    <section class="admin-panel overflow-hidden">
        <div class="admin-panel__header"><div><h3 class="admin-panel__title">Daftar Hari</h3><p class="admin-panel__subtitle">Gunakan urutan yang konsisten agar pilihan hari pada jadwal lebih mudah dipahami admin.</p></div></div>
        @if($haris->isEmpty())
            <div class="admin-empty-state"><div class="admin-empty-state__icon"><i class="bi bi-calendar-week"></i></div><h3>Belum ada master hari</h3><p>Tambahkan data hari agar modul penjadwalan memiliki referensi urutan yang lengkap.</p><a href="{{ route('admin.hari.create') }}" class="admin-btn admin-btn-primary"><i class="bi bi-plus-circle"></i>Tambah Hari</a></div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead><tr><th>Urutan</th><th>Nama Hari</th><th class="text-right">Aksi</th></tr></thead>
                    <tbody>
                        @foreach($haris as $hari)
                            <tr>
                                <td><span class="admin-badge admin-badge-muted">{{ $hari->urutan }}</span></td>
                                <td><div class="font-semibold text-white">{{ $hari->nama }}</div></td>
                                <td><div class="flex justify-end gap-2"><a href="{{ route('admin.hari.edit', $hari->id) }}" class="admin-btn admin-btn-ghost admin-btn-sm"><i class="bi bi-pencil-square"></i>Edit</a><form action="{{ route('admin.hari.destroy', $hari->id) }}" method="POST" onsubmit="return confirm('Yakin?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn-danger admin-btn-sm"><i class="bi bi-trash3"></i>Hapus</button></form></div></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($haris->hasPages())<div class="border-t border-white/10 px-4 py-5 sm:px-6">{{ $haris->links() }}</div>@endif
        @endif
    </section>
</div>
@endsection
