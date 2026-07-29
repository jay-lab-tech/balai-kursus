@extends('instruktur::layouts.master')

@section('title', 'Pertemuan & Risalah')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-10">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('instruktur.kursus.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#526875] transition hover:text-[#0d9488]"><i class="bi bi-arrow-left"></i> Kembali ke kursus saya</a>
        <span class="font-mono text-xs uppercase tracking-[.16em] text-[#6c7c82]">Kursus / {{ $kursus->id }} / Pertemuan</span>
    </div>

    <header class="border-b border-[#cfc8bb] pb-6">
        <p class="font-mono text-xs uppercase tracking-[.18em] text-[#0d9488]">{{ $kursus->program->nama ?? 'Program' }} / {{ $kursus->level->nama ?? 'Level' }}</p>
        <div class="mt-2 flex flex-col gap-4 md:flex-row md:items-end md:justify-between"><div><h2 class="text-4xl tracking-tight text-[#173f5f]">{{ $kursus->nama }}</h2><p class="mt-2 text-[#6c7c82]">Kelola materi, catatan, dan absensi setiap pertemuan.</p></div><span class="font-mono text-sm text-[#a84a2a]">{{ $risalahs->count() }} pertemuan tercatat</span></div>
    </header>

    <nav class="mt-5 flex flex-wrap gap-1 border-b border-[#cfc8bb]" aria-label="Navigasi kursus">
        <a href="{{ route('instruktur.kursus.show', $kursus) }}" class="px-4 py-3 text-sm font-semibold text-[#6c7c82] transition hover:text-[#0d9488]">Ringkasan</a>
        <a href="{{ route('instruktur.risalah.index', $kursus) }}" class="border-b-2 border-[#a84a2a] px-4 py-3 text-sm font-semibold text-[#173f5f]">Pertemuan &amp; Risalah</a>
        <a href="{{ route('instruktur.nilai.index', $kursus) }}" class="px-4 py-3 text-sm font-semibold text-[#6c7c82] transition hover:text-[#0d9488]">Nilai Peserta</a>
    </nav>

    <section class="mt-8 border border-[#cfc8bb] bg-[#fffefa]">
        <div class="grid grid-cols-[4rem_minmax(0,1fr)_auto] border-b border-[#cfc8bb] bg-[#f5f2ea] px-5 py-3 font-mono text-[.68rem] uppercase tracking-[.14em] text-[#6c7c82]"><span>No.</span><span>Materi pertemuan</span><span>Aksi</span></div>
        @forelse($risalahs as $r)
            <article class="grid gap-4 border-b border-[#e5e0d6] px-5 py-5 last:border-b-0 md:grid-cols-[4rem_minmax(0,1fr)_auto] md:items-center">
                <span class="font-mono text-sm text-[#a84a2a]">{{ str_pad($r->pertemuan_ke, 2, '0', STR_PAD_LEFT) }}</span>
                <div><h3 class="text-xl text-[#173f5f]">{{ $r->materi ?: 'Materi belum diisi' }}</h3><p class="mt-1 text-sm text-[#6c7c82]">{{ $r->tgl_pertemuan ? \Carbon\Carbon::parse($r->tgl_pertemuan)->format('d F Y') : 'Tanggal belum ditentukan' }} <span class="mx-2 text-[#cfc8bb]">·</span> {{ $r->absensis()->count() }} catatan absensi</p>@if($r->catatan)<p class="mt-2 max-w-2xl text-sm leading-6 text-[#526875]">{{ Str::limit($r->catatan, 150) }}</p>@endif</div>
                <div class="flex flex-wrap gap-2 md:justify-end"><a href="{{ route('instruktur.risalah.edit', $r) }}" class="inline-flex items-center gap-2 border border-[#cfc8bb] px-3 py-2 text-xs font-semibold text-[#173f5f] transition hover:border-[#0d9488] hover:text-[#0d9488]"><i class="bi bi-pencil"></i>Edit risalah</a><a href="{{ route('instruktur.absensi.show', $r) }}" class="inline-flex items-center gap-2 bg-[#0d9488] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#0f766e]"><i class="bi bi-clipboard-check"></i>Absensi</a>@if($r->dokumen)<a href="{{ route('instruktur.risalah.download', $r) }}" class="inline-flex items-center gap-2 border border-[#cfc8bb] px-3 py-2 text-xs font-semibold text-[#a84a2a] transition hover:border-[#a84a2a]"><i class="bi bi-download"></i>Dokumen</a>@endif</div>
            </article>
        @empty
            <div class="px-6 py-14 text-center"><i class="bi bi-journal-x text-3xl text-[#0d9488]"></i><h3 class="mt-3 text-xl text-[#173f5f]">Belum ada risalah</h3><p class="mt-2 text-sm text-[#6c7c82]">Pertemuan dibuat oleh admin sebelum dapat dikelola instruktur.</p></div>
        @endforelse
    </section>
</div>
@endsection
