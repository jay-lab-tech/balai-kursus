@extends('layouts.admin')

@section('title', 'Manajemen Instruktur')

@section('page-title', 'Manajemen Instruktur')

@section('page-description', 'Kelola akun instruktur, spesialisasi, dan akses pembaruan data pengajar secara lebih terstruktur.')

@section('content')
<div class="space-y-8">
    <section class="admin-panel overflow-hidden rounded-[2rem] p-6 sm:p-8">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-sky-500/30 bg-sky-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
                    <i class="bi bi-person-badge-fill text-sky-400"></i>
                    Tim Instruktur
                </div>
                <h1 class="mt-5 text-3xl font-bold text-white sm:text-4xl">Kelola profil pengajar dan spesialisasi keahlian dalam satu direktori.</h1>
                <p class="mt-4 max-w-3xl text-base leading-7 text-slate-300">
                    Halaman ini memudahkan admin menjaga data instruktur tetap konsisten, termasuk akun login, identitas pengajar, dan area spesialisasi masing-masing.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.instruktur.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-600 to-sky-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-sky-500 hover:to-sky-600">
                    <i class="bi bi-person-plus-fill"></i>
                    Tambah Instruktur
                </a>
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <div class="rounded-[1.5rem] bg-gradient-to-br from-sky-600 to-sky-700 p-5 text-white shadow-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-100">Total Instruktur</p>
                <p class="mt-3 text-4xl font-bold">{{ $instrukturs->count() }}</p>
                <p class="mt-2 text-sm text-sky-100/90">Seluruh instruktur yang terdaftar dalam sistem.</p>
            </div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5 md:col-span-1 xl:col-span-2">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Fokus Halaman</p>
                <p class="mt-3 text-xl font-bold text-white">Identitas pengajar dan spesialisasi aktif</p>
                <p class="mt-2 text-sm text-slate-300">Gunakan halaman ini untuk memastikan instruktur yang mengajar punya akun dan data spesialisasi yang lengkap.</p>
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
                    <i class="bi bi-person-lines-fill mr-3 text-yellow-300"></i>Daftar Instruktur
                </h2>
                <p class="mt-2 text-slate-400">Tabel instruktur memuat nama pengajar, akun email, spesialisasi, dan aksi pengelolaan data.</p>
            </div>
            <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-300">
                {{ $instrukturs->count() }} instruktur ditemukan
            </span>
        </div>

        @if($instrukturs->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white/5 text-4xl text-yellow-300">
                    <i class="bi bi-person-badge"></i>
                </div>
                <h3 class="mt-6 text-2xl font-bold text-white">Belum ada instruktur</h3>
                <p class="mt-3 text-slate-400">Tambahkan instruktur baru untuk mulai menghubungkan pengajar dengan kelas program.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">No</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Instruktur</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Email</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Spesialisasi</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($instrukturs as $i)
                            <tr class="transition hover:bg-white/[0.03]">
                                <td class="px-6 py-5 text-sm font-semibold text-slate-300">{{ $loop->iteration }}</td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-600 to-sky-700 text-white shadow-lg">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-white">{{ $i->nama_instr }}</p>
                                            <p class="mt-1 text-sm text-slate-400">Akun terhubung ke {{ $i->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $i->user->email }}</td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center gap-2 rounded-full border border-yellow-400/20 bg-yellow-400/10 px-3 py-2 text-sm font-medium text-yellow-300">
                                        <i class="bi bi-star-fill"></i>
                                        {{ $i->spesialisasi }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.instruktur.edit', $i->id) }}" class="admin-btn admin-btn-ghost admin-btn-sm">
                                            <i class="bi bi-pencil-square text-yellow-300"></i>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.instruktur.destroy', $i->id) }}" method="POST" onsubmit="return confirm('Yakin hapus instruktur ini?')">
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


