@extends('layouts.admin')

@section('title', 'Jadwal Kursus')

@section('page-title', 'Jadwal Kursus')

@section('page-description', 'Kelola jadwal pertemuan untuk kelas yang dipilih, termasuk waktu, lokasi, ruang, dan urutan pertemuan.')

@section('content')
<div class="space-y-8">
    <section class="admin-panel overflow-hidden rounded-[2rem] p-6 sm:p-8">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
                    <i class="bi bi-calendar-week-fill text-red-400"></i>
                    Jadwal Per Kelas
                </div>
                <h1 class="mt-5 text-3xl font-bold text-white sm:text-4xl">{{ $kursus->nama }}</h1>
                <p class="mt-3 text-base text-slate-300">{{ $kursus->program->nama ?? '-' }} • {{ $kursus->level->nama ?? '-' }}</p>
                <p class="mt-4 max-w-3xl text-base leading-7 text-slate-300">Susun seluruh pertemuan untuk kelas ini, mulai dari tanggal, jam, lokasi, sampai ruang kelas yang dipakai.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.jadwal.create', $kursus->id) }}" class="admin-btn admin-btn-primary"><i class="bi bi-plus-circle"></i>Tambah Jadwal</a>
                <a href="{{ route('admin.kursus.index') }}" class="admin-btn admin-btn-secondary"><i class="bi bi-arrow-left"></i>Kembali ke Kelas</a>
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <article class="admin-stat-card"><span class="admin-stat-card__label">Total Pertemuan</span><div class="admin-stat-card__value">{{ $jadwals->total() }}</div><p class="admin-stat-card__hint">Jumlah jadwal yang sudah disusun untuk kelas ini.</p></article>
            <article class="admin-stat-card"><span class="admin-stat-card__label">Halaman Aktif</span><div class="admin-stat-card__value">{{ $jadwals->currentPage() }}</div><p class="admin-stat-card__hint">Navigasi daftar jadwal per halaman.</p></article>
            <article class="admin-stat-card"><span class="admin-stat-card__label">Per Halaman</span><div class="admin-stat-card__value">{{ $jadwals->perPage() }}</div><p class="admin-stat-card__hint">Jumlah pertemuan yang dimuat pada halaman ini.</p></article>
        </div>
    </section>

    <section class="admin-panel overflow-hidden rounded-[2rem]">
        <div class="flex flex-col gap-3 border-b border-white/10 px-6 py-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white"><i class="bi bi-calendar3 mr-3 text-yellow-300"></i>Daftar Jadwal Kelas</h2>
                <p class="mt-2 text-slate-400">Tinjau tanggal, jam, lokasi, ruang, dan aksi pengelolaan setiap pertemuan.</p>
            </div>
            <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-300">{{ $jadwals->total() }} jadwal ditemukan</span>
        </div>

        @if($jadwals->isEmpty())
            <div class="admin-empty-state">
                <div class="admin-empty-state__icon"><i class="bi bi-calendar-x"></i></div>
                <h3>Belum ada jadwal</h3>
                <p>Buat jadwal pertama untuk mulai mengatur rangkaian pertemuan pada kelas ini.</p>
                <a href="{{ route('admin.jadwal.create', $kursus->id) }}" class="admin-btn admin-btn-primary"><i class="bi bi-plus-circle"></i>Tambah Jadwal</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr><th>Pertemuan</th><th>Tanggal</th><th>Waktu</th><th>Lokasi</th><th>Kelas</th><th>Hari</th><th class="text-right">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($jadwals as $jadwal)
                            <tr>
                                <td>{{ $jadwal->pertemuan_ke ?? '-' }}</td>
                                <td>{{ $jadwal->tgl_pertemuan->format('d M Y') }}</td>
                                <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                                <td>{{ $jadwal->lokasi->nama ?? '-' }}</td>
                                <td>{{ $jadwal->kela->nama ?? '-' }}</td>
                                <td>{{ $jadwal->hari->nama ?? '-' }}</td>
                                <td><div class="flex justify-end gap-2"><a href="{{ route('admin.jadwal.edit', [$kursus->id, $jadwal->id]) }}" class="admin-btn admin-btn-ghost admin-btn-sm"><i class="bi bi-pencil-square"></i>Edit</a><form action="{{ route('admin.jadwal.destroy', [$kursus->id, $jadwal->id]) }}" method="POST" onsubmit="return confirm('Hapus jadwal?')">@csrf @method('DELETE')<button type="submit" class="admin-btn admin-btn-danger admin-btn-sm"><i class="bi bi-trash3"></i>Hapus</button></form></div></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($jadwals->hasPages())<div class="border-t border-white/10 px-4 py-5 sm:px-6">{{ $jadwals->links() }}</div>@endif
        @endif
    </section>
</div>
@endsection
