@extends('layouts.admin')

@section('title', 'Tambah Kelas')

@section('page-title', 'Tambah Kelas')

@section('content')
<div class="space-y-6">
    <section class="admin-panel admin-panel--hero">
        <span class="admin-eyebrow"><i class="bi bi-plus-square"></i>Form Kelas</span>
        <div class="space-y-3">
            <h2 class="text-3xl font-semibold tracking-tight text-white">Tambahkan ruang kelas baru.</h2>
            <p class="max-w-2xl text-sm leading-6 text-slate-300">Atur nama ruang, kapasitas, serta fasilitas utama agar admin mudah memilih kelas yang paling sesuai.</p>
        </div>
    </section>

    <section class="admin-panel max-w-4xl">
        <div class="admin-panel__header"><div><h3 class="admin-panel__title">Informasi Kelas</h3><p class="admin-panel__subtitle">Pastikan kapasitas realistis dan fasilitas menggambarkan kondisi ruang sebenarnya.</p></div></div>
        <form method="POST" action="{{ route('admin.kelas.store') }}" class="space-y-6">
            @csrf
            <div class="space-y-2"><label class="admin-label">Nama Kelas</label><input type="text" name="nama" class="admin-input @error('nama') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required value="{{ old('nama') }}">@error('nama') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            <div class="space-y-2"><label class="admin-label">Kapasitas (orang)</label><input type="number" name="kapasitas" class="admin-input @error('kapasitas') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" min="1" required value="{{ old('kapasitas') }}">@error('kapasitas') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            <div class="space-y-2"><label class="admin-label">Fasilitas</label><input type="text" name="fasilitas" class="admin-input @error('fasilitas') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required value="{{ old('fasilitas') }}" placeholder="Contoh: Proyektor, AC, Whiteboard">@error('fasilitas') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            <div class="space-y-2"><label class="admin-label">Keterangan</label><textarea name="keterangan" class="admin-input min-h-[110px] @error('keterangan') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" rows="3">{{ old('keterangan') }}</textarea>@error('keterangan') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            <div class="flex flex-wrap gap-3"><button type="submit" class="admin-btn admin-btn-primary"><i class="bi bi-check2-circle"></i>Simpan Kelas</button><a href="{{ route('admin.kelas.index') }}" class="admin-btn admin-btn-secondary"><i class="bi bi-arrow-left"></i>Batal</a></div>
        </form>
    </section>
</div>
@endsection
