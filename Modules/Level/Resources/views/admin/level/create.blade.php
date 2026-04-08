@extends('layouts.admin')

@section('title', 'Tambah Level')

@section('page-title', 'Tambah Level')

@section('content')
<div class="space-y-6">
    <section class="admin-panel admin-panel--hero">
        <span class="admin-eyebrow">
            <i class="bi bi-plus-square"></i>
            Form Level
        </span>
        <div class="space-y-3">
            <h2 class="text-3xl font-semibold tracking-tight text-white">Tambahkan jenjang level baru.</h2>
            <p class="max-w-2xl text-sm leading-6 text-slate-300">
                Tentukan urutan dan rentang nilai agar sistem penempatan dapat memetakan hasil tes dengan akurat.
            </p>
        </div>
    </section>

    @if($errors->any())
        <div class="admin-alert admin-alert-danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Form belum lengkap.</strong>
                <div class="mt-1 text-sm text-rose-100">{{ $errors->first() }}</div>
            </div>
        </div>
    @endif

    <section class="admin-panel max-w-4xl">
        <div class="admin-panel__header">
            <div>
                <h3 class="admin-panel__title">Informasi Level</h3>
                <p class="admin-panel__subtitle">Gunakan urutan konsisten dan pastikan rentang nilai tidak tumpang tindih.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.level.store') }}" class="space-y-6">
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="nama" class="admin-label">Nama Level</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="admin-input @error('nama') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required>
                    @error('nama')
                        <p class="admin-field-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-2">
                    <label for="urutan" class="admin-label">Urutan</label>
                    <input type="number" id="urutan" name="urutan" value="{{ old('urutan', 1) }}" class="admin-input @error('urutan') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required>
                    @error('urutan')
                        <p class="admin-field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="nilai_min" class="admin-label">Nilai Minimum</label>
                    <input type="number" step="0.01" id="nilai_min" name="nilai_min" value="{{ old('nilai_min') }}" class="admin-input @error('nilai_min') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required>
                    @error('nilai_min')
                        <p class="admin-field-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-2">
                    <label for="nilai_max" class="admin-label">Nilai Maksimum</label>
                    <input type="number" step="0.01" id="nilai_max" name="nilai_max" value="{{ old('nilai_max') }}" class="admin-input @error('nilai_max') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required>
                    @error('nilai_max')
                        <p class="admin-field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label for="deskripsi" class="admin-label">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" class="admin-input min-h-[140px] @error('deskripsi') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="admin-field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="bi bi-check2-circle"></i>
                    Simpan Level
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
