@extends('layouts.admin')

@section('title', 'Manajemen Level')

@section('page-title', 'Manajemen Level')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Daftar Level</h2>
            <p class="text-sm text-gray-500">Rentang nilai di bawah dipakai sistem untuk menentukan level hasil tes penempatan.</p>
        </div>
        <a href="{{ route('admin.level.create') }}" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Tambah Level</a>
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
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Urutan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Rentang Nilai</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($level as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->urutan }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $item->nama }}</div>
                                <div class="text-xs text-gray-500">{{ $item->deskripsi }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->rentang_nilai }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex gap-3">
                                    <a href="{{ route('admin.level.edit', $item) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                                    <form action="{{ route('admin.level.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus level ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
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
