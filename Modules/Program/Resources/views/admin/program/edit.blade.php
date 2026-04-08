@extends('layouts.admin')

@section('title', 'Edit Program')

@section('page-title', 'Edit Program')

@section('content')
<div class="space-y-6">
    <section class="admin-panel admin-panel--hero">
        <span class="admin-eyebrow">
            <i class="bi bi-pencil-square"></i>
            Form Master Program
        </span>
        <div class="space-y-3">
            <h2 class="text-3xl font-semibold tracking-tight text-white">Perbarui identitas program {{ $program->nama }}.</h2>
            <p class="max-w-2xl text-sm leading-6 text-slate-300">
                Sesuaikan nama atau warna program agar tetap sinkron dengan struktur akademik dan tampilan administrasi.
            </p>
        </div>
    </section>

    @if($errors->any())
        <div class="admin-alert admin-alert-danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Perubahan belum bisa disimpan.</strong>
                <div class="mt-1 text-sm text-rose-100">{{ $errors->first() }}</div>
            </div>
        </div>
    @endif

    <section class="admin-panel max-w-3xl">
        <div class="admin-panel__header">
            <div>
                <h3 class="admin-panel__title">Edit Informasi Program</h3>
                <p class="admin-panel__subtitle">Perbarui nama program dan identitas warnanya tanpa mengubah relasi kursus yang sudah ada.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.program.update', $program) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-[1.5fr_0.8fr]">
                <div class="space-y-2">
                    <label for="nama" class="admin-label">Nama Program</label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        value="{{ old('nama', $program->nama) }}"
                        class="admin-input @error('nama') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror"
                        required
                    >
                    @error('nama')
                        <p class="admin-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="warna" class="admin-label">Warna Program</label>
                    <input
                        type="color"
                        id="warna"
                        name="warna"
                        value="{{ old('warna', $program->warna) }}"
                        class="h-14 w-full rounded-2xl border border-white/10 bg-slate-950/70 p-2"
                    >
                    @error('warna')
                        <p class="admin-field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="rounded-3xl border border-white/10 bg-slate-950/40 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Preview identitas</p>
                <div class="mt-4 flex items-center gap-4">
                    <span class="h-14 w-14 rounded-2xl border border-white/10 shadow-inner" style="background-color: {{ old('warna', $program->warna) }}"></span>
                    <div>
                        <div class="text-sm font-semibold text-white">{{ old('nama', $program->nama) }}</div>
                        <div class="text-xs text-slate-400">Perubahan akan langsung memengaruhi tampilan label program.</div>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-save2"></i>
                    Update Program
                </button>
                <a href="{{ route('admin.program.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </form>
    </section>
</div>
@endsection
