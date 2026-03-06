@extends('layouts.admin')

@section('title', 'Master Kelas')

@section('page-title', 'Master Kelas')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900"><i class="bi bi-door-closed me-2"></i>Master Kelas</h2>
        <a href="{{ route('admin.kelas.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
            <i class="bi bi-plus-circle me-2"></i>Tambah Kelas
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($kelas->isEmpty())
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded m-4">Belum ada kelas</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fasilitas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($kelas as $key => $k)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $kelas->firstItem() + $key }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $k->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">{{ $k->kapasitas }} orang</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $k->fasilitas }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2 flex">
                                    <a href="{{ route('admin.kelas.show', $k->id) }}" class="text-cyan-600 hover:text-cyan-900">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.kelas.edit', $k->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($kelas->hasPages())
            <div class="bg-white border-t border-gray-200 px-4 py-5 sm:px-6">
                {{ $kelas->links() }}
            </div>
            @endif
        @endif
    </div>
</div>
@endsection
