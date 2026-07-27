@extends('layouts.admin')

@section('title', 'Tambah Kelas')

@section('page-title', 'Tambah Kelas')

@section('page-description', 'Buat kelas program baru lengkap dengan periode, harga, kuota, dan status operasional.')

@section('content')
<div class="space-y-8 max-w-5xl">
    <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-sky-500/30 bg-sky-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
            <i class="bi bi-plus-circle-fill text-sky-400"></i>
            Form Kelas Baru
        </div>
        <h1 class="mt-5 text-3xl font-bold text-white">Siapkan kelas program baru untuk penempatan peserta.</h1>
        <p class="mt-3 max-w-3xl text-base leading-7 text-slate-300">Lengkapi informasi dasar kelas, hubungkan dengan program dan level, lalu tentukan harga, kuota, dan status agar kelas siap digunakan oleh admin operasional.</p>
    </section>

    @if($errors->any())
        <div class="rounded-[1.5rem] border border-sky-500/20 bg-sky-600/10 px-5 py-4 text-sky-100 shadow-lg">
            <div class="flex items-start gap-3">
                <i class="bi bi-exclamation-octagon-fill mt-0.5 text-lg text-sky-300"></i>
                <div>
                    <p class="font-semibold">Form belum bisa disimpan</p>
                    <ul class="mt-2 space-y-1 text-sm text-sky-100/90">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.kursus.store') }}" class="space-y-6">
        @csrf

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Informasi Dasar</h2>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <label for="nama" class="mb-2 block text-sm font-medium text-slate-300">Nama Kelas</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white placeholder:text-slate-500 focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
                <div>
                    <label for="periode" class="mb-2 block text-sm font-medium text-slate-300">Periode</label>
                    <input type="text" id="periode" name="periode" value="{{ old('periode') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white placeholder:text-slate-500 focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10">
                </div>
            </div>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <label for="program_id" class="mb-2 block text-sm font-medium text-slate-300">Program</label>
                    <select id="program_id" name="program_id" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                        <option value="">Pilih program</option>
                        @foreach($program as $item)
                            <option value="{{ $item->id }}" {{ (string) old('program_id') === (string) $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="level_id" class="mb-2 block text-sm font-medium text-slate-300">Level</label>
                    <select id="level_id" name="level_id" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                        <option value="">Pilih level</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ (string) old('level_id') === (string) $level->id ? 'selected' : '' }}>{{ $level->nama }} ({{ $level->rentang_nilai }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Jadwal dan Biaya</h2>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <label for="tanggal_mulai" class="mb-2 block text-sm font-medium text-slate-300">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
                <div>
                    <label for="tanggal_selesai" class="mb-2 block text-sm font-medium text-slate-300">Tanggal Selesai</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
            </div>
            <div class="mt-6 grid gap-6 md:grid-cols-3">
                <div>
                    <label for="harga" class="mb-2 block text-sm font-medium text-slate-300">Harga</label>
                    <input type="number" id="harga" name="harga" value="{{ old('harga') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
                <div>
                    <label for="harga_upi" class="mb-2 block text-sm font-medium text-slate-300">Harga UPI</label>
                    <input type="number" id="harga_upi" name="harga_upi" value="{{ old('harga_upi') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10">
                </div>
                <div>
                    <label for="kuota" class="mb-2 block text-sm font-medium text-slate-300">Kuota</label>
                    <input type="number" id="kuota" name="kuota" value="{{ old('kuota') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
            </div>
            <div class="mt-6">
                <label for="status" class="mb-2 block text-sm font-medium text-slate-300">Status</label>
                <select id="status" name="status" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                    <option value="buka" {{ old('status') === 'buka' ? 'selected' : '' }}>Buka</option>
                    <option value="tutup" {{ old('status') === 'tutup' ? 'selected' : '' }}>Tutup</option>
                    <option value="berjalan" {{ old('status') === 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                </select>
            </div>
        </section>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-600 to-sky-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-sky-500 hover:to-sky-600">
                <i class="bi bi-check-circle-fill"></i>
                Simpan Kelas
            </button>
            <a href="{{ route('admin.kursus.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection
