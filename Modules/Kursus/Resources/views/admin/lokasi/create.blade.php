@extends('layouts.admin')

@section('title', 'Tambah Lokasi')

@section('page-title', 'Tambah Lokasi')

@section('content')
<div class="space-y-6">
    <div class="max-w-2xl">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Tambah Lokasi</h2>
                <form method="POST" action="{{ route('admin.lokasi.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Lokasi</label>
                            <input type="text" name="nama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('nama') border-red-500 @enderror" required value="{{ old('nama') }}">
                            @error('nama') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea name="alamat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('alamat') border-red-500 @enderror" rows="2" required>{{ old('alamat') }}</textarea>
                            @error('alamat') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kota</label>
                                <input type="text" name="kota" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('kota') border-red-500 @enderror" required value="{{ old('kota') }}">
                                @error('kota') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Provinsi</label>
                                <input type="text" name="provinsi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('provinsi') border-red-500 @enderror" required value="{{ old('provinsi') }}">
                                @error('provinsi') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No Telp</label>
                            <input type="text" name="no_telp" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('no_telp') border-red-500 @enderror" required value="{{ old('no_telp') }}">
                            @error('no_telp') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea name="keterangan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" rows="2">{{ old('keterangan') }}</textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Simpan</button>
                            <a href="{{ route('admin.lokasi.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
