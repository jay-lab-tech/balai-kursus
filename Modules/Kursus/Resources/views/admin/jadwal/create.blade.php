@extends('layouts.admin')

@section('title', 'Tambah Jadwal')

@section('page-title', 'Tambah Jadwal')

@section('page-description', 'Buat pertemuan baru untuk kelas ' . $kursus->nama . ' dengan lokasi, ruang, dan waktu yang jelas.')

@section('content')
<div class="space-y-8 max-w-5xl">
    <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
            <i class="bi bi-calendar-plus-fill text-red-400"></i>
            Jadwal Baru
        </div>
        <h1 class="mt-5 text-3xl font-bold text-white">Buat jadwal baru untuk kelas <span class="text-yellow-300">{{ $kursus->nama }}</span>.</h1>
        <p class="mt-3 max-w-3xl text-base leading-7 text-slate-300">Tentukan tanggal, jam, lokasi, ruang, dan hari pertemuan agar jadwal kelas bisa digunakan pada operasional belajar dan sinkronisasi risalah.</p>
    </section>

    @if($errors->any())
        <div class="rounded-[1.5rem] border border-red-500/20 bg-red-600/10 px-5 py-4 text-red-100 shadow-lg">
            <div class="flex items-start gap-3">
                <i class="bi bi-exclamation-octagon-fill mt-0.5 text-lg text-red-300"></i>
                <div>
                    <p class="font-semibold">Form belum bisa disimpan</p>
                    <ul class="mt-2 space-y-1 text-sm text-red-100/90">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.jadwal.store', $kursus) }}" method="POST" class="space-y-6">
        @csrf

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Detail Pertemuan</h2>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Pertemuan Ke</label>
                    <input type="number" name="pertemuan_ke" value="{{ old('pertemuan_ke') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Tanggal Pertemuan</label>
                    <input type="date" name="tgl_pertemuan" value="{{ old('tgl_pertemuan') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
            </div>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10">
                </div>
            </div>
        </section>

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Lokasi dan Ruang</h2>
            <div class="mt-6 grid gap-6 md:grid-cols-3">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Lokasi</label>
                    <select name="lokasi_id" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                        <option value="">Pilih Lokasi</option>
                        @foreach($lokasis as $lokasi)
                            <option value="{{ $lokasi->id }}" @selected(old('lokasi_id') == $lokasi->id)>{{ $lokasi->nama }} - {{ $lokasi->kota }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Kelas</label>
                    <select name="kela_id" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" @selected(old('kela_id') == $k->id)>{{ $k->nama }} ({{ $k->kapasitas ?? '-' }} kursi)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Hari</label>
                    <select name="hari_id" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                        <option value="">Pilih Hari</option>
                        @foreach($haris as $hari)
                            <option value="{{ $hari->id }}" @selected(old('hari_id') == $hari->id)>{{ $hari->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-red-600 to-red-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-red-500 hover:to-red-600">
                <i class="bi bi-check-circle-fill"></i>
                Simpan Jadwal
            </button>
            <a href="{{ route('admin.jadwal.index', $kursus) }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection
