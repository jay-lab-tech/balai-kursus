@extends('layouts.admin')

@section('title', 'Daftar Nilai Peserta')

@section('page-title', 'Daftar Nilai Peserta')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:justify-between lg:items-center">
        <h2 class="text-2xl font-bold text-gray-900"><i class="bi bi-list me-2"></i>Daftar Nilai Peserta</h2>
        <div class="flex flex-col gap-3 xl:flex-row xl:items-center">
            <a href="{{ route('admin.score.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                <i class="bi bi-download me-2"></i>Export Nilai
            </a>
            <form method="GET" action="{{ route('admin.score.index') }}" class="flex gap-2">
                <div class="flex">
                    <input type="search" name="q" class="px-3 py-2 border border-gray-300 rounded-l-md text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Cari peserta, kursus..." value="{{ old('q', $q ?? '') }}">
                    <button class="px-4 py-2 bg-gray-600 text-white border border-gray-600 rounded-r-md hover:bg-gray-700 text-sm" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <div class="relative inline-block">
                <button class="px-4 py-2 bg-gray-600 text-white border border-gray-600 rounded-md hover:bg-gray-700 text-sm flex items-center gap-2" type="button" id="sortDropdown" onclick="toggleSort()" onblur="setTimeout(() => document.getElementById('sortMenu').classList.add('hidden'), 200)">
                    Sortir <i class="bi bi-chevron-down"></i>
                </button>
                <div id="sortMenu" class="hidden absolute right-0 mt-1 bg-white border border-gray-200 rounded-md shadow-lg z-10">
                    <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('admin.score.index', array_merge(request()->all(), ['sort_by' => 'final_score', 'sort_dir' => 'desc'])) }}">Nilai Tertinggi</a>
                    <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('admin.score.index', array_merge(request()->all(), ['sort_by' => 'final_score', 'sort_dir' => 'asc'])) }}">Nilai Terendah</a>
                    <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('admin.score.index', array_merge(request()->all(), ['sort_by' => 'status', 'sort_dir' => 'desc'])) }}">Status</a>
                </div>
            </div>

            <a href="{{ route('admin.score.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                <i class="bi bi-plus-circle me-2"></i>Tambah Nilai
            </a>
        </div>
    </div>

    @if($scores->count())
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kursus</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Listening</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Speaking</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Reading</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Writing</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Assignment</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Final Score</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Evaluator</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($scores as $score)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $score->pendaftaran->peserta->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $score->pendaftaran->kursus->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">{{ $score->listening }}</span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">{{ $score->speaking }}</span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">{{ $score->reading }}</span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">{{ $score->writing }}</span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">{{ $score->assignment }}</span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $score->final_score >= 70 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $score->final_score }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    @if($score->status == 'pass')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Lulus</span>
                                    @elseif($score->status == 'fail')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Gagal</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $score->evaluator->nama_instr ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.score.show', $score->id) }}" class="text-cyan-600 hover:text-cyan-900 inline-flex items-center">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($scores->hasPages())
            <div class="bg-white border-t border-gray-200 px-4 py-5 sm:px-6">
                {{ $scores->links() }}
            </div>
            @endif
        </div>
    @endif
</div>

<script>
function toggleSort() {
    document.getElementById('sortMenu').classList.toggle('hidden');
}
</script>
@endsection
