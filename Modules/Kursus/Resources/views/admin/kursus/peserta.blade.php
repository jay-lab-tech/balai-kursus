@extends('layouts.admin')

@section('title', 'Peserta Kelas')

@section('page-title', 'Peserta Kelas')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">{{ $kursus->nama }}</h2>
        <p class="text-sm text-gray-500">{{ $kursus->program->nama ?? '-' }} | {{ $kursus->level->nama ?? '-' }}</p>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Peserta</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Level</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Nilai Tes</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($peserta as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $item->peserta->user->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->peserta->user->email ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->level->nama ?? 'Belum ada level' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->placementScore?->final_score ?? 'Belum ada nilai tes' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ str_replace('_', ' ', ucfirst($item->status_pendaftaran)) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">Belum ada peserta pada kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-200 px-6 py-4">
            {{ $peserta->links() }}
        </div>
    </div>
</div>
@endsection
