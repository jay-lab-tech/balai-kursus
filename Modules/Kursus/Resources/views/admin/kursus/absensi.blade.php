@extends('layouts.admin')

@section('title', 'Absensi Kelas')

@section('page-title', 'Absensi Kelas')

@section('page-description', 'Lihat kehadiran peserta pada kelas ini beserta status dan waktu hadir.')

@section('content')
<div class="space-y-8">
    <section class="admin-panel overflow-hidden rounded-[2rem] p-6 sm:p-8">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300"><i class="bi bi-clipboard-check-fill text-red-400"></i>Absensi Per Kelas</div>
                <h1 class="mt-5 text-3xl font-bold text-white sm:text-4xl">{{ $kursus->nama }}</h1>
                <p class="mt-4 max-w-3xl text-base leading-7 text-slate-300">Pantau status hadir peserta untuk setiap pertemuan pada kelas ini secara lebih rapi dan mudah discan.</p>
            </div>
            <div class="flex flex-wrap gap-3"><a href="{{ route('admin.kursus.index') }}" class="admin-btn admin-btn-secondary"><i class="bi bi-arrow-left"></i>Kembali ke Kelas</a></div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2">
            <article class="admin-stat-card"><span class="admin-stat-card__label">Total Absensi</span><div class="admin-stat-card__value">{{ $absensis->count() }}</div><p class="admin-stat-card__hint">Jumlah data kehadiran yang tercatat untuk kelas ini.</p></article>
            <article class="admin-stat-card"><span class="admin-stat-card__label">Kelas Aktif</span><div class="text-base font-semibold leading-7 text-white">{{ $kursus->nama }}</div><p class="mt-3 text-sm text-slate-300">{{ $kursus->program->nama ?? '-' }} • {{ $kursus->level->nama ?? '-' }}</p></article>
        </div>
    </section>

    <section class="admin-panel overflow-hidden rounded-[2rem]">
        <div class="flex flex-col gap-3 border-b border-white/10 px-6 py-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white"><i class="bi bi-card-checklist mr-3 text-yellow-300"></i>Daftar Absensi</h2>
                <p class="mt-2 text-slate-400">Ringkasan risalah, peserta, status hadir, dan jam datang untuk kelas ini.</p>
            </div>
            <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-300">{{ $absensis->count() }} data absensi</span>
        </div>

        @if($absensis->isEmpty())
            <div class="admin-empty-state"><div class="admin-empty-state__icon"><i class="bi bi-clipboard-x"></i></div><h3>Belum ada data absensi</h3><p>Absensi akan muncul di sini setelah kehadiran peserta pada kelas ini mulai tercatat.</p></div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead><tr><th>Risalah</th><th>Peserta</th><th>Status</th><th>Jam</th></tr></thead>
                    <tbody>
                        @foreach($absensis as $a)
                            <tr><td>Pertemuan {{ $a->risalah->pertemuan_ke }} - {{ $a->risalah->tgl_pertemuan->format('d M Y') }}</td><td>{{ $a->pendaftaran->peserta->user->name ?? '-' }}</td><td>{{ $a->status }}</td><td>{{ $a->jam_datang ?: '-' }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
@endsection
