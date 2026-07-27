@extends('layouts.admin')

@section('title', 'Peserta Kelas')

@section('page-title', 'Peserta Kelas')

@section('page-description', 'Lihat daftar peserta yang sudah ditempatkan ke kelas ini beserta level, nilai tes, dan status pendaftarannya.')

@section('content')
<div class="space-y-8">
    <section class="admin-panel overflow-hidden rounded-[2rem] p-6 sm:p-8">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-sky-500/30 bg-sky-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
                    <i class="bi bi-people-fill text-sky-400"></i>
                    Peserta Terdaftar
                </div>
                <h1 class="mt-5 text-3xl font-bold text-white sm:text-4xl">{{ $kursus->nama }}</h1>
                <p class="mt-3 text-base text-slate-300">{{ $kursus->program->nama ?? '-' }} • {{ $kursus->level->nama ?? '-' }}</p>
                <p class="mt-4 max-w-3xl text-base leading-7 text-slate-300">Daftar ini membantu admin memantau siapa saja yang sudah masuk ke kelas, level penempatannya, nilai tes, dan status pendaftaran terakhir.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.kursus.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Kelas
                </a>
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <div class="rounded-[1.5rem] bg-gradient-to-br from-sky-600 to-sky-700 p-5 text-white shadow-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-100">Total Peserta</p>
                <p class="mt-3 text-4xl font-bold">{{ $peserta->total() }}</p>
                <p class="mt-2 text-sm text-sky-100/90">Jumlah peserta yang sudah tercatat di kelas ini.</p>
            </div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Halaman Saat Ini</p>
                <p class="mt-3 text-3xl font-bold text-white">{{ $peserta->currentPage() }}</p>
                <p class="mt-2 text-sm text-slate-300">Navigasi data peserta per halaman.</p>
            </div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Kuota Kelas</p>
                <p class="mt-3 text-3xl font-bold text-white">{{ $kursus->kuota ?? '-' }}</p>
                <p class="mt-2 text-sm text-slate-300">Kapasitas maksimum peserta untuk kelas ini.</p>
            </div>
        </div>
    </section>

    <section class="admin-panel overflow-hidden rounded-[2rem]">
        <div class="flex flex-col gap-3 border-b border-white/10 px-6 py-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">
                    <i class="bi bi-person-lines-fill mr-3 text-yellow-300"></i>Daftar Peserta Kelas
                </h2>
                <p class="mt-2 text-slate-400">Informasi peserta menampilkan email, level hasil placement, nilai tes, dan status pendaftaran.</p>
            </div>
            <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-300">
                {{ $peserta->total() }} peserta ditemukan
            </span>
        </div>

        @if($peserta->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white/5 text-4xl text-yellow-300">
                    <i class="bi bi-people"></i>
                </div>
                <h3 class="mt-6 text-2xl font-bold text-white">Belum ada peserta pada kelas ini</h3>
                <p class="mt-3 text-slate-400">Peserta akan tampil di sini setelah proses placement atau penempatan kelas dilakukan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Peserta</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Email</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Level</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Nilai Tes</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peserta as $item)
                            <tr class="transition hover:bg-white/[0.03]">
                                <td class="px-6 py-5 text-sm font-semibold text-white">{{ $item->peserta->user->name ?? '-' }}</td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $item->participant_email_snapshot ?? $item->peserta->user->email ?? '-' }}</td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $item->level->nama ?? 'Belum ada level' }}</td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $item->placementScore?->final_score ?? 'Belum ada nilai tes' }}</td>
                                <td class="px-6 py-5">
                                    @php
                                        $statusClass = match($item->status_pendaftaran) {
                                            'aktif' => 'border-emerald-400/20 bg-emerald-500/10 text-emerald-300',
                                            'menunggu_pembayaran' => 'border-yellow-400/20 bg-yellow-400/10 text-yellow-300',
                                            'menunggu_tes' => 'border-blue-400/20 bg-blue-500/10 text-blue-300',
                                            default => 'border-white/10 bg-white/5 text-slate-300',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] {{ $statusClass }}">
                                        {{ str_replace('_', ' ', ucfirst($item->status_pendaftaran)) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-white/10 px-6 py-4">
                {{ $peserta->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
