@extends('layouts.admin')

@section('title', 'Semua Jadwal')

@section('page-title', 'Semua Jadwal')

@section('page-description', 'Pantau seluruh pertemuan kelas program, lokasi, dan waktu pelaksanaan dalam satu daftar global.')

@section('content')
<div class="space-y-8">
    <section class="admin-panel overflow-hidden rounded-[2rem] p-6 sm:p-8">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-sky-500/30 bg-sky-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
                    <i class="bi bi-calendar-week-fill text-sky-400"></i>
                    Agenda Global
                </div>
                <h1 class="mt-5 text-3xl font-bold text-white sm:text-4xl">Pantau seluruh jadwal kelas dari satu papan operasional.</h1>
                <p class="mt-4 max-w-3xl text-base leading-7 text-slate-300">
                    Halaman ini menggabungkan seluruh pertemuan kelas program agar admin lebih mudah meninjau tanggal, jam, lokasi, dan kelas tanpa membuka satu program satu per satu.
                </p>
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
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-100">Total Jadwal</p>
                <p class="mt-3 text-4xl font-bold">{{ $jadwals->total() }}</p>
                <p class="mt-2 text-sm text-sky-100/90">Jumlah seluruh pertemuan yang sudah tersimpan.</p>
            </div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Halaman Saat Ini</p>
                <p class="mt-3 text-3xl font-bold text-white">{{ $jadwals->currentPage() }}</p>
                <p class="mt-2 text-sm text-slate-300">Navigasi per halaman untuk jadwal yang lebih mudah discan.</p>
            </div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Per Halaman</p>
                <p class="mt-3 text-3xl font-bold text-white">{{ $jadwals->perPage() }}</p>
                <p class="mt-2 text-sm text-slate-300">Jumlah jadwal yang dimuat setiap halaman.</p>
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
                    <i class="bi bi-calendar3 mr-3 text-yellow-300"></i>Daftar Jadwal Kelas
                </h2>
                <p class="mt-2 text-slate-400">Menampilkan semua jadwal lintas kelas program beserta lokasi, ruang, dan waktu pelaksanaan.</p>
            </div>
            <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-300">
                {{ $jadwals->total() }} jadwal ditemukan
            </span>
        </div>

        @if($jadwals->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white/5 text-4xl text-yellow-300">
                    <i class="bi bi-calendar-x"></i>
                </div>
                <h3 class="mt-6 text-2xl font-bold text-white">Belum ada jadwal tersimpan</h3>
                <p class="mt-3 text-slate-400">Buat jadwal dari halaman kelas program agar pertemuan mulai tercatat di sini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Kursus</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Pertemuan</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Waktu</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Lokasi</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Kelas</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwals as $jadwal)
                            <tr class="transition hover:bg-white/[0.03]">
                                <td class="px-6 py-5">
                                    <p class="font-semibold text-white">{{ $jadwal->kursus->nama ?? '-' }}</p>
                                    <p class="mt-1 text-sm text-slate-400">{{ $jadwal->kursus->program->nama ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-5 text-sm font-semibold text-slate-200">{{ $jadwal->pertemuan_ke ?? '-' }}</td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $jadwal->tgl_pertemuan?->format('d M Y') }}</td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $jadwal->jam_mulai ?? '-' }} - {{ $jadwal->jam_selesai ?? '-' }}</td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $jadwal->lokasi->nama ?? '-' }}</td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $jadwal->kela->nama ?? '-' }}</td>
                                <td class="px-6 py-5">
                                    <a href="{{ route('admin.jadwal.edit', [$jadwal->kursus_id, $jadwal->id]) }}" class="admin-btn admin-btn-ghost admin-btn-sm">
                                        <i class="bi bi-pencil-square text-yellow-300"></i>
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($jadwals->hasPages())
                <div class="border-t border-white/10 px-6 py-4">
                    {{ $jadwals->links() }}
                </div>
            @endif
        @endif
    </section>
</div>
@endsection


