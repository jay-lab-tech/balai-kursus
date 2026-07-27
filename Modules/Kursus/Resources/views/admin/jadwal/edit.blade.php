@extends('layouts.admin')

@section('title', 'Edit Jadwal')

@section('page-title', 'Edit Jadwal')

@section('page-description', 'Perbarui detail pertemuan untuk kelas ' . $kursus->nama . ' agar jadwal tetap akurat.')

@section('content')
<div class="space-y-8 max-w-5xl">
    <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-sky-500/30 bg-sky-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
            <i class="bi bi-pencil-square text-sky-400"></i>
            Update Jadwal
        </div>
        <h1 class="mt-5 text-3xl font-bold text-white">Perbarui jadwal untuk kelas <span class="text-yellow-300">{{ $kursus->nama }}</span>.</h1>
        <p class="mt-3 max-w-3xl text-base leading-7 text-slate-300">Sesuaikan pertemuan, tanggal, waktu, dan lokasi jika ada perubahan operasional agar kelas tetap sinkron dengan risalah dan absensi.</p>
    </section>

    @if($errors->any())
        <div class="rounded-[1.5rem] border border-sky-500/20 bg-sky-600/10 px-5 py-4 text-sky-100 shadow-lg">
            <div class="flex items-start gap-3">
                <i class="bi bi-exclamation-octagon-fill mt-0.5 text-lg text-sky-300"></i>
                <div>
                    <p class="font-semibold">Perubahan belum bisa disimpan</p>
                    <ul class="mt-2 space-y-1 text-sm text-sky-100/90">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.jadwal.update', [$kursus, $jadwal]) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Detail Pertemuan</h2>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <label class="admin-label mb-2">Pertemuan Ke</label>
                    <input type="number" name="pertemuan_ke" value="{{ old('pertemuan_ke', $jadwal->pertemuan_ke) }}" class="admin-input">
                </div>
                <div>
                    <label class="admin-label mb-2">Tanggal Pertemuan</label>
                    <input type="date" name="tgl_pertemuan" value="{{ old('tgl_pertemuan', $jadwal->tgl_pertemuan?->format('Y-m-d')) }}" class="admin-input" required>
                </div>
            </div>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <label class="admin-label mb-2">Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $jadwal->jam_mulai) }}" class="admin-input">
                </div>
                <div>
                    <label class="admin-label mb-2">Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $jadwal->jam_selesai) }}" class="admin-input">
                </div>
            </div>
        </section>

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Lokasi dan Ruang</h2>
            <div class="mt-6 grid gap-6 md:grid-cols-3">
                <div>
                    <label class="admin-label mb-2">Lokasi</label>
                    <select name="lokasi_id" class="admin-input" required>
                        <option value="">Pilih Lokasi</option>
                        @foreach($lokasis as $lokasi)
                            <option value="{{ $lokasi->id }}" @selected(old('lokasi_id', $jadwal->lokasi_id) == $lokasi->id)>{{ $lokasi->nama }} - {{ $lokasi->kota }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="admin-label mb-2">Kelas</label>
                    <select name="kela_id" class="admin-input" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" @selected(old('kela_id', $jadwal->kela_id) == $k->id)>{{ $k->nama }} ({{ $k->kapasitas ?? '-' }} kursi)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="admin-label mb-2">Hari</label>
                    <select name="hari_id" class="admin-input" required>
                        <option value="">Pilih Hari</option>
                        @foreach($haris as $hari)
                            <option value="{{ $hari->id }}" @selected(old('hari_id', $jadwal->hari_id) == $hari->id)>{{ $hari->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-600 to-sky-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-sky-500 hover:to-sky-600">
                <i class="bi bi-check-circle-fill"></i>
                Update Jadwal
            </button>
            <a href="{{ route('admin.jadwal.index', $kursus) }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection
