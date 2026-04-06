@extends('layouts.admin')

@section('title', 'Edit Level')

@section('page-title', 'Edit Level')

@section('content')
<div class="max-w-3xl">
    <div class="rounded-2xl bg-white p-6 shadow">
        <form method="POST" action="{{ route('admin.level.update', $level) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Level</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $level->nama) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="urutan" class="block text-sm font-medium text-gray-700">Urutan</label>
                    <input type="number" id="urutan" name="urutan" value="{{ old('urutan', $level->urutan) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="nilai_min" class="block text-sm font-medium text-gray-700">Nilai Minimum</label>
                    <input type="number" step="0.01" id="nilai_min" name="nilai_min" value="{{ old('nilai_min', $level->nilai_min) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="nilai_max" class="block text-sm font-medium text-gray-700">Nilai Maksimum</label>
                    <input type="number" step="0.01" id="nilai_max" name="nilai_max" value="{{ old('nilai_max', $level->nilai_max) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
            </div>

            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('deskripsi', $level->deskripsi) }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">Update</button>
                <a href="{{ route('admin.level.index') }}" class="rounded-xl bg-gray-200 px-5 py-3 font-semibold text-gray-800 hover:bg-gray-300">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
