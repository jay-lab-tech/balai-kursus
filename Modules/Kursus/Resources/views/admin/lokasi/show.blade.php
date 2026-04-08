@extends('layouts.admin')

@section('title', 'Detail Lokasi')

@section('page-title', 'Detail Lokasi')

@section('content')
<div class="space-y-6">
    <section class="admin-panel admin-panel--hero">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-3xl space-y-4">
                <span class="admin-eyebrow"><i class="bi bi-geo-alt"></i>Detail Lokasi</span>
                <div class="space-y-3">
                    <h2 class="text-3xl font-semibold tracking-tight text-white">{{ $lokasi->nama }}</h2>
                    <p class="max-w-2xl text-sm leading-6 text-slate-300">Lihat informasi alamat, kontak, dan catatan operasional untuk memastikan lokasi siap dipakai pada modul jadwal.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.lokasi.edit', $lokasi->id) }}" class="admin-btn admin-btn-primary"><i class="bi bi-pencil-square"></i>Edit Lokasi</a>
                <a href="{{ route('admin.lokasi.index') }}" class="admin-btn admin-btn-secondary"><i class="bi bi-arrow-left"></i>Kembali</a>
            </div>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-3">
        <article class="admin-stat-card"><span class="admin-stat-card__label">Kota</span><div class="admin-stat-card__value text-2xl">{{ $lokasi->kota }}</div><p class="admin-stat-card__hint">Area kota lokasi operasional.</p></article>
        <article class="admin-stat-card"><span class="admin-stat-card__label">Provinsi</span><div class="admin-stat-card__value text-2xl">{{ $lokasi->provinsi }}</div><p class="admin-stat-card__hint">Provinsi yang terkait dengan lokasi.</p></article>
        <article class="admin-stat-card"><span class="admin-stat-card__label">Kontak</span><div class="admin-stat-card__value text-2xl">{{ $lokasi->no_telp }}</div><p class="admin-stat-card__hint">Nomor yang bisa dihubungi untuk koordinasi.</p></article>
    </section>

    <section class="admin-panel max-w-5xl">
        <div class="admin-panel__header"><div><h3 class="admin-panel__title">Informasi Lengkap</h3><p class="admin-panel__subtitle">Detail ini dipakai sebagai referensi operasional untuk jadwal dan pengelolaan kelas.</p></div></div>
        <div class="grid gap-6 md:grid-cols-2">
            <div class="space-y-2 rounded-3xl border border-white/10 bg-slate-950/40 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Alamat</p>
                <p class="text-sm leading-7 text-slate-200">{{ $lokasi->alamat }}</p>
            </div>
            <div class="space-y-4 rounded-3xl border border-white/10 bg-slate-950/40 p-5">
                <div><p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Kota</p><p class="mt-2 text-sm text-slate-200">{{ $lokasi->kota }}</p></div>
                <div><p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Provinsi</p><p class="mt-2 text-sm text-slate-200">{{ $lokasi->provinsi }}</p></div>
                <div><p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">No Telp</p><p class="mt-2 text-sm text-slate-200">{{ $lokasi->no_telp }}</p></div>
            </div>
        </div>

        @if($lokasi->keterangan)
            <div class="mt-6 rounded-3xl border border-white/10 bg-slate-950/40 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Keterangan</p>
                <p class="mt-3 text-sm leading-7 text-slate-200">{{ $lokasi->keterangan }}</p>
            </div>
        @endif
    </section>
</div>
@endsection
