@extends('layouts.admin')

@section('title', 'Master Lokasi')

@section('page-title', 'Master Lokasi')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900"><i class="bi bi-geo-alt me-2"></i>Master Lokasi</h2>
        <a href="{{ route('admin.lokasi.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
            <i class="bi bi-plus-circle me-2"></i>Tambah Lokasi
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($lokasis->isEmpty())
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded m-4">Belum ada lokasi</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kota</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Telp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($lokasis as $key => $lokasi)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $lokasis->firstItem() + $key }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $lokasi->nama }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $lokasi->alamat }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lokasi->kota }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lokasi->no_telp }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.lokasi.show', $lokasi->id) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.lokasi.edit', $lokasi->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.lokasi.destroy', $lokasi->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="bg-white border-t border-gray-200 px-4 py-5 sm:px-6">
                {{ $lokasis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
