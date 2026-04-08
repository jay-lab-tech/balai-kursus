@extends('layouts.admin')

@section('title', 'Tes Penempatan')

@section('page-title', 'Tes Penempatan')

@section('page-description', 'Kelola hasil placement test, status penempatan peserta, dan akses cepat ke input maupun edit skor.')

@section('content')
<div class="space-y-8">
    <section class="admin-panel overflow-hidden rounded-[2rem] p-6 sm:p-8">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
                    <i class="bi bi-clipboard-data-fill text-red-400"></i>
                    Placement Control
                </div>
                <h1 class="mt-5 text-3xl font-bold text-white sm:text-4xl">Antrian tes penempatan peserta dalam satu panel.</h1>
                <p class="mt-4 max-w-3xl text-base leading-7 text-slate-300">Admin dapat mencari pendaftaran, menginput hasil tes, meninjau level yang dihasilkan, dan memantau kelas hasil penempatan secara langsung.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <form method="GET" action="{{ route('admin.score.index') }}" class="flex flex-wrap gap-3">
                    <div class="relative">
                        <i class="bi bi-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></i>
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari peserta, program, nomor..." class="w-72 rounded-2xl border border-white/10 bg-slate-950/70 py-3 pl-12 pr-4 text-sm text-white placeholder:text-slate-500 focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10">
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                        <i class="bi bi-search"></i>
                        Cari
                    </button>
                </form>
                <a href="{{ route('admin.score.export') }}" class="inline-flex items-center gap-2 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-300 transition hover:bg-emerald-500/15">
                    <i class="bi bi-download"></i>
                    Export
                </a>
                <a href="{{ route('admin.score.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-red-600 to-red-700 px-4 py-3 text-sm font-semibold text-white transition hover:from-red-500 hover:to-red-600">
                    <i class="bi bi-plus-circle"></i>
                    Input Hasil Tes
                </a>
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <div class="rounded-[1.5rem] bg-gradient-to-br from-red-600 to-red-700 p-5 text-white shadow-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-red-100">Total Pendaftaran</p>
                <p class="mt-3 text-4xl font-bold">{{ $pendaftarans->total() }}</p>
                <p class="mt-2 text-sm text-red-100/90">Total baris antrian placement yang sedang dipantau.</p>
            </div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Pencarian Aktif</p>
                <p class="mt-3 text-xl font-bold text-white">{{ request('q') ? 'Ya' : 'Tidak' }}</p>
                <p class="mt-2 text-sm text-slate-300">{{ request('q') ?: 'Belum ada kata kunci aktif.' }}</p>
            </div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Halaman Saat Ini</p>
                <p class="mt-3 text-3xl font-bold text-white">{{ $pendaftarans->currentPage() }}</p>
                <p class="mt-2 text-sm text-slate-300">Navigasi data placement per halaman.</p>
            </div>
        </div>
    </section>

    @if(session('success'))
        <div class="rounded-[1.5rem] border border-emerald-400/20 bg-emerald-500/10 px-5 py-4 text-emerald-200 shadow-lg">
            <div class="flex items-start gap-3">
                <i class="bi bi-check-circle-fill mt-0.5 text-lg text-emerald-300"></i>
                <div>
                    <p class="font-semibold">Perubahan berhasil disimpan</p>
                    <p class="mt-1 text-sm text-emerald-100/90">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <section class="admin-panel overflow-hidden rounded-[2rem]">
        <div class="flex flex-col gap-3 border-b border-white/10 px-6 py-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">
                    <i class="bi bi-bar-chart-steps mr-3 text-yellow-300"></i>Antrian Tes Penempatan
                </h2>
                <p class="mt-2 text-slate-400">Lihat pendaftaran, nilai placement, level hasil penempatan, kelas tujuan, dan tindakan lanjutan.</p>
            </div>
            <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-300">
                {{ $pendaftarans->total() }} data placement
            </span>
        </div>

        @if($pendaftarans->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white/5 text-4xl text-yellow-300">
                    <i class="bi bi-clipboard-x"></i>
                </div>
                <h3 class="mt-6 text-2xl font-bold text-white">Belum ada data pendaftaran program</h3>
                <p class="mt-3 text-slate-400">Data placement akan muncul di sini setelah peserta melakukan pendaftaran program.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Pendaftaran</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Peserta</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Program</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Nilai Tes</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Level</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Kelas</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendaftarans as $pendaftaran)
                            <tr class="transition hover:bg-white/[0.03]">
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $pendaftaran->nomor }}</td>
                                <td class="px-6 py-5">
                                    <p class="font-semibold text-white">{{ $pendaftaran->peserta->user->name ?? '-' }}</p>
                                    <p class="mt-1 text-sm text-slate-400">{{ $pendaftaran->peserta->user->email ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $pendaftaran->program->nama ?? '-' }}</td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $pendaftaran->placementScore?->final_score ?? 'Belum diinput' }}</td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $pendaftaran->level->nama ?? 'Belum ditentukan' }}</td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $pendaftaran->kursus->nama ?? 'Belum ditempatkan' }}</td>
                                <td class="px-6 py-5">
                                    @php
                                        $statusClass = match($pendaftaran->status_pendaftaran) {
                                            'aktif' => 'border-emerald-400/20 bg-emerald-500/10 text-emerald-300',
                                            'menunggu_pembayaran' => 'border-yellow-400/20 bg-yellow-400/10 text-yellow-300',
                                            'menunggu_tes' => 'border-blue-400/20 bg-blue-500/10 text-blue-300',
                                            default => 'border-white/10 bg-white/5 text-slate-300',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] {{ $statusClass }}">
                                        {{ str_replace('_', ' ', ucfirst($pendaftaran->status_pendaftaran)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-sm">
                                    <div class="flex flex-wrap gap-3">
                                        @if($pendaftaran->placementScore)
                                            <a href="{{ route('admin.score.show', $pendaftaran->placementScore) }}" class="admin-btn admin-btn-ghost admin-btn-sm">Detail</a>
                                            <a href="{{ route('admin.score.edit', $pendaftaran->placementScore) }}" class="admin-btn admin-btn-ghost admin-btn-sm">Edit</a>
                                        @else
                                            <a href="{{ route('admin.score.create', ['pendaftaran_id' => $pendaftaran->id]) }}" class="admin-btn admin-btn-sm" style="background:linear-gradient(135deg,#059669,#10b981);color:#fff;">Input Tes</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-white/10 px-6 py-4">
                {{ $pendaftarans->links() }}
            </div>
        @endif
    </section>
</div>
@endsection


