@extends('layouts.admin')

@section('title', 'Edit Hari')

@section('page-title', 'Edit Hari')

@section('content')
<div class="space-y-6">
    <div class="max-w-2xl">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Hari</h2>
                <form method="POST" action="{{ route('admin.hari.update', $hari->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Hari</label>
                            <input type="text" name="nama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('nama') border-red-500 @enderror" required value="{{ old('nama', $hari->nama) }}">
                            @error('nama') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Urutan (1-7)</label>
                            <input type="number" name="urutan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('urutan') border-red-500 @enderror" min="1" max="7" required value="{{ old('urutan', $hari->urutan) }}">
                            @error('urutan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Update</button>
                            <a href="{{ route('admin.hari.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
