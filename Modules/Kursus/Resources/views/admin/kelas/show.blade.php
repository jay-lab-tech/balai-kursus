@extends('layouts.admin')

@section('title', 'Detail Kelas')

@section('page-title', 'Detail Kelas')

@section('content')
<div class="space-y-6">
    <section class="admin-panel admin-panel--hero">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-3xl space-y-4">
                <span class="admin-eyebrow"><i class="bi bi-door-open"></i>Detail Kelas</span>
                <div class="space-y-3">
                    <h2 class="text-3xl font-semibold tracking-tight text-white">{{ $kela->nama }}</h2>
                    <p class="max-w-2xl text-sm leading-6 text-slate-300">Ringkasan ini membantu admin melihat kapasitas dan fasilitas ruang kelas sebelum dipakai di jadwal atau penempatan peserta.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.kelas.edit', $kela->id) }}" class="admin-btn admin-btn-primary"><i class="bi bi-pencil-square"></i>Edit Kelas</a>
                <a href="{{ route('admin.kelas.index') }}" class="admin-btn admin-btn-secondary"><i class="bi bi-arrow-left"></i>Kembali</a>
            </div>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <article class="admin-stat-card"><span class="admin-stat-card__label">Kapasitas</span><div class="admin-stat-card__value">{{ $kela->kapasitas }}</div><p class="admin-stat-card__hint">Total peserta yang bisa ditampung di ruang ini.</p></article>
        <article class="admin-stat-card xl:col-span-2"><span class="admin-stat-card__label">Fasilitas</span><div class="text-base font-medium leading-7 text-white">{{ $kela->fasilitas }}</div><p class="admin-stat-card__hint">Peralatan atau sarana utama yang tersedia di ruang kelas.</p></article>
    </section>

    <section class="admin-panel max-w-4xl">
        <div class="admin-panel__header"><div><h3 class="admin-panel__title">Informasi Ruang Kelas</h3><p class="admin-panel__subtitle">Gunakan detail ini untuk memastikan kelas yang dipilih sesuai dengan kebutuhan pembelajaran.</p></div></div>
        <div class="grid gap-6 md:grid-cols-2">
            <div class="rounded-3xl border border-white/10 bg-slate-950/40 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Nama Kelas</p>
                <p class="mt-3 text-lg font-semibold text-white">{{ $kela->nama }}</p>
            </div>
            <div class="rounded-3xl border border-white/10 bg-slate-950/40 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Kapasitas</p>
                <p class="mt-3 text-lg font-semibold text-white">{{ $kela->kapasitas }} orang</p>
            </div>
        </div>

        @if($kela->keterangan)
            <div class="mt-6 rounded-3xl border border-white/10 bg-slate-950/40 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Keterangan</p>
                <p class="mt-3 text-sm leading-7 text-slate-200">{{ $kela->keterangan }}</p>
            </div>
        @endif
    </section>
</div>
@endsection
