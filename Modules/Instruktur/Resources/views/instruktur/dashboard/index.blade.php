@extends('instruktur::layouts.master')

@section('title', 'Dashboard Instruktur')

@section('content')
@php
    $instruktur = auth()->user()->instruktur;
    $pivotData = \App\Models\InstrukturKursusLevel::where('instruktur_id', $instruktur->id)
        ->with(['kursus.program', 'level'])
        ->get();
    $kursusIds = $pivotData->pluck('kursus_id')->unique();
    $kursus = \App\Models\Kursus::whereIn('id', $kursusIds)->get();
    $totalPeserta = $kursus->sum(fn ($item) => $item->pendaftarans()->count());
    $totalRisalah = $kursus->sum(fn ($item) => $item->risalahs()->count());
@endphp

<div class="min-h-screen overflow-x-hidden bg-[#0b2035] px-4 py-10 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mb-8 flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-400/15 text-amber-300">
                <i class="bi bi-speedometer2 text-3xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-sky-300">Ruang Instruktur</p>
                <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-white">Ringkasan mengajar</h1>
                <p class="mt-1 text-sm text-slate-400">Pantau kursus, peserta, dan materi yang sedang Anda kelola.</p>
            </div>
        </div>

        <div class="mb-10 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
            <div class="rounded-2xl border border-white/10 bg-[#102a43] p-6 shadow-xl shadow-black/10">
                <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-400/10 text-amber-300">
                        <i class="bi bi-book text-3xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-gray-400">Total Kursus</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ $kursus->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-[#102a43] p-6 shadow-xl shadow-black/10">
                <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-400/10 text-sky-300">
                        <i class="bi bi-people text-3xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-gray-400">Total Peserta</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ $totalPeserta }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-[#102a43] p-6 shadow-xl shadow-black/10">
                <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-400/10 text-sky-300">
                        <i class="bi bi-calendar-event text-3xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-gray-400">Total Pertemuan</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ $totalRisalah }}</p>
                    </div>
                </div>
            </div>
        </div>

        <section class="space-y-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white">Kursus yang Anda Ajarkan</h2>
                    <p class="text-sm text-gray-400">Setiap kartu akan otomatis turun ke bawah saat ruang layar tidak cukup.</p>
                </div>
                <div class="rounded-full border border-gray-700 bg-gray-900/70 px-4 py-2 text-sm text-gray-300">
                    {{ $pivotData->count() }} penugasan aktif
                </div>
            </div>

            @if($pivotData->count() > 0)
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($pivotData as $pivot)
                        @php $k = $pivot->kursus; @endphp
                        <article class="min-w-0 overflow-hidden rounded-2xl border border-gray-700 bg-gradient-to-br from-gray-800 to-gray-900 p-6 shadow-lg transition-colors duration-200 hover:border-yellow-500/50">
                            <div class="flex h-full flex-col">
                                <div class="min-w-0 flex-1">
                                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-yellow-500/10 text-yellow-400">
                                        <i class="bi bi-bookmark text-2xl"></i>
                                    </div>

                            <h3 class="mb-4 break-words text-xl font-bold leading-tight text-white">
                                        {{ $k->nama }}
                                    </h3>

                                    <div class="space-y-3 text-base text-gray-300">
                                        <div class="flex items-start gap-3">
                                            <i class="bi bi-folder mt-1 text-gray-500"></i>
                                            <div class="min-w-0">
                                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">Program</p>
                                                <p class="break-words">{{ $k->program->nama ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3">
                                            <i class="bi bi-bookmark mt-1 text-gray-500"></i>
                                            <div class="min-w-0">
                                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">Level</p>
                                                <p class="break-words">{{ $pivot->level->nama ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="my-6 grid grid-cols-2 gap-4 rounded-2xl border border-gray-700/80 bg-black/20 p-4">
                                    <div class="text-center">
                                        <p class="text-sm font-semibold text-gray-400">Peserta</p>
                                        <p class="mt-2 text-3xl font-bold text-yellow-400">{{ $k->pendaftarans()->count() }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-sm font-semibold text-gray-400">Pertemuan</p>
                                        <p class="mt-2 text-3xl font-bold text-yellow-400">{{ $k->risalahs()->count() }}</p>
                                    </div>
                                </div>

                                <a href="{{ url('/instruktur/kursus/' . $k->id) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-yellow-600 to-yellow-700 px-5 py-3 text-base font-semibold text-white transition-all duration-200 hover:from-yellow-700 hover:to-yellow-800">
                                    <i class="bi bi-arrow-right mr-2"></i>Lihat Detail
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="rounded-3xl border border-dashed border-gray-700 bg-gray-900/60 px-6 py-16 text-center">
                    <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-gray-800 text-gray-400">
                        <i class="bi bi-book text-5xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Belum Ada Kursus</h2>
                    <p class="mt-2 text-gray-400">Hubungi admin untuk mendapatkan penugasan mengajar.</p>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
