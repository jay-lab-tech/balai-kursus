@extends('instruktur::layouts.master')

@section('title', $kursus->nama)

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-10">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('instruktur.kursus.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#526875] transition hover:text-[#0d9488]">
            <i class="bi bi-arrow-left"></i> Kembali ke kursus saya
        </a>
        <span class="font-mono text-xs uppercase tracking-[.16em] text-[#6c7c82]">Kursus / {{ $kursus->id }}</span>
    </div>

    <header class="border-b border-[#cfc8bb] pb-7">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="font-mono text-xs uppercase tracking-[.18em] text-[#0d9488]">Ruang kerja kursus</p>
                <h2 class="mt-2 max-w-3xl text-4xl tracking-tight text-[#173f5f]">{{ $kursus->nama }}</h2>
                <p class="mt-3 text-[#6c7c82]">{{ $kursus->program->nama ?? '-' }} <span class="mx-2 text-[#cfc8bb]">/</span> {{ $kursus->level->nama ?? '-' }}</p>
            </div>
            <div class="grid grid-cols-2 gap-6 border-l border-[#cfc8bb] pl-6 text-right">
                <div><p class="font-mono text-[.65rem] uppercase tracking-[.14em] text-[#6c7c82]">Peserta</p><p class="mt-1 text-2xl font-semibold text-[#173f5f]">{{ $kursus->pendaftarans()->count() }}</p></div>
                <div><p class="font-mono text-[.65rem] uppercase tracking-[.14em] text-[#6c7c82]">Pertemuan</p><p class="mt-1 text-2xl font-semibold text-[#173f5f]">{{ $risalah->count() }}</p></div>
            </div>
        </div>
    </header>

    <nav class="mt-5 flex flex-wrap gap-1 border-b border-[#cfc8bb]" aria-label="Navigasi kursus">
        <a href="{{ route('instruktur.kursus.show', $kursus) }}" class="border-b-2 border-[#a84a2a] px-4 py-3 text-sm font-semibold text-[#173f5f]">Ringkasan</a>
        <a href="{{ route('instruktur.risalah.index', $kursus) }}" class="px-4 py-3 text-sm font-semibold text-[#6c7c82] transition hover:text-[#0d9488]">Pertemuan &amp; Risalah</a>
        <a href="{{ route('instruktur.nilai.index', $kursus) }}" class="px-4 py-3 text-sm font-semibold text-[#6c7c82] transition hover:text-[#0d9488]">Nilai Peserta</a>
    </nav>

    <section class="mt-8 grid gap-8 lg:grid-cols-[minmax(0,1fr)_18rem]">
        <div>
            <div class="mb-4 flex items-end justify-between gap-4">
                <div><p class="font-mono text-xs uppercase tracking-[.16em] text-[#0d9488]">Operasional kelas</p><h3 class="mt-1 text-2xl text-[#173f5f]">Daftar pertemuan</h3></div>
                <a href="{{ route('instruktur.risalah.index', $kursus) }}" class="text-sm font-semibold text-[#a84a2a] hover:underline">Kelola risalah <i class="bi bi-arrow-up-right"></i></a>
            </div>
            <div class="overflow-hidden border border-[#cfc8bb] bg-[#fffefa]">
                @forelse($risalah as $r)
                    <div class="flex flex-col gap-5 border-b border-[#e5e0d6] px-5 py-5 last:border-b-0 md:flex-row md:items-center md:justify-between">
                        <div class="flex min-w-0 items-start gap-4">
                            <span class="font-mono text-sm text-[#a84a2a]">{{ str_pad($r->pertemuan_ke, 2, '0', STR_PAD_LEFT) }}</span>
                            <div class="min-w-0"><h4 class="text-lg text-[#173f5f]">Pertemuan {{ $r->pertemuan_ke }}</h4><p class="mt-1 text-sm text-[#6c7c82]">{{ $r->tgl_pertemuan ? \Carbon\Carbon::parse($r->tgl_pertemuan)->format('d F Y') : 'Tanggal belum ditentukan' }}</p><p class="mt-2 truncate text-sm text-[#526875]">{{ $r->materi ?? 'Materi belum diisi' }}</p></div>
                        </div>
                        <div class="flex shrink-0 flex-wrap gap-2">
                            <a href="{{ route('instruktur.risalah.edit', $r) }}" class="inline-flex items-center gap-2 border border-[#cfc8bb] px-3 py-2 text-xs font-semibold text-[#173f5f] transition hover:border-[#0d9488] hover:text-[#0d9488]"><i class="bi bi-file-earmark-text"></i>Risalah</a>
                            <a href="{{ route('instruktur.absensi.show', $r) }}" class="inline-flex items-center gap-2 bg-[#0d9488] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#0f766e]"><i class="bi bi-clipboard-check"></i>Absensi</a>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-14 text-center"><i class="bi bi-calendar2-x text-3xl text-[#0d9488]"></i><h4 class="mt-3 text-xl text-[#173f5f]">Belum ada pertemuan</h4><p class="mt-2 text-sm text-[#6c7c82]">Hubungi admin untuk menambahkan jadwal dan pertemuan kelas.</p></div>
                @endforelse
            </div>
        </div>

        <aside class="border-l border-[#cfc8bb] pl-6">
            <p class="font-mono text-xs uppercase tracking-[.16em] text-[#0d9488]">Konteks kelas</p>
            <dl class="mt-5 space-y-5 text-sm">
                <div><dt class="text-[#6c7c82]">Program</dt><dd class="mt-1 font-semibold text-[#173f5f]">{{ $kursus->program->nama ?? '-' }}</dd></div>
                <div><dt class="text-[#6c7c82]">Level</dt><dd class="mt-1 font-semibold text-[#173f5f]">{{ $kursus->level->nama ?? '-' }}</dd></div>
                <div><dt class="text-[#6c7c82]">Instruktur utama</dt><dd class="mt-1 font-semibold text-[#173f5f]">{{ $kursus->instruktur->nama_instr ?? '-' }}</dd></div>
                <div><dt class="text-[#6c7c82]">Peserta hadir tercatat</dt><dd class="mt-1 font-semibold text-[#173f5f]">{{ $risalah->sum(fn ($item) => $item->absensis()->count()) }}</dd></div>
            </dl>
            <div class="mt-8 border-t border-[#cfc8bb] pt-5"><p class="text-sm leading-6 text-[#6c7c82]">Gunakan tab di atas untuk berpindah dari ringkasan ke risalah dan nilai tanpa meninggalkan konteks kursus.</p></div>
        </aside>
    </section>
</div>
@endsection
