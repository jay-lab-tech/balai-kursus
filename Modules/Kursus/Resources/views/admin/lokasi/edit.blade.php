@extends('layouts.admin')

@section('title', 'Edit Lokasi')

@section('page-title', 'Edit Lokasi')

@section('content')
<div class="space-y-6">
    <section class="admin-panel admin-panel--hero">
        <span class="admin-eyebrow"><i class="bi bi-pencil-square"></i>Form Lokasi</span>
        <div class="space-y-3">
            <h2 class="text-3xl font-semibold tracking-tight text-white">Perbarui data lokasi {{ $lokasi->nama }}.</h2>
            <p class="max-w-2xl text-sm leading-6 text-slate-300">Pastikan alamat dan kontak lokasi tetap mutakhir agar penjadwalan dan operasional kelas tidak terganggu.</p>
        </div>
    </section>

    <section class="admin-panel max-w-4xl">
        <div class="admin-panel__header"><div><h3 class="admin-panel__title">Edit Informasi Lokasi</h3><p class="admin-panel__subtitle">Perubahan akan langsung dipakai oleh modul jadwal, kelas, dan operasional lainnya.</p></div></div>
        <form method="POST" action="{{ route('admin.lokasi.update', $lokasi->id) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="space-y-2"><label class="admin-label">Nama Lokasi</label><input type="text" name="nama" class="admin-input @error('nama') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required value="{{ old('nama', $lokasi->nama) }}">@error('nama') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            <div class="space-y-2"><label class="admin-label">Alamat</label><textarea name="alamat" class="admin-input min-h-[110px] @error('alamat') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" rows="3" required>{{ old('alamat', $lokasi->alamat) }}</textarea>@error('alamat') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2"><label class="admin-label">Kota</label><input type="text" name="kota" class="admin-input @error('kota') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required value="{{ old('kota', $lokasi->kota) }}">@error('kota') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
                <div class="space-y-2"><label class="admin-label">Provinsi</label><input type="text" name="provinsi" class="admin-input @error('provinsi') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required value="{{ old('provinsi', $lokasi->provinsi) }}">@error('provinsi') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            </div>
            <div class="space-y-2"><label class="admin-label">No Telp</label><input type="text" name="no_telp" class="admin-input @error('no_telp') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" required value="{{ old('no_telp', $lokasi->no_telp) }}">@error('no_telp') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            <div class="space-y-2"><label class="admin-label">Keterangan</label><textarea name="keterangan" class="admin-input min-h-[110px] @error('keterangan') border-rose-500/70 focus:border-rose-400 focus:ring-rose-500/30 @enderror" rows="3">{{ old('keterangan', $lokasi->keterangan) }}</textarea>@error('keterangan') <p class="admin-field-error">{{ $message }}</p> @enderror</div>
            <div class="flex flex-wrap gap-3"><button type="submit" class="admin-btn admin-btn-primary"><i class="bi bi-save2"></i>Update Lokasi</button><a href="{{ route('admin.lokasi.index') }}" class="admin-btn admin-btn-secondary"><i class="bi bi-arrow-left"></i>Batal</a></div>
        </form>
    </section>
</div>
@endsection
