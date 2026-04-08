@extends('layouts.admin')

@section('title', 'Master Kelas')

@section('page-title', 'Kelas')

@section('content')
<div class="space-y-6">
    <section class="admin-panel admin-panel--hero">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-3xl space-y-4">
                <span class="admin-eyebrow"><i class="bi bi-door-open"></i>Master Operasional</span>
                <div class="space-y-3">
                    <h2 class="text-3xl font-semibold tracking-tight text-white">Kelola ruang kelas dan kapasitasnya.</h2>
                    <p class="max-w-2xl text-sm leading-6 text-slate-300">Data kelas membantu admin menempatkan peserta ke ruang yang sesuai beserta fasilitas yang tersedia.</p>
                </div>
            </div>
            <a href="{{ route('admin.kelas.create') }}" class="admin-btn admin-btn-primary"><i class="bi bi-plus-circle"></i>Tambah Kelas</a>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-3">
        <article class="admin-stat-card"><span class="admin-stat-card__label">Total Kelas</span><div class="admin-stat-card__value">{{ $kelas->total() }}</div><p class="admin-stat-card__hint">Ruang kelas yang tersedia di sistem.</p></article>
        <article class="admin-stat-card"><span class="admin-stat-card__label">Kapasitas Halaman Ini</span><div class="admin-stat-card__value">{{ $kelas->sum('kapasitas') }}</div><p class="admin-stat-card__hint">Akumulasi kapasitas dari data yang sedang ditampilkan.</p></article>
        <article class="admin-stat-card"><span class="admin-stat-card__label">Halaman Aktif</span><div class="admin-stat-card__value">{{ $kelas->currentPage() }}</div><p class="admin-stat-card__hint">Gunakan navigasi halaman untuk meninjau seluruh master kelas.</p></article>
    </section>

    @if(session('success'))
        <div class="admin-alert admin-alert-success"><i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span></div>
    @endif

    <section class="admin-panel overflow-hidden">
        <div class="admin-panel__header"><div><h3 class="admin-panel__title">Daftar Kelas</h3><p class="admin-panel__subtitle">Pantau kapasitas, fasilitas, dan detail ruang kelas dalam satu tampilan yang lebih rapi.</p></div></div>
        @if($kelas->isEmpty())
            <div class="admin-empty-state"><div class="admin-empty-state__icon"><i class="bi bi-door-open"></i></div><h3>Belum ada kelas</h3><p>Tambahkan kelas pertama agar penempatan ruang belajar bisa diatur dengan lebih presisi.</p><a href="{{ route('admin.kelas.create') }}" class="admin-btn admin-btn-primary"><i class="bi bi-plus-circle"></i>Tambah Kelas</a></div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead><tr><th>No</th><th>Nama Kelas</th><th>Kapasitas</th><th>Fasilitas</th><th class="text-right">Aksi</th></tr></thead>
                    <tbody>
                        @foreach($kelas as $key => $k)
                            <tr>
                                <td>{{ $kelas->firstItem() + $key }}</td>
                                <td><div class="space-y-1"><div class="font-semibold text-white">{{ $k->nama }}</div><div class="text-xs text-slate-400">{{ $k->keterangan ?: 'Belum ada keterangan tambahan.' }}</div></div></td>
                                <td><span class="admin-badge admin-badge-info">{{ $k->kapasitas }} orang</span></td>
                                <td><div class="max-w-xs text-sm text-slate-300">{{ $k->fasilitas }}</div></td>
                                <td><div class="flex justify-end gap-2"><a href="{{ route('admin.kelas.show', $k->id) }}" class="admin-btn admin-btn-ghost admin-btn-sm"><i class="bi bi-eye"></i>Detail</a><a href="{{ route('admin.kelas.edit', $k->id) }}" class="admin-btn admin-btn-ghost admin-btn-sm"><i class="bi bi-pencil-square"></i>Edit</a><form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Yakin?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn-danger admin-btn-sm"><i class="bi bi-trash3"></i>Hapus</button></form></div></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($kelas->hasPages())<div class="border-t border-white/10 px-4 py-5 sm:px-6">{{ $kelas->links() }}</div>@endif
        @endif
    </section>
</div>
@endsection
