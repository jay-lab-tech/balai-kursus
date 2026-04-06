@extends('layouts.admin')

@section('title', 'Semua Jadwal')

@section('page-title', 'Semua Jadwal')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Semua Jadwal Kelas</h2>
            <p class="text-sm text-gray-500">Daftar seluruh pertemuan dari kelas program yang sudah dibuat admin.</p>
        </div>
        <a href="{{ route('admin.kursus.index') }}" class="inline-flex items-center rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
            <i class="bi bi-arrow-left mr-2"></i>Kembali ke Kelas Program
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl bg-white p-5 shadow">
            <p class="text-xs uppercase tracking-[0.2em] text-gray-400">Total Jadwal</p>
            <p class="mt-3 text-3xl font-bold text-gray-900">{{ $jadwals->total() }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow">
            <p class="text-xs uppercase tracking-[0.2em] text-gray-400">Halaman Saat Ini</p>
            <p class="mt-3 text-3xl font-bold text-gray-900">{{ $jadwals->currentPage() }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow">
            <p class="text-xs uppercase tracking-[0.2em] text-gray-400">Per Halaman</p>
            <p class="mt-3 text-3xl font-bold text-gray-900">{{ $jadwals->perPage() }}</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Kursus</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Pertemuan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Waktu</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Lokasi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Kelas</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white text-sm text-gray-700">
                    @forelse($jadwals as $jadwal)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $jadwal->kursus->nama ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $jadwal->kursus->program->nama ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $jadwal->pertemuan_ke ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $jadwal->tgl_pertemuan?->format('d M Y') }}</td>
                            <td class="px-6 py-4">{{ $jadwal->jam_mulai ?? '-' }} - {{ $jadwal->jam_selesai ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $jadwal->lokasi->nama ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $jadwal->kela->nama ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.jadwal.edit', [$jadwal->kursus_id, $jadwal->id]) }}" class="inline-flex items-center rounded-lg bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700 hover:bg-amber-100">
                                    <i class="bi bi-pencil-square mr-2"></i>Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">Belum ada jadwal yang tersimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($jadwals->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $jadwals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
