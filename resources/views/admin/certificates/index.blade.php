@extends('layouts.admin')

@section('title', 'Manajemen Sertifikat')

@section('page-title', 'Manajemen Sertifikat')

@section('page-description', 'Kelola draft, publish, dan nomor sertifikat resmi peserta dari satu panel admin.')

@section('content')
<div class="space-y-8">
    <section class="admin-panel overflow-hidden rounded-[2rem] p-6 sm:p-8">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-sky-500/30 bg-sky-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
                    <i class="bi bi-award-fill text-sky-400"></i>
                    Sertifikat Resmi
                </div>
                <h1 class="mt-5 text-3xl font-bold text-white sm:text-4xl">Kelola penerbitan sertifikat dengan template resmi.</h1>
                <p class="mt-4 max-w-3xl text-base leading-7 text-slate-300">Setiap sertifikat sekarang mengikuti template aktif, menarik data peserta dan kursus secara otomatis, lalu diterbitkan melalui alur draft ke publish.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.templates.index') }}" class="admin-btn admin-btn-ghost">
                    <i class="bi bi-layout-text-window-reverse text-yellow-300"></i>
                    Template Sertifikat
                </a>
                <a href="{{ route('admin.certificates.batch.create') }}" class="admin-btn admin-btn-ghost">
                    <i class="bi bi-collection text-yellow-300"></i>
                    Generate Batch
                </a>
                <a href="{{ route('admin.certificates.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-600 to-sky-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-sky-500 hover:to-sky-600">
                    <i class="bi bi-plus-circle"></i>
                    Buat Draft Sertifikat
                </a>
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-4">
            <div class="rounded-[1.5rem] bg-gradient-to-br from-sky-600 to-sky-700 p-5 text-white shadow-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-100">Total Sertifikat</p>
                <p class="mt-3 text-4xl font-bold">{{ $certificates->count() }}</p>
                <p class="mt-2 text-sm text-sky-100/90">Seluruh entri sertifikat yang pernah dibuat admin.</p>
            </div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Draft</p>
                <p class="mt-3 text-3xl font-bold text-white">{{ $certificates->where('status', \App\Models\Certificate::STATUS_DRAFT)->count() }}</p>
                <p class="mt-2 text-sm text-slate-300">Masih menunggu review dan publish.</p>
            </div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Published</p>
                <p class="mt-3 text-3xl font-bold text-white">{{ $certificates->where('status', \App\Models\Certificate::STATUS_PUBLISHED)->count() }}</p>
                <p class="mt-2 text-sm text-slate-300">Sudah bisa diunduh peserta.</p>
            </div>
            <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Template Aktif</p>
                @php($activeTemplate = $certificates->firstWhere('template.is_active', true)?->template ?? \App\Models\CertificateTemplate::active()->first())
                <p class="mt-3 text-lg font-bold text-white">{{ $activeTemplate?->name ?? 'Belum ada' }}</p>
                <p class="mt-2 text-sm text-slate-300">Template resmi yang dipakai untuk generate PDF.</p>
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
                    <i class="bi bi-patch-check-fill mr-3 text-yellow-300"></i>Daftar Sertifikat
                </h2>
                <p class="mt-2 text-slate-400">Pantau nomor sertifikat, peserta, tanggal terbit, dan status publikasi dari satu daftar yang rapi.</p>
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
                <p class="mt-3 text-slate-400">Mulai dengan membuat draft sertifikat pertama berdasarkan kursus yang sudah siap diterbitkan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Peserta</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Program & Kursus</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Nomor Sertifikat</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Terbit</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Status</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($certificates as $certificate)
                            <tr class="transition hover:bg-white/[0.03]">
                                <td class="px-4 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-white">{{ $certificate->participant_name_snapshot ?: ($certificate->participant->user->name ?? '-') }}</span>
                                        <span class="text-xs text-slate-400">{{ $certificate->participant->user->email ?? '-' }}</span>
                                        <span class="mt-2 inline-flex w-fit rounded-full border border-yellow-400/20 bg-yellow-400/10 px-2.5 py-1 text-xs font-medium text-yellow-300">{{ $certificate->participant->nomor_peserta ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="font-semibold text-white">{{ $certificate->program_name_snapshot ?: '-' }}</span>
                                        <span class="text-sm text-slate-300">{{ $certificate->course_name_snapshot ?: ($certificate->course->nama ?? '-') }}</span>
                                        <span class="text-xs text-slate-400">{{ $certificate->hours_snapshot ? $certificate->hours_snapshot . ' Jam Pelajaran' : 'Jam pelajaran belum diisi' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="font-semibold text-white">{{ $certificate->certificate_number ?: '-' }}</span>
                                        <span class="text-xs text-slate-400">Nomor: {{ $certificate->serial_number ?: '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-col gap-1 text-sm">
                                        <span class="font-medium text-white">{{ optional($certificate->issued_date)->translatedFormat('d F Y') ?? '-' }}</span>
                                        <span class="text-xs text-slate-400">Template: {{ $certificate->template->name ?? 'Belum tersambung' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    @if($certificate->status === \App\Models\Certificate::STATUS_DRAFT)
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="rounded-full border border-yellow-400/20 bg-yellow-400/10 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-yellow-300">Draft</span>
                                            <form action="{{ route('admin.certificates.publish', $certificate->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="admin-btn admin-btn-sm" style="background:linear-gradient(135deg,#059669,#10b981);color:#fff;">Publish</button>
                                            </form>
                                        </div>
                                    @elseif($certificate->status === \App\Models\Certificate::STATUS_REVOKED)
                                        <span class="rounded-full border border-sky-400/20 bg-sky-500/10 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Revoked</span>
                                    @else
                                        <span class="rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-300">Published</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.certificates.preview', $certificate->id) }}" target="_blank" class="admin-btn admin-btn-ghost admin-btn-sm">
                                            <i class="bi bi-eye text-yellow-300"></i>
                                            Preview
                                        </a>
                                        <a href="{{ route('admin.certificates.edit', $certificate->id) }}" class="admin-btn admin-btn-ghost admin-btn-sm">
                                            <i class="bi bi-pencil-square text-yellow-300"></i>
                                            Edit
                                        </a>
                                        @if($certificate->status === \App\Models\Certificate::STATUS_PUBLISHED)
                                            <form action="{{ route('admin.certificates.revoke', $certificate->id) }}" method="POST" class="inline-flex">
                                                @csrf
                                                <button type="submit" class="admin-btn admin-btn-sm border border-sky-500/20 bg-sky-600/10 text-sky-200 hover:bg-sky-600/20">
                                                    <i class="bi bi-x-octagon"></i>
                                                    Revoke
                                                </button>
                                            </form>
                                        @elseif($certificate->status === \App\Models\Certificate::STATUS_REVOKED)
                                            <form action="{{ route('admin.certificates.restore-draft', $certificate->id) }}" method="POST" class="inline-flex">
                                                @csrf
                                                <button type="submit" class="admin-btn admin-btn-sm border border-yellow-400/20 bg-yellow-400/10 text-yellow-200 hover:bg-yellow-400/20">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                    Restore Draft
                                                </button>
                                            </form>
                                        @endif
                                        @if($certificate->status === \App\Models\Certificate::STATUS_PUBLISHED)
                                            <span class="inline-flex items-center gap-2 rounded-xl border border-emerald-400/20 bg-emerald-500/10 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-300">
                                                <i class="bi bi-download"></i>
                                                Tersedia untuk peserta
                                            </span>
                                        @endif
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
