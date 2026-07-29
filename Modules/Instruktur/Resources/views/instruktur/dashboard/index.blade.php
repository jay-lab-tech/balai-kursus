@extends('instruktur::layouts.master')

@section('title', 'Ringkasan Mengajar')

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

<div class="min-h-screen bg-[#f7f8f6] px-4 py-8 sm:px-6 lg:px-10">
    <div class="mx-auto max-w-7xl">
        <header class="mb-10 flex flex-col gap-6 border-b border-[#dce7e5] pb-8 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="font-mono text-xs font-semibold uppercase tracking-[0.24em] text-[#0d9488]">Ruang kerja instruktur / {{ now()->translatedFormat('j F Y') }}</p>
                <h1 class="mt-3 max-w-2xl text-4xl font-bold tracking-[-0.04em] text-[#173f5f] sm:text-5xl">Siap mengajar,<br><span class="text-[#0d9488]">{{ Str::before(auth()->user()->name, ' ') }}.</span></h1>
                <p class="mt-4 max-w-xl text-base leading-7 text-[#718596]">Satu tempat untuk melihat kelas yang ditugaskan, peserta, dan catatan pertemuan yang perlu ditindaklanjuti.</p>
            </div>
            <a href="{{ url('/instruktur/jadwal') }}" class="inline-flex min-h-11 items-center justify-center gap-3 rounded-xl bg-[#173f5f] px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-[#173f5f]/10 transition hover:bg-[#0f766e]">
                <i class="bi bi-calendar3"></i>
                Buka jadwal mengajar
            </a>
        </header>

        <section class="mb-10 grid gap-4 sm:grid-cols-3" aria-label="Ringkasan aktivitas mengajar">
            <div class="border-l-4 border-[#0d9488] bg-white p-5 shadow-[0_14px_35px_rgba(23,63,95,.06)]">
                <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#718596]">Kelas aktif</p>
                <p class="mt-3 text-4xl font-bold tracking-tight text-[#173f5f]">{{ $kursus->count() }}</p>
                <p class="mt-2 text-sm text-[#718596]">kelas yang sedang Anda kelola</p>
            </div>
            <div class="border-l-4 border-[#d97706] bg-white p-5 shadow-[0_14px_35px_rgba(23,63,95,.06)]">
                <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#718596]">Peserta</p>
                <p class="mt-3 text-4xl font-bold tracking-tight text-[#173f5f]">{{ $totalPeserta }}</p>
                <p class="mt-2 text-sm text-[#718596]">terdaftar di kelas Anda</p>
            </div>
            <div class="border-l-4 border-[#40627d] bg-white p-5 shadow-[0_14px_35px_rgba(23,63,95,.06)]">
                <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#718596]">Pertemuan</p>
                <p class="mt-3 text-4xl font-bold tracking-tight text-[#173f5f]">{{ $totalRisalah }}</p>
                <p class="mt-2 text-sm text-[#718596]">catatan pertemuan tersedia</p>
            </div>
        </section>

        <section>
            <div class="mb-5 flex items-end justify-between gap-4">
                <div>
                    <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#0d9488]">Daftar penugasan</p>
                    <h2 class="mt-2 text-2xl font-bold tracking-tight text-[#173f5f]">Kelas yang Anda ajarkan</h2>
                </div>
                <span class="hidden font-mono text-xs text-[#718596] sm:block">{{ $pivotData->count() }} penugasan aktif</span>
            </div>

            @if($pivotData->count() > 0)
                <div class="grid gap-5 lg:grid-cols-2">
                    @foreach($pivotData as $index => $pivot)
                        @php $k = $pivot->kursus; @endphp
                        <article class="group flex min-w-0 flex-col border border-[#dce7e5] bg-white p-6 shadow-[0_14px_35px_rgba(23,63,95,.06)] transition duration-200 hover:-translate-y-0.5 hover:border-[#0d9488] hover:shadow-[0_18px_42px_rgba(23,63,95,.12)]">
                            <div class="flex items-start justify-between gap-5">
                                <div class="flex min-w-0 gap-4">
                                    <span class="font-mono text-sm text-[#d97706]">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <div class="min-w-0">
                                        <h3 class="break-words text-xl font-bold leading-tight text-[#173f5f]">{{ $k->nama }}</h3>
                                        <p class="mt-2 text-sm text-[#718596]">{{ $k->program->nama ?? 'Program' }} · {{ $pivot->level->nama ?? 'Level belum ditentukan' }}</p>
                                    </div>
                                </div>
                                <span class="shrink-0 rounded-full bg-[#e8f7f4] px-3 py-1 font-mono text-[11px] font-semibold uppercase tracking-wider text-[#0f766e]">Aktif</span>
                            </div>

                            <div class="mt-7 grid grid-cols-2 border-y border-[#e8efed] py-4">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.16em] text-[#718596]">Peserta</p>
                                    <p class="mt-1 text-2xl font-bold text-[#173f5f]">{{ $k->pendaftarans()->count() }}</p>
                                </div>
                                <div class="border-l border-[#e8efed] pl-5">
                                    <p class="text-xs uppercase tracking-[0.16em] text-[#718596]">Pertemuan</p>
                                    <p class="mt-1 text-2xl font-bold text-[#173f5f]">{{ $k->risalahs()->count() }}</p>
                                </div>
                            </div>

                            <a href="{{ url('/instruktur/kursus/' . $k->id) }}" class="mt-5 inline-flex min-h-11 items-center justify-between rounded-xl border border-[#dce7e5] px-4 py-3 text-sm font-semibold text-[#173f5f] transition hover:border-[#0d9488] hover:bg-[#e8f7f4] hover:text-[#0f766e]">
                                Kelola kelas
                                <i class="bi bi-arrow-up-right text-base"></i>
                            </a>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="border border-dashed border-[#b8cbc8] bg-white px-6 py-16 text-center">
                    <i class="bi bi-journal-x text-4xl text-[#0d9488]"></i>
                    <h2 class="mt-4 text-2xl font-bold text-[#173f5f]">Belum ada penugasan</h2>
                    <p class="mx-auto mt-2 max-w-md text-[#718596]">Hubungi admin untuk mendapatkan kelas yang perlu Anda kelola.</p>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
