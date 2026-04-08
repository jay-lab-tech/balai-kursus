@extends('layouts.admin')

@section('title', 'Edit Hari')

@section('page-title', 'Edit Hari')

@section('content')
<div class="space-y-6">
    <section class="admin-panel admin-panel--hero">
        <span class="admin-eyebrow"><i class="bi bi-pencil-square"></i>Form Hari</span>
        <div class="space-y-3">
            <h2 class="text-3xl font-semibold tracking-tight text-white">Perbarui data hari {{ $hari->nama }}.</h2>
            <p class="max-w-2xl text-sm leading-6 text-slate-300">Jaga konsistensi nama dan urutan hari agar tidak mengganggu pilihan pada form jadwal.</p>
        </div>
    </section>

    <section class="admin-panel max-w-3xl">
        <div class="admin-panel__header"><div><h3 class="admin-panel__title">Edit Informasi Hari</h3><p class="admin-panel__subtitle">Perubahan akan langsung tercermin pada referensi penjadwalan admin.</p></div></div>
        <form method="POST" action="{{ route('admin.hari.update', $hari->id) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="space-y-2"><label class="admin-label">Nama Hari</label><input type="text" name="nama" class="admin-input @error('nama') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required value="{{ old('nama', $hari->nama) }}">@error('nama') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            <div class="space-y-2"><label class="admin-label">Urutan (1-7)</label><input type="number" name="urutan" class="admin-input @error('urutan') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" min="1" max="7" required value="{{ old('urutan', $hari->urutan) }}">@error('urutan') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            <div class="flex flex-wrap gap-3"><button type="submit" class="admin-btn admin-btn-primary"><i class="bi bi-save2"></i>Update Hari</button><a href="{{ route('admin.hari.index') }}" class="admin-btn admin-btn-secondary"><i class="bi bi-arrow-left"></i>Batal</a></div>
        </form>
    </section>
</div>
@endsection
