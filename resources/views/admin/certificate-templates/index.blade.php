@extends('layouts.admin')

@section('title', 'Template Sertifikat')

@section('page-title', 'Template Sertifikat')

@section('page-description', 'Atur template resmi, aset visual, dan penandatangan sertifikat yang dipakai sistem.')

@section('content')
<div class="space-y-8">
    <section class="admin-panel overflow-hidden rounded-[2rem] p-6 sm:p-8">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-sky-500/30 bg-sky-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
                    <i class="bi bi-easel2-fill text-sky-400"></i>
                    Template Sertifikat
                </div>
                <h1 class="mt-5 text-3xl font-bold text-white sm:text-4xl">Kelola tampilan resmi sertifikat.</h1>
                <p class="mt-4 max-w-3xl text-base leading-7 text-slate-300">Template menentukan aset logo, background, tanda tangan, cap, nama penandatangan, dan format prefix nomor sertifikat yang dipakai saat PDF digenerate.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.templates.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-600 to-sky-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-sky-500 hover:to-sky-600">
                    <i class="bi bi-plus-circle"></i>
                    Buat Template Baru
                </a>
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
        <div class="border-b border-white/10 px-6 py-5">
            <h2 class="text-2xl font-bold text-white">
                <i class="bi bi-card-image mr-3 text-yellow-300"></i>Daftar Template
            </h2>
            <p class="mt-2 text-slate-400">Pastikan selalu ada satu template aktif yang menjadi sumber resmi untuk penerbitan sertifikat peserta.</p>
        </div>

        @if($templates->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white/5 text-4xl text-yellow-300">
                    <i class="bi bi-card-image"></i>
                </div>
                <h3 class="mt-6 text-2xl font-bold text-white">Belum ada template</h3>
                <p class="mt-3 text-slate-400">Buat template pertama untuk mulai menerbitkan sertifikat resmi.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Template</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Penandatangan</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Prefix</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Digunakan</th>
                            <th class="px-4 py-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($templates as $template)
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-white">{{ $template->name }}</span>
                                            @if($template->is_active)
                                                <span class="rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-emerald-300">Aktif</span>
                                            @endif
                                        </div>
                                        <span class="text-sm text-slate-300">{{ $template->institution_name }}</span>
                                        <span class="text-xs text-slate-400">{{ $template->unit_name }} • {{ $template->city }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="font-medium text-white">{{ $template->signer_name }}</span>
                                        <span class="text-sm text-slate-300">{{ $template->signer_title }}</span>
                                        <span class="text-xs text-slate-400">NIP {{ $template->signer_nip }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-300">{{ $template->certificate_prefix }}</td>
                                <td class="px-4 py-4 text-sm text-slate-300">{{ $template->certificates_count }} sertifikat</td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.templates.edit', $template) }}" class="admin-btn admin-btn-ghost admin-btn-sm">
                                            <i class="bi bi-pencil-square text-yellow-300"></i>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.templates.destroy', $template) }}" onsubmit="return confirm('Hapus template ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-btn admin-btn-sm border border-sky-500/20 bg-sky-600/10 text-sky-200 hover:bg-sky-600/20">
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
                {{ $templates->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
