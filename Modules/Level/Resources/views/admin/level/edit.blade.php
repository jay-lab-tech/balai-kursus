@extends('layouts.admin')

@section('title', 'Edit Level')

@section('page-title', 'Edit Level')

@section('content')
<div class="space-y-6">
    <section class="admin-panel admin-panel--hero">
        <span class="admin-eyebrow">
            <i class="bi bi-pencil-square"></i>
            Form Level
        </span>
        <div class="space-y-3">
            <h2 class="text-3xl font-semibold tracking-tight text-white">Perbarui level {{ $level->nama }}.</h2>
            <p class="max-w-2xl text-sm leading-6 text-slate-300">
                Pastikan rentang nilai tetap konsisten agar hasil tes penempatan tidak bentrok dengan level lain.
            </p>
        </div>
    </section>

    @if($errors->any())
        <div class="admin-alert admin-alert-danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Perubahan belum dapat disimpan.</strong>
                <div class="mt-1 text-sm text-rose-100">{{ $errors->first() }}</div>
            </div>
        </div>
    @endif

    <section class="admin-panel max-w-4xl">
        <div class="admin-panel__header">
            <div>
                <h3 class="admin-panel__title">Edit Informasi Level</h3>
                <p class="admin-panel__subtitle">Perbarui nama, urutan, serta rentang nilai tanpa mengubah alur kerja admin.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.level.update', $level) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="nama" class="admin-label">Nama Level</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $level->nama) }}" class="admin-input @error('nama') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required>
                    @error('nama')
                        <p class="admin-field-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-2">
                    <label for="urutan" class="admin-label">Urutan</label>
                    <input type="number" id="urutan" name="urutan" value="{{ old('urutan', $level->urutan) }}" class="admin-input @error('urutan') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required>
                    @error('urutan')
                        <p class="admin-field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="nilai_min" class="admin-label">Nilai Minimum</label>
                    <input type="number" step="0.01" id="nilai_min" name="nilai_min" value="{{ old('nilai_min', $level->nilai_min) }}" class="admin-input @error('nilai_min') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required>
                    @error('nilai_min')
                        <p class="admin-field-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-2">
                    <label for="nilai_max" class="admin-label">Nilai Maksimum</label>
                    <input type="number" step="0.01" id="nilai_max" name="nilai_max" value="{{ old('nilai_max', $level->nilai_max) }}" class="admin-input @error('nilai_max') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required>
                    @error('nilai_max')
                        <p class="admin-field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label for="deskripsi" class="admin-label">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" class="admin-input min-h-[140px] @error('deskripsi') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror">{{ old('deskripsi', $level->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="admin-field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-save2"></i>
                    Update Level
                </button>
                <a href="{{ route('admin.level.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </form>
    </section>
</div>
@endsection
