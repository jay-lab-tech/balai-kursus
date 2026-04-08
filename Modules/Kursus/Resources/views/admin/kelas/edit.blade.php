@extends('layouts.admin')

@section('title', 'Edit Kelas')

@section('page-title', 'Edit Kelas')

@section('content')
<div class="space-y-6">
    <section class="admin-panel admin-panel--hero">
        <span class="admin-eyebrow"><i class="bi bi-pencil-square"></i>Form Kelas</span>
        <div class="space-y-3">
            <h2 class="text-3xl font-semibold tracking-tight text-white">Perbarui ruang kelas {{ $kela->nama }}.</h2>
            <p class="max-w-2xl text-sm leading-6 text-slate-300">Sesuaikan kapasitas dan fasilitas agar data ruang kelas tetap akurat untuk kebutuhan penjadwalan.</p>
        </div>
    </section>

    <section class="admin-panel max-w-4xl">
        <div class="admin-panel__header"><div><h3 class="admin-panel__title">Edit Informasi Kelas</h3><p class="admin-panel__subtitle">Perubahan akan langsung dipakai oleh jadwal, penempatan, dan pengelolaan operasional.</p></div></div>
        <form method="POST" action="{{ route('admin.kelas.update', $kela->id) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="space-y-2"><label class="admin-label">Nama Kelas</label><input type="text" name="nama" class="admin-input @error('nama') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required value="{{ old('nama', $kela->nama) }}">@error('nama') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            <div class="space-y-2"><label class="admin-label">Kapasitas (orang)</label><input type="number" name="kapasitas" class="admin-input @error('kapasitas') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" min="1" required value="{{ old('kapasitas', $kela->kapasitas) }}">@error('kapasitas') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            <div class="space-y-2"><label class="admin-label">Fasilitas</label><input type="text" name="fasilitas" class="admin-input @error('fasilitas') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required value="{{ old('fasilitas', $kela->fasilitas) }}">@error('fasilitas') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            <div class="space-y-2"><label class="admin-label">Keterangan</label><textarea name="keterangan" class="admin-input min-h-[110px] @error('keterangan') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" rows="3">{{ old('keterangan', $kela->keterangan) }}</textarea>@error('keterangan') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            <div class="flex flex-wrap gap-3"><button type="submit" class="admin-btn admin-btn-primary"><i class="bi bi-save2"></i>Update Kelas</button><a href="{{ route('admin.kelas.index') }}" class="admin-btn admin-btn-secondary"><i class="bi bi-arrow-left"></i>Batal</a></div>
        </form>
    </section>
</div>
@endsection
