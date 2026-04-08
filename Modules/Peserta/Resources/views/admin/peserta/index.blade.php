@extends('layouts.admin')

@section('title', 'Manajemen Peserta')

@section('page-title', 'Manajemen Peserta')

@section('page-description', 'Kelola data peserta, pencarian cepat, dan aksi operasional dari satu tampilan yang lebih terstruktur.')

@section('content')
<div class="space-y-8">
    <section class="admin-panel overflow-hidden rounded-[2rem] p-6 sm:p-8">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
                    <i class="bi bi-people-fill text-red-400"></i>
                    Direktori Peserta
                </div>
                <h1 class="mt-5 text-3xl font-bold text-white sm:text-4xl">Kelola seluruh data peserta dengan tampilan yang lebih cepat dibaca.</h1>
                <p class="mt-4 max-w-3xl text-base leading-7 text-slate-300">
                    Gunakan halaman ini untuk menambah peserta baru, mencari data berdasarkan identitas, dan menjaga data peserta tetap rapi serta mudah ditelusuri.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.peserta.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-red-600 to-red-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-red-500 hover:to-red-600">
                    <i class="bi bi-person-plus-fill"></i>
                    Tambah Peserta
                </a>
                <a href="{{ route('admin.peserta.export') }}" class="inline-flex items-center gap-2 rounded-2xl border border-yellow-400/20 bg-yellow-400/10 px-5 py-3 text-sm font-semibold text-yellow-300 transition hover:bg-yellow-400/15">
                    <i class="bi bi-download"></i>
                    Export Peserta
                </a>
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <div class="rounded-[1.5rem] bg-gradient-to-br from-red-600 to-red-700 p-5 text-white shadow-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-red-100">Total Data</p>
                <p class="mt-3 text-4xl font-bold">{{ $pesertas->count() }}</p>
                <p class="mt-2 text-sm text-red-100/90">Peserta tampil berdasarkan filter aktif saat ini.</p>
            </div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Pencarian</p>
                <p class="mt-3 text-xl font-bold text-white">{{ request('search') ? 'Aktif' : 'Semua data' }}</p>
                <p class="mt-2 text-sm text-slate-300">{{ request('search') ?: 'Belum ada kata kunci pencarian.' }}</p>
            </div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Filter Status</p>
                <p class="mt-3 text-xl font-bold text-white">{{ request('filter') ? ucfirst(request('filter')) : 'Semua status' }}</p>
                <p class="mt-2 text-sm text-slate-300">Pilih status untuk mempersempit daftar peserta yang ditampilkan.</p>
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

    <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">
                    <i class="bi bi-funnel-fill mr-3 text-yellow-300"></i>Filter dan Pencarian
                </h2>
                <p class="mt-2 text-slate-400">Cari peserta berdasarkan nama, email, nomor peserta, nomor HP, atau instansi.</p>
            </div>
            <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-300">
                {{ $pesertas->count() }} hasil tampil
            </span>
        </div>

        <form method="GET" action="" class="mt-6 grid gap-4 lg:grid-cols-[1.6fr_0.8fr_0.6fr]">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Cari peserta</label>
                <div class="relative">
                    <i class="bi bi-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama, email, nomor peserta, instansi..."
                        class="w-full rounded-2xl border border-white/10 bg-slate-950/70 py-3 pl-12 pr-4 text-sm text-white placeholder:text-slate-500 focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10">
                </div>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Status peserta</label>
                <select name="filter" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10">
                    <option value="">Semua</option>
                    <option value="aktif" {{ request('filter') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('filter') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-red-600 to-red-700 px-4 py-3 text-sm font-semibold text-white transition hover:from-red-500 hover:to-red-600">
                    <i class="bi bi-search"></i>
                    Terapkan
                </button>
            </div>
        </form>
    </section>

    <section class="admin-panel overflow-hidden rounded-[2rem]">
        <div class="flex flex-col gap-3 border-b border-white/10 px-6 py-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">
                    <i class="bi bi-table mr-3 text-yellow-300"></i>Daftar Peserta
                </h2>
                <p class="mt-2 text-slate-400">Tabel ini menampilkan data peserta lengkap beserta aksi edit dan hapus.</p>
            </div>
            <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-300">
                {{ $pesertas->count() }} baris data
            </span>
        </div>

        @if($pesertas->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white/5 text-4xl text-yellow-300">
                    <i class="bi bi-people"></i>
                </div>
                <h3 class="mt-6 text-2xl font-bold text-white">Belum ada peserta yang cocok</h3>
                <p class="mt-3 text-slate-400">Coba ubah filter atau tambahkan peserta baru untuk mulai mengisi daftar ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">No</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Peserta</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Nomor Peserta</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Kontak</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Instansi</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesertas as $p)
                            <tr class="transition hover:bg-white/[0.03]">
                                <td class="px-6 py-5 text-sm font-semibold text-slate-300">{{ $loop->iteration }}</td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-red-600 to-red-700 text-white shadow-lg">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-white">{{ $p->user->name }}</p>
                                            <p class="mt-1 text-sm text-slate-400">{{ $p->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center rounded-full border border-yellow-400/20 bg-yellow-400/10 px-3 py-2 font-mono text-sm font-semibold text-yellow-300">
                                        {{ $p->nomor_peserta }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-sm text-slate-300">
                                    <div class="space-y-1">
                                        <p class="flex items-center gap-2"><i class="bi bi-envelope text-slate-500"></i>{{ $p->user->email }}</p>
                                        <p class="flex items-center gap-2"><i class="bi bi-telephone text-slate-500"></i>{{ $p->no_hp }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-2 text-sm font-medium text-emerald-300">
                                        <i class="bi bi-building"></i>
                                        {{ $p->instansi ?: 'Belum diisi' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.peserta.edit', $p->id) }}" class="admin-btn admin-btn-ghost admin-btn-sm">
                                            <i class="bi bi-pencil-square text-yellow-300"></i>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.peserta.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin hapus peserta ini?')">
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
        @endif
    </section>
</div>
@endsection




