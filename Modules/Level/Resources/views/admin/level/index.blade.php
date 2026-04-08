@extends('layouts.admin')

@section('title', 'Manajemen Level')

@section('page-title', 'Level')

@section('content')
@php
    $totalLevel = $level->count();
    $minNilai = $level->min('nilai_min');
    $maxNilai = $level->max('nilai_max');
@endphp

<div class="space-y-6">
    <section class="admin-panel admin-panel--hero">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-3xl space-y-4">
                <span class="admin-eyebrow">
                    <i class="bi bi-layers"></i>
                    Master Akademik
                </span>
                <div class="space-y-3">
                    <h2 class="text-3xl font-semibold tracking-tight text-white">Atur level dan rentang nilai penempatan.</h2>
                    <p class="max-w-2xl text-sm leading-6 text-slate-300">
                        Data level dipakai untuk pemetaan hasil tes penempatan dan struktur jenjang pembelajaran di seluruh program.
                    </p>
                </div>
            </div>

            <a href="{{ route('admin.level.create') }}" class="admin-btn admin-btn-primary">
                <i class="bi bi-plus-circle"></i>
                Tambah Level
            </a>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-3">
        <article class="admin-stat-card">
            <span class="admin-stat-card__label">Total Level</span>
            <div class="admin-stat-card__value">{{ $totalLevel }}</div>
            <p class="admin-stat-card__hint">Jumlah jenjang yang aktif di sistem.</p>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-card__label">Nilai Minimum</span>
            <div class="admin-stat-card__value">{{ $minNilai ?? '-' }}</div>
            <p class="admin-stat-card__hint">Batas bawah terkecil dari seluruh rentang penempatan.</p>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-card__label">Nilai Maksimum</span>
            <div class="admin-stat-card__value">{{ $maxNilai ?? '-' }}</div>
            <p class="admin-stat-card__hint">Batas atas terbesar dari seluruh rentang penempatan.</p>
        </article>
    </section>

    @if(session('success'))
        <div class="admin-alert admin-alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <section class="admin-panel overflow-hidden">
        <div class="admin-panel__header">
            <div>
                <h3 class="admin-panel__title">Daftar Level</h3>
                <p class="admin-panel__subtitle">Setiap level menyimpan urutan dan rentang nilai yang dipakai untuk hasil tes penempatan.</p>
            </div>
        </div>

        @if($level->isEmpty())
            <div class="admin-empty-state">
                <div class="admin-empty-state__icon">
                    <i class="bi bi-layers"></i>
                </div>
                <h3>Belum ada level</h3>
                <p>Tambahkan level agar hasil tes penempatan dapat dipetakan ke jenjang yang tepat.</p>
                <a href="{{ route('admin.level.create') }}" class="admin-btn admin-btn-primary">
                    <i class="bi bi-plus-circle"></i>
                    Tambah Level
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Urutan</th>
                            <th>Nama Level</th>
                            <th>Rentang Nilai</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($level as $item)
                            <tr>
                                <td>
                                    <span class="admin-badge admin-badge-muted">{{ $item->urutan }}</span>
                                </td>
                                <td>
                                    <div class="space-y-1">
                                        <div class="font-semibold text-white">{{ $item->nama }}</div>
                                        <div class="text-xs text-slate-400">{{ $item->deskripsi ?: 'Belum ada deskripsi level.' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="admin-badge admin-badge-warning">{{ $item->rentang_nilai }}</span>
                                </td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.level.edit', $item) }}" class="admin-btn admin-btn-ghost admin-btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.level.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus level ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm">
                                                <i class="bi bi-trash3"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
@endsection
