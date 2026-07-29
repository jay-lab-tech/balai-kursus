@extends('peserta::layouts.student')

@section('title', 'Ringkasan Belajar - Balai Kursus')

@section('content')
<div class="min-h-screen bg-[#f7f8f6] px-4 py-8 sm:px-6 lg:px-10">
    <div class="mx-auto max-w-7xl">
        <header class="mb-10 flex flex-col gap-6 border-b border-[#dce7e5] pb-8 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="font-mono text-xs font-semibold uppercase tracking-[0.24em] text-[#0d9488]">Ruang belajar / {{ now()->translatedFormat('j F Y') }}</p>
                <h1 class="mt-3 max-w-2xl text-4xl font-bold tracking-[-0.04em] text-[#173f5f] sm:text-5xl">Halo, {{ Str::before(auth()->user()->name, ' ') }}.</h1>
                <p class="mt-4 max-w-xl text-base leading-7 text-[#718596]">Lihat perkembangan pendaftaran, penempatan kelas, dan langkah berikutnya dari satu halaman.</p>
            </div>
            <a href="{{ route('peserta.program.index') }}" class="inline-flex min-h-11 items-center justify-center gap-3 rounded-xl bg-[#d97706] px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-[#d97706]/10 transition hover:bg-[#b45309]">
                <i class="bi bi-compass"></i>
                Cari program
            </a>
        </header>

        <section class="mb-10 grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Ringkasan belajar">
            <div class="border-l-4 border-[#0d9488] bg-white p-5 shadow-[0_14px_35px_rgba(23,63,95,.06)]">
                <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#718596]">Pendaftaran</p>
                <p class="mt-3 text-4xl font-bold tracking-tight text-[#173f5f]">{{ $pendaftarans->count() }}</p>
                <p class="mt-2 text-sm text-[#718596]">total pengajuan program</p>
            </div>
            <div class="border-l-4 border-[#d97706] bg-white p-5 shadow-[0_14px_35px_rgba(23,63,95,.06)]">
                <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#718596]">Menunggu tes</p>
                <p class="mt-3 text-4xl font-bold tracking-tight text-[#173f5f]">{{ $pendaftarans->where('status_pendaftaran', \App\Models\Pendaftaran::STATUS_MENUNGGU_TES)->count() }}</p>
                <p class="mt-2 text-sm text-[#718596]">belum mendapat hasil placement</p>
            </div>
            <div class="border-l-4 border-[#16a34a] bg-white p-5 shadow-[0_14px_35px_rgba(23,63,95,.06)]">
                <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#718596]">Ditempatkan</p>
                <p class="mt-3 text-4xl font-bold tracking-tight text-[#173f5f]">{{ $pendaftarans->whereNotNull('kursus_id')->count() }}</p>
                <p class="mt-2 text-sm text-[#718596]">sudah mendapat kelas</p>
            </div>
            <div class="border-l-4 border-[#40627d] bg-white p-5 shadow-[0_14px_35px_rgba(23,63,95,.06)]">
                <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#718596]">Kelas aktif</p>
                <p class="mt-3 text-4xl font-bold tracking-tight text-[#173f5f]">{{ $pendaftarans->where('status_pendaftaran', \App\Models\Pendaftaran::STATUS_AKTIF)->count() }}</p>
                <p class="mt-2 text-sm text-[#718596]">kelas yang sedang berjalan</p>
            </div>
        </section>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,1.35fr)_minmax(280px,.65fr)]">
            <section class="border border-[#dce7e5] bg-white p-6 shadow-[0_14px_35px_rgba(23,63,95,.06)] sm:p-8">
                <div class="flex items-end justify-between gap-4 border-b border-[#e8efed] pb-5">
                    <div>
                        <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#0d9488]">Aktivitas terbaru</p>
                        <h2 class="mt-2 text-2xl font-bold tracking-tight text-[#173f5f]">Status pendaftaran</h2>
                    </div>
                    <a href="{{ route('peserta.pendaftaran.index') }}" class="text-sm font-semibold text-[#0f766e] hover:text-[#d97706]">Lihat semua</a>
                </div>

                @if($pendaftarans->isEmpty())
                    <div class="mt-8 border border-dashed border-[#b8cbc8] px-6 py-12 text-center text-[#718596]">Belum ada pendaftaran program.</div>
                @else
                    <div class="mt-6 divide-y divide-[#e8efed]">
                        @foreach($pendaftarans->take(5) as $index => $pendaftaran)
                            <article class="py-5 first:pt-0 last:pb-0">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="flex min-w-0 gap-4">
                                        <span class="font-mono text-sm text-[#d97706]">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        <div class="min-w-0">
                                            <p class="font-mono text-xs uppercase tracking-[0.16em] text-[#718596]">{{ $pendaftaran->nomor }}</p>
                                            <h3 class="mt-1 break-words text-lg font-bold text-[#173f5f]">{{ $pendaftaran->program->nama ?? 'Program belum tersedia' }}</h3>
                                            <p class="mt-2 text-sm text-[#718596]">Level: {{ $pendaftaran->level->nama ?? 'Menunggu hasil tes' }} · Kelas: {{ $pendaftaran->kursus->nama ?? 'Belum ditempatkan' }}</p>
                                        </div>
                                    </div>
                                    <span class="w-fit rounded-full bg-[#e8f7f4] px-3 py-1 font-mono text-[11px] font-semibold uppercase tracking-wider text-[#0f766e]">{{ str_replace('_', ' ', $pendaftaran->status_pendaftaran) }}</span>
                                </div>
                                @if($pendaftaran->canBePaid())
                                    <a href="{{ route('peserta.pendaftaran.index') }}" class="mt-4 inline-flex min-h-10 items-center gap-2 rounded-lg border border-[#d97706]/30 px-3 py-2 text-sm font-semibold text-[#b45309] transition hover:bg-[#fef3c7]"><i class="bi bi-credit-card"></i> Selesaikan pembayaran</a>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>

            <aside class="space-y-6">
                <section class="border border-[#dce7e5] bg-white p-6 shadow-[0_14px_35px_rgba(23,63,95,.06)]">
                    <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#0d9488]">Jalan pintas</p>
                    <h2 class="mt-2 text-xl font-bold text-[#173f5f]">Yang ingin Anda buka?</h2>
                    <div class="mt-5 space-y-2">
                        <a href="{{ route('peserta.program.index') }}" class="flex min-h-11 items-center justify-between border-b border-[#e8efed] py-3 text-sm font-semibold text-[#40627d] transition hover:border-[#0d9488] hover:text-[#0f766e]"><span><i class="bi bi-compass mr-3 text-[#0d9488]"></i>Daftar program</span><i class="bi bi-arrow-up-right"></i></a>
                        <a href="{{ route('peserta.pendaftaran.index') }}" class="flex min-h-11 items-center justify-between border-b border-[#e8efed] py-3 text-sm font-semibold text-[#40627d] transition hover:border-[#0d9488] hover:text-[#0f766e]"><span><i class="bi bi-clipboard-check mr-3 text-[#0d9488]"></i>Status pendaftaran</span><i class="bi bi-arrow-up-right"></i></a>
                        <a href="{{ route('peserta.kursus.saya') }}" class="flex min-h-11 items-center justify-between border-b border-[#e8efed] py-3 text-sm font-semibold text-[#40627d] transition hover:border-[#0d9488] hover:text-[#0f766e]"><span><i class="bi bi-journal-bookmark mr-3 text-[#0d9488]"></i>Kelas saya</span><i class="bi bi-arrow-up-right"></i></a>
                        <a href="{{ route('peserta.riwayat.index') }}" class="flex min-h-11 items-center justify-between py-3 text-sm font-semibold text-[#40627d] transition hover:text-[#0f766e]"><span><i class="bi bi-receipt mr-3 text-[#0d9488]"></i>Riwayat pembayaran</span><i class="bi bi-arrow-up-right"></i></a>
                    </div>
                </section>

                <section class="bg-[#173f5f] p-6 text-white shadow-[0_14px_35px_rgba(23,63,95,.14)]">
                    <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#8bd8cf]">Cara kerja</p>
                    <h2 class="mt-3 text-xl font-bold">Dari daftar sampai kelas</h2>
                    <ol class="mt-5 space-y-4 text-sm leading-6 text-[#d7e9e7]">
                        <li class="flex gap-3"><span class="font-mono text-[#fbbf24]">01</span><span>Pilih program yang sesuai.</span></li>
                        <li class="flex gap-3"><span class="font-mono text-[#fbbf24]">02</span><span>Ikuti tes penempatan.</span></li>
                        <li class="flex gap-3"><span class="font-mono text-[#fbbf24]">03</span><span>Admin menentukan level dan kelas.</span></li>
                        <li class="flex gap-3"><span class="font-mono text-[#fbbf24]">04</span><span>Selesaikan pembayaran lalu mulai belajar.</span></li>
                    </ol>
                </section>
            </aside>
        </div>
    </div>
</div>
@endsection
