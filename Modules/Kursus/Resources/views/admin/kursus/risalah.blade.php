@extends('layouts.admin')

@section('title', 'Risalah Kelas')

@section('page-title', 'Risalah Kelas')

@section('page-description', 'Pantau dokumentasi materi per pertemuan untuk kelas yang sedang ditinjau.')

@section('content')
<div class="space-y-8">
    <section class="admin-panel overflow-hidden rounded-[2rem] p-6 sm:p-8">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-sky-500/30 bg-sky-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300"><i class="bi bi-journal-richtext text-sky-400"></i>Risalah Per Kelas</div>
                <h1 class="mt-5 text-3xl font-bold text-white sm:text-4xl">{{ $kursus->nama }}</h1>
                <p class="mt-4 max-w-3xl text-base leading-7 text-slate-300">Semua dokumentasi materi, tanggal pertemuan, dan instruktur untuk kelas ini terkumpul dalam satu tampilan.</p>
            </div>
            <div class="flex flex-wrap gap-3"><a href="{{ route('admin.kursus.index') }}" class="admin-btn admin-btn-secondary"><i class="bi bi-arrow-left"></i>Kembali ke Kelas</a></div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2">
            <article class="admin-stat-card"><span class="admin-stat-card__label">Total Risalah</span><div class="admin-stat-card__value">{{ $risalahs->count() }}</div><p class="admin-stat-card__hint">Jumlah risalah yang sudah terdokumentasi untuk kelas ini.</p></article>
            <article class="admin-stat-card"><span class="admin-stat-card__label">Kelas Aktif</span><div class="text-base font-semibold leading-7 text-white">{{ $kursus->nama }}</div><p class="mt-3 text-sm text-slate-300">{{ $kursus->program->nama ?? '-' }} • {{ $kursus->level->nama ?? '-' }}</p></article>
        </div>
    </section>

    <section class="admin-panel overflow-hidden rounded-[2rem]">
        <div class="flex flex-col gap-3 border-b border-white/10 px-6 py-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white"><i class="bi bi-journal-bookmark-fill mr-3 text-yellow-300"></i>Daftar Risalah</h2>
                <p class="mt-2 text-slate-400">Menampilkan pertemuan, tanggal, instruktur, dan materi yang diajarkan.</p>
            </div>
            <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-300">{{ $risalahs->count() }} risalah</span>
        </div>

        @if($risalahs->isEmpty())
            <div class="admin-empty-state"><div class="admin-empty-state__icon"><i class="bi bi-journal-x"></i></div><h3>Belum ada risalah</h3><p>Dokumentasi materi akan muncul di sini setelah instruktur mengisi risalah pertemuan.</p></div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead><tr><th>Pertemuan</th><th>Tanggal</th><th>Instruktur</th><th>Materi</th></tr></thead>
                    <tbody>
                        @foreach($risalahs as $r)
                            <tr><td>{{ $r->pertemuan_ke }}</td><td>{{ $r->tgl_pertemuan->format('d M Y') }}</td><td>{{ $r->instruktur->nama_instr }}</td><td>{{ $r->materi }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
@endsection
