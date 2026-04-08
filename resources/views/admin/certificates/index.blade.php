@extends('layouts.admin')

@section('title', 'Manajemen Sertifikat')

@section('page-title', 'Manajemen Sertifikat')

@section('page-description', 'Kelola sertifikat peserta, status publish, dan relasi kursus dari satu panel admin.')

@section('content')
<div class="space-y-8">
    <section class="admin-panel overflow-hidden rounded-[2rem] p-6 sm:p-8">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
                    <i class="bi bi-patch-check-fill text-red-400"></i>
                    Sertifikat Peserta
                </div>
                <h1 class="mt-5 text-3xl font-bold text-white sm:text-4xl">Kelola penerbitan sertifikat kursus peserta.</h1>
                <p class="mt-4 max-w-3xl text-base leading-7 text-slate-300">Halaman ini membantu admin membuat, memperbarui, dan mempublikasikan sertifikat peserta berdasarkan kursus yang sudah selesai atau siap diterbitkan.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.certificates.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-red-600 to-red-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-red-500 hover:to-red-600">
                    <i class="bi bi-plus-circle"></i>
                    Tambah Sertifikat
                </a>
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <div class="rounded-[1.5rem] bg-gradient-to-br from-red-600 to-red-700 p-5 text-white shadow-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-red-100">Total Sertifikat</p>
                <p class="mt-3 text-4xl font-bold">{{ $certificates->count() }}</p>
                <p class="mt-2 text-sm text-red-100/90">Jumlah seluruh sertifikat yang sudah dibuat.</p>
            </div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Published</p>
                <p class="mt-3 text-3xl font-bold text-white">{{ $certificates->where('status', 'published')->count() }}</p>
                <p class="mt-2 text-sm text-slate-300">Sertifikat yang sudah bisa diakses peserta.</p>
            </div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Pending</p>
                <p class="mt-3 text-3xl font-bold text-white">{{ $certificates->where('status', 'pending')->count() }}</p>
                <p class="mt-2 text-sm text-slate-300">Sertifikat yang masih menunggu proses publish.</p>
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
                    <i class="bi bi-award-fill mr-3 text-yellow-300"></i>Daftar Sertifikat
                </h2>
                <p class="mt-2 text-slate-400">Menampilkan nama sertifikat, kursus, peserta, tanggal dibuat, status publish, dan tindakan admin.</p>
            </div>
            <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-300">
                {{ $certificates->count() }} sertifikat ditemukan
            </span>
        </div>

        @if($certificates->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white/5 text-4xl text-yellow-300">
                    <i class="bi bi-patch-exclamation"></i>
                </div>
                <h3 class="mt-6 text-2xl font-bold text-white">Belum ada sertifikat</h3>
                <p class="mt-3 text-slate-400">Buat sertifikat baru agar peserta yang memenuhi syarat bisa menerima sertifikatnya.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Sertifikat</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Kursus</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Peserta</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Dibuat</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Status</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($certificates as $certificate)
                            <tr class="transition hover:bg-white/[0.03]">
                                <td class="px-4 py-4">
                                    <span class="font-bold text-white">{{ $certificate->certificate_name }}</span>
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-300">{{ $certificate->course->nama ?? '-' }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-white">{{ $certificate->participant->user->name ?? '-' }}</span>
                                        <span class="text-xs text-slate-400">{{ $certificate->participant->user->email ?? '-' }}</span>
                                        <span class="mt-2 inline-flex w-fit rounded-full border border-yellow-400/20 bg-yellow-400/10 px-2.5 py-1 text-xs font-medium text-yellow-300">{{ $certificate->participant->nomor_peserta ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-xs text-slate-400">{{ $certificate->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-4">
                                    @if($certificate->status == 'pending')
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="rounded-full border border-yellow-400/20 bg-yellow-400/10 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-yellow-300">Pending</span>
                                            <form action="{{ route('admin.certificates.publish', $certificate->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="admin-btn admin-btn-sm" style="background:linear-gradient(135deg,#059669,#10b981);color:#fff;">Publish</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-300">Published</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <a href="{{ route('admin.certificates.edit', $certificate->id) }}" class="admin-btn admin-btn-ghost admin-btn-sm">
                                        <i class="bi bi-pencil-square text-yellow-300"></i>
                                        Edit
                                    </a>
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


