@extends('layouts.admin')

@section('title', 'Manajemen Kelas')

@section('page-title', 'Manajemen Kelas')

@section('page-description', 'Kelola kelas program, pantau kuota, dan akses peserta per kelas dari satu tampilan operasional.')

@section('content')
<div class="space-y-8">
    <section class="admin-panel overflow-hidden rounded-[2rem] p-6 sm:p-8">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
                    <i class="bi bi-book-half text-red-400"></i>
                    Kelas Program
                </div>
                <h1 class="mt-5 text-3xl font-bold text-white sm:text-4xl">Daftar kelas yang menjadi tempat penempatan peserta aktif.</h1>
                <p class="mt-4 max-w-3xl text-base leading-7 text-slate-300">
                    Setiap kelas terhubung ke program dan level tertentu. Halaman ini membantu admin memantau kuota, harga, serta akses cepat ke daftar peserta dalam tiap kelas.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.kursus.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-red-600 to-red-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-red-500 hover:to-red-600">
                    <i class="bi bi-plus-circle"></i>
                    Tambah Kelas
                </a>
                <a href="{{ route('admin.jadwal.all') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                    <i class="bi bi-calendar-week"></i>
                    Lihat Semua Jadwal
                </a>
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <div class="rounded-[1.5rem] bg-gradient-to-br from-red-600 to-red-700 p-5 text-white shadow-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-red-100">Total Kelas</p>
                <p class="mt-3 text-4xl font-bold">{{ $kursus->total() }}</p>
                <p class="mt-2 text-sm text-red-100/90">Jumlah seluruh kelas program yang terdaftar.</p>
            </div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Halaman Saat Ini</p>
                <p class="mt-3 text-3xl font-bold text-white">{{ $kursus->currentPage() }}</p>
                <p class="mt-2 text-sm text-slate-300">Navigasi paginasi mengikuti daftar kelas terbaru.</p>
            </div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Per Halaman</p>
                <p class="mt-3 text-3xl font-bold text-white">{{ $kursus->perPage() }}</p>
                <p class="mt-2 text-sm text-slate-300">Jumlah baris data yang ditampilkan per halaman.</p>
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
                    <i class="bi bi-collection-play-fill mr-3 text-yellow-300"></i>Daftar Kelas
                </h2>
                <p class="mt-2 text-slate-400">Pantau program, level, periode, kuota terisi, dan akses tindakan penting untuk setiap kelas.</p>
            </div>
            <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-300">
                {{ $kursus->total() }} kelas ditemukan
            </span>
        </div>

        @if($kursus->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white/5 text-4xl text-yellow-300">
                    <i class="bi bi-book"></i>
                </div>
                <h3 class="mt-6 text-2xl font-bold text-white">Belum ada kelas program</h3>
                <p class="mt-3 text-slate-400">Tambahkan kelas baru untuk mulai menempatkan peserta ke program yang sesuai.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Kelas</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Program</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Level</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Periode</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Kuota</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kursus as $kelas)
                            <tr class="transition hover:bg-white/[0.03]">
                                <td class="px-6 py-5">
                                    <p class="font-semibold text-white">{{ $kelas->nama }}</p>
                                    <p class="mt-1 text-sm text-slate-400">Rp {{ number_format($kelas->harga, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $kelas->program->nama ?? '-' }}</td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $kelas->level->nama ?? '-' }}</td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $kelas->periode ?? '-' }}</td>
                                <td class="px-6 py-5">
                                    <div class="space-y-1 text-sm text-slate-300">
                                        <p><span class="font-semibold text-white">{{ $kelas->pendaftarans_count }}</span> / {{ $kelas->kuota }}</p>
                                        <p class="text-xs text-slate-500">peserta terisi</p>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    @php
                                        $statusClass = match($kelas->status) {
                                            'buka' => 'border-emerald-400/20 bg-emerald-500/10 text-emerald-300',
                                            'berjalan' => 'border-yellow-400/20 bg-yellow-400/10 text-yellow-300',
                                            'tutup' => 'border-red-500/20 bg-red-600/10 text-red-200',
                                            default => 'border-white/10 bg-white/5 text-slate-300',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] {{ $statusClass }}">
                                        {{ ucfirst($kelas->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.kursus.edit', $kelas) }}" class="admin-btn admin-btn-ghost admin-btn-sm">
                                            <i class="bi bi-pencil-square text-yellow-300"></i>
                                            Edit
                                        </a>
                                        <a href="{{ route('admin.kursus.peserta', $kelas) }}" class="admin-btn admin-btn-sm" style="border:1px solid rgba(250,204,21,0.2);background:rgba(250,204,21,0.1);color:rgb(253 224 71);">
                                            <i class="bi bi-people"></i>
                                            Peserta
                                        </a>
                                        <form action="{{ route('admin.kursus.destroy', $kelas) }}" method="POST" onsubmit="return confirm('Hapus kelas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm">
                                                <i class="bi bi-trash"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-white/10 px-6 py-4">
                {{ $kursus->links() }}
            </div>
        @endif
    </section>
</div>
@endsection


