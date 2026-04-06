@extends('layouts.admin')

@section('title', 'Manajemen Kelas')

@section('page-title', 'Manajemen Kelas')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Daftar Kelas</h2>
            <p class="text-sm text-gray-500">Setiap kelas terhubung ke satu program dan satu level. Sistem akan memilih kelas ini otomatis saat placement peserta dilakukan.</p>
        </div>
        <a href="{{ route('admin.kursus.create') }}" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Tambah Kelas</a>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl bg-white shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Kelas</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Program</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Level</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Periode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Kuota</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($kursus as $kelas)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $kelas->nama }}</div>
                                <div class="text-xs text-gray-500">Rp {{ number_format($kelas->harga, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $kelas->program->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $kelas->level->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $kelas->periode ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $kelas->pendaftarans_count }} / {{ $kelas->kuota }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ ucfirst($kelas->status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex gap-3">
                                    <a href="{{ route('admin.kursus.edit', $kelas) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                                    <a href="{{ route('admin.kursus.peserta', $kelas) }}" class="text-blue-600 hover:text-blue-800">Peserta</a>
                                    <form action="{{ route('admin.kursus.destroy', $kelas) }}" method="POST" onsubmit="return confirm('Hapus kelas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">Belum ada kelas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-200 px-6 py-4">
            {{ $kursus->links() }}
        </div>
    </div>
</div>
@endsection
