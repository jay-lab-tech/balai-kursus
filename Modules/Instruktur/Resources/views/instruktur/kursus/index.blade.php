@extends('instruktur::layouts.master')

@section('title', 'Kursus yang Diampu')

@section('content')
<div class="min-h-screen bg-[#f7f8f6] px-4 py-8 sm:px-6 lg:px-10">
    <div class="mx-auto max-w-7xl">
        <header class="mb-9 flex flex-col gap-5 border-b border-[#dce7e5] pb-7 sm:flex-row sm:items-end sm:justify-between">
            <div><p class="font-mono text-xs uppercase tracking-[0.22em] text-[#0d9488]">Ruang instruktur / kelas</p><h1 class="mt-2 text-4xl font-bold tracking-tight text-[#173f5f]">Kursus yang diampu</h1><p class="mt-3 text-[#718596]">Pilih kelas untuk mencatat pertemuan, absensi, dan nilai peserta.</p></div>
            <a href="{{ route('instruktur.dashboard') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-[#c8d9d6] bg-white px-4 py-3 text-sm font-semibold text-[#40627d] transition hover:border-[#0d9488] hover:text-[#0f766e]"><i class="bi bi-arrow-left"></i>Ringkasan</a>
        </header>

        @if($kursus->isEmpty())
            <div class="border border-dashed border-[#b8cbc8] bg-white px-6 py-16 text-center"><i class="bi bi-journal-x text-4xl text-[#0d9488]"></i><h2 class="mt-4 text-2xl font-bold text-[#173f5f]">Belum ada kelas</h2><p class="mt-2 text-[#718596]">Kelas akan tampil setelah admin memberikan penugasan.</p></div>
        @else
            <div class="grid gap-5 lg:grid-cols-2">
                @foreach($kursus as $index => $k)
                    <article class="border border-[#dce7e5] bg-white p-6 shadow-[0_14px_35px_rgba(23,63,95,.06)] transition duration-200 hover:-translate-y-0.5 hover:border-[#0d9488] hover:shadow-[0_18px_42px_rgba(23,63,95,.12)]">
                        <div class="flex items-start justify-between gap-4"><span class="font-mono text-sm text-[#d97706]">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span><span class="rounded-full bg-[#e8f7f4] px-3 py-1 font-mono text-[11px] font-semibold uppercase tracking-wider text-[#0f766e]">{{ $k->status ?? 'aktif' }}</span></div>
                        <h2 class="mt-5 break-words text-2xl font-bold tracking-tight text-[#173f5f]">{{ $k->nama }}</h2>
                        <div class="mt-3 flex flex-wrap gap-x-4 gap-y-2 text-sm text-[#718596]"><span><i class="bi bi-diagram-3 mr-1 text-[#0d9488]"></i>{{ $k->program->nama ?? '-' }}</span><span><i class="bi bi-bar-chart mr-1 text-[#0d9488]"></i>{{ $k->level->nama ?? '-' }}</span></div>
                        <div class="mt-7 grid grid-cols-2 border-y border-[#e8efed] py-4"><div><p class="text-xs uppercase tracking-[0.16em] text-[#718596]">Peserta</p><p class="mt-1 text-2xl font-bold text-[#173f5f]">{{ $k->pendaftarans()->count() }}</p></div><div class="border-l border-[#e8efed] pl-5"><p class="text-xs uppercase tracking-[0.16em] text-[#718596]">Pertemuan</p><p class="mt-1 text-2xl font-bold text-[#173f5f]">{{ $k->risalahs()->count() }}</p></div></div>
                        <a href="{{ url('/instruktur/kursus/'.$k->id) }}" class="mt-5 inline-flex min-h-11 w-full items-center justify-between rounded-xl bg-[#173f5f] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#0f766e]">Kelola pertemuan <i class="bi bi-arrow-up-right"></i></a>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
