@extends('layouts.admin')

@section('title', 'Tes Penempatan')

@section('page-title', 'Tes Penempatan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Antrian Tes Penempatan</h2>
            <p class="text-sm text-gray-500">Admin menginput hasil tes, lalu sistem otomatis menentukan level dan kelas yang kuotanya masih tersedia.</p>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row">
            <form method="GET" action="{{ route('admin.score.index') }}" class="flex gap-2">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari peserta, program, nomor..." class="w-72 rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <button type="submit" class="rounded-xl bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">Cari</button>
            </form>
            <a href="{{ route('admin.score.export') }}" class="rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">Export</a>
            <a href="{{ route('admin.score.create') }}" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Input Hasil Tes</a>
        </div>
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
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Pendaftaran</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Peserta</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Program</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Nilai Tes</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Level</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Kelas</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($pendaftarans as $pendaftaran)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $pendaftaran->nomor }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $pendaftaran->peserta->user->name ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $pendaftaran->peserta->user->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $pendaftaran->program->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $pendaftaran->placementScore?->final_score ?? 'Belum diinput' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $pendaftaran->level->nama ?? 'Belum ditentukan' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $pendaftaran->kursus->nama ?? 'Belum ditempatkan' }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                    {{ str_replace('_', ' ', ucfirst($pendaftaran->status_pendaftaran)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex gap-3">
                                    @if($pendaftaran->placementScore)
                                        <a href="{{ route('admin.score.show', $pendaftaran->placementScore) }}" class="text-blue-600 hover:text-blue-800">Detail</a>
                                        <a href="{{ route('admin.score.edit', $pendaftaran->placementScore) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                                    @else
                                        <a href="{{ route('admin.score.create', ['pendaftaran_id' => $pendaftaran->id]) }}" class="text-green-600 hover:text-green-800">Input Tes</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500">
                                Belum ada data pendaftaran program.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-200 px-6 py-4">
            {{ $pendaftarans->links() }}
        </div>
    </div>
</div>
@endsection
