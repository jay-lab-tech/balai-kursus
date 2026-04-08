@extends('layouts.admin')

@section('title', 'Tambah Lokasi')

@section('page-title', 'Tambah Lokasi')

@section('content')
<div class="space-y-6">
    <section class="admin-panel admin-panel--hero">
        <span class="admin-eyebrow"><i class="bi bi-plus-square"></i>Form Lokasi</span>
        <div class="space-y-3">
            <h2 class="text-3xl font-semibold tracking-tight text-white">Tambahkan lokasi operasional baru.</h2>
            <p class="max-w-2xl text-sm leading-6 text-slate-300">Isi informasi alamat dan kontak secara lengkap agar penjadwalan kelas mudah dipetakan ke lokasi yang tepat.</p>
        </div>
    </section>

    <section class="admin-panel max-w-4xl">
        <div class="admin-panel__header"><div><h3 class="admin-panel__title">Informasi Lokasi</h3><p class="admin-panel__subtitle">Data ini akan membantu admin saat mengelola ruang dan jadwal kursus.</p></div></div>
        <form method="POST" action="{{ route('admin.lokasi.store') }}" class="space-y-6">
            @csrf
            <div class="space-y-2"><label class="admin-label">Nama Lokasi</label><input type="text" name="nama" class="admin-input @error('nama') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required value="{{ old('nama') }}">@error('nama') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            <div class="space-y-2"><label class="admin-label">Alamat</label><textarea name="alamat" class="admin-input min-h-[110px] @error('alamat') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" rows="3" required>{{ old('alamat') }}</textarea>@error('alamat') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2"><label class="admin-label">Kota</label><input type="text" name="kota" class="admin-input @error('kota') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required value="{{ old('kota') }}">@error('kota') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
                <div class="space-y-2"><label class="admin-label">Provinsi</label><input type="text" name="provinsi" class="admin-input @error('provinsi') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required value="{{ old('provinsi') }}">@error('provinsi') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            </div>
            <div class="space-y-2"><label class="admin-label">No Telp</label><input type="text" name="no_telp" class="admin-input @error('no_telp') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required value="{{ old('no_telp') }}">@error('no_telp') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            <div class="space-y-2"><label class="admin-label">Keterangan</label><textarea name="keterangan" class="admin-input min-h-[110px] @error('keterangan') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" rows="3">{{ old('keterangan') }}</textarea>@error('keterangan') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            <div class="flex flex-wrap gap-3"><button type="submit" class="admin-btn admin-btn-primary"><i class="bi bi-check2-circle"></i>Simpan Lokasi</button><a href="{{ route('admin.lokasi.index') }}" class="admin-btn admin-btn-secondary"><i class="bi bi-arrow-left"></i>Batal</a></div>
        </form>
    </section>
</div>
@endsection
