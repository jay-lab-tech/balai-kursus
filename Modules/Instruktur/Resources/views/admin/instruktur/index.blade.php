@extends('layouts.admin')

@section('title', 'Manajemen Instruktur')

@section('page-title', 'Manajemen Instruktur')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900"><i class="bi bi-person-badge me-2"></i>Manajemen Instruktur</h2>
        <a href="{{ route('admin.instruktur.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"><i class="bi bi-plus-circle me-2"></i>Tambah Instruktur</a>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
                            <div class="flex items-center">
                                <i class="bi bi-hash mr-2 text-gray-400"></i>
                                No
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
                            <div class="flex items-center">
                                <i class="bi bi-person-badge mr-2 text-gray-400"></i>
                                Nama Instruktur
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
                            <div class="flex items-center">
                                <i class="bi bi-envelope mr-2 text-gray-400"></i>
                                Email
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
                            <div class="flex items-center">
                                <i class="bi bi-star mr-2 text-gray-400"></i>
                                Spesialisasi
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
                            <div class="flex items-center">
                                <i class="bi bi-gear mr-2 text-gray-400"></i>
                                Aksi
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($instrukturs as $i)
                    <tr class="hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 transition-all duration-200 hover:shadow-sm">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                                        <i class="bi bi-person-fill text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $i->nama_instr }}</div>
                                    <div class="text-sm text-gray-500">{{ $i->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                <i class="bi bi-envelope text-gray-400 mr-2"></i>
                                {{ $i->user->email }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 border border-orange-200">
                                <i class="bi bi-star-fill mr-1"></i>
                                {{ $i->spesialisasi }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.instruktur.edit', $i->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-150">
                                    <i class="bi bi-pencil-square mr-1"></i>
                                    Edit
                                </a>
                                <form action="{{ route('admin.instruktur.destroy', $i->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin hapus instruktur ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-150">
                                        <i class="bi bi-trash mr-1"></i>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
