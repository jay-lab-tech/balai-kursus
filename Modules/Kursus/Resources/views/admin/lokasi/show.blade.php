@extends('layouts.admin')

@section('title', 'Detail Lokasi')

@section('page-title', 'Detail Lokasi')

@section('content')
<div class="space-y-6">
    <div class="flex gap-3">
        <a href="{{ route('admin.lokasi.edit', $lokasi->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700">Edit</a>
        <a href="{{ route('admin.lokasi.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Kembali</a>
    </div>
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ $lokasi->nama }}</h1>
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-medium text-gray-700">Alamat</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $lokasi->alamat }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Kota</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $lokasi->kota }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Provinsi</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $lokasi->provinsi }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">No Telp</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $lokasi->no_telp }}</p>
                </div>
                @if($lokasi->keterangan)
                    <div>
                        <p class="text-sm font-medium text-gray-700">Keterangan</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $lokasi->keterangan }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
