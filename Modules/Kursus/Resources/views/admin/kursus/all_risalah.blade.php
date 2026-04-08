@extends('layouts.admin')

@section('title', 'Semua Risalah')

@section('page-title', 'Semua Risalah')

@section('page-description', 'Pantau seluruh risalah pertemuan, instruktur pengajar, dan materi yang sudah tercatat.')

@section('content')
<div class="space-y-8">
    <section class="admin-panel overflow-hidden rounded-[2rem] p-6 sm:p-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
            <i class="bi bi-journal-richtext text-red-400"></i>
            Dokumentasi Belajar
        </div>
        <h1 class="mt-5 text-3xl font-bold text-white">Semua risalah pertemuan dalam satu layar.</h1>
        <p class="mt-3 max-w-3xl text-base leading-7 text-slate-300">Gunakan halaman ini untuk meninjau materi yang diajarkan, pengajar yang bertugas, dan konsistensi dokumentasi setiap pertemuan kelas.</p>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <div class="rounded-[1.5rem] bg-gradient-to-br from-red-600 to-red-700 p-5 text-white shadow-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-red-100">Total Risalah</p>
                <p class="mt-3 text-4xl font-bold">{{ $risalahs->count() }}</p>
                <p class="mt-2 text-sm text-red-100/90">Jumlah seluruh risalah yang sudah tercatat.</p>
            </div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5 md:col-span-2">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Fokus Halaman</p>
                <p class="mt-3 text-xl font-bold text-white">Kursus, instruktur, dan materi tiap pertemuan</p>
                <p class="mt-2 text-sm text-slate-300">Data di halaman ini membantu admin menilai kelengkapan risalah belajar di seluruh kelas.</p>
            </div>
        </div>
    </section>

    <section class="admin-panel overflow-hidden rounded-[2rem]">
        <div class="flex flex-col gap-3 border-b border-white/10 px-6 py-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">
                    <i class="bi bi-journal-bookmark-fill mr-3 text-yellow-300"></i>Daftar Risalah
                </h2>
                <p class="mt-2 text-slate-400">Menampilkan kursus, pertemuan, tanggal, instruktur, dan ringkasan materi yang diajarkan.</p>
            </div>
            <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-300">
                {{ $risalahs->count() }} risalah ditemukan
            </span>
        </div>

        @if($risalahs->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white/5 text-4xl text-yellow-300">
                    <i class="bi bi-journal-x"></i>
                </div>
                <h3 class="mt-6 text-2xl font-bold text-white">Belum ada risalah</h3>
                <p class="mt-3 text-slate-400">Risalah akan muncul di sini setelah jadwal dan pertemuan mulai terdokumentasi.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Kursus</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Pertemuan</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Instruktur</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Materi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($risalahs as $r)
                            <tr class="transition hover:bg-white/[0.03]">
                                <td class="px-6 py-5 text-sm font-semibold text-white">{{ $r->kursus->nama }}</td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $r->pertemuan_ke }}</td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $r->tgl_pertemuan->format('d M Y') }}</td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $r->instruktur->nama_instr }}</td>
                                <td class="px-6 py-5 text-sm text-slate-300">{{ $r->materi }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
@endsection

