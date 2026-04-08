@extends('layouts.admin')

@section('title', 'Manajemen Program')

@section('page-title', 'Program')

@section('content')
@php
    $totalProgram = $program->count();
    $totalLevel = $program->sum(fn ($item) => $item->kursuses->pluck('level_id')->filter()->unique()->count());
    $totalKursus = $program->sum(fn ($item) => $item->kursuses->count());
@endphp

<div class="space-y-6">
    <section class="admin-panel admin-panel--hero">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-3xl space-y-4">
                <span class="admin-eyebrow">
                    <i class="bi bi-kanban"></i>
                    Master Akademik
                </span>
                <div class="space-y-3">
                    <h2 class="text-3xl font-semibold tracking-tight text-white">Kelola struktur program kursus dengan lebih rapi.</h2>
                    <p class="max-w-2xl text-sm leading-6 text-slate-300">
                        Program menjadi fondasi pengelompokan level dan kursus. Halaman ini merangkum jumlah level yang aktif,
                        warna identitas program, dan total kursus yang terhubung.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.program.create') }}" class="admin-btn admin-btn-primary">
                    <i class="bi bi-plus-circle"></i>
                    Tambah Program
                </a>
            </div>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-3">
        <article class="admin-stat-card">
            <span class="admin-stat-card__label">Total Program</span>
            <div class="admin-stat-card__value">{{ $totalProgram }}</div>
            <p class="admin-stat-card__hint">Seluruh kategori utama yang aktif di sistem.</p>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-card__label">Level Terhubung</span>
            <div class="admin-stat-card__value">{{ $totalLevel }}</div>
            <p class="admin-stat-card__hint">Akumulasi level unik yang terhubung dengan kursus per program.</p>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-card__label">Kursus Tercatat</span>
            <div class="admin-stat-card__value">{{ $totalKursus }}</div>
            <p class="admin-stat-card__hint">Jumlah kursus yang sudah dikelompokkan ke program.</p>
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
                <h3 class="admin-panel__title">Daftar Program</h3>
                <p class="admin-panel__subtitle">Lihat ringkasan identitas visual, cakupan level, dan total kursus pada setiap program.</p>
            </div>
        </div>

        @if($program->isEmpty())
            <div class="admin-empty-state">
                <div class="admin-empty-state__icon">
                    <i class="bi bi-kanban"></i>
                </div>
                <h3>Belum ada program</h3>
                <p>Tambahkan program pertama untuk mulai menyusun struktur akademik dan relasi kursus.</p>
                <a href="{{ route('admin.program.create') }}" class="admin-btn admin-btn-primary">
                    <i class="bi bi-plus-circle"></i>
                    Tambah Program
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Program</th>
                            <th>Warna</th>
                            <th>Level</th>
                            <th>Kursus</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($program as $item)
                            @php
                                $levelCount = $item->kursuses->pluck('level_id')->filter()->unique()->count();
                                $kursusCount = $item->kursuses->count();
                            @endphp
                            <tr>
                                <td>
                                    <div class="space-y-1">
                                        <div class="font-semibold text-white">{{ $item->nama }}</div>
                                        <div class="text-xs text-slate-400">Program ID #{{ $item->id }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <span class="h-10 w-10 rounded-2xl border border-white/10 shadow-inner" style="background-color: {{ $item->warna }}"></span>
                                        <div>
                                            <div class="text-sm font-medium text-slate-100">{{ $item->warna }}</div>
                                            <div class="text-xs text-slate-400">Identitas visual program</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="admin-badge admin-badge-warning">{{ $levelCount }} level</span>
                                </td>
                                <td>
                                    <span class="admin-badge admin-badge-info">{{ $kursusCount }} kursus</span>
                                </td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.program.edit', $item) }}" class="admin-btn admin-btn-ghost admin-btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.program.destroy', $item) }}" onsubmit="return confirm('Hapus program ini?');">
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
