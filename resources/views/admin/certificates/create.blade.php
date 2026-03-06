@extends('layouts.admin')

@section('title', 'Terbitkan Sertifikat Baru')

@section('page-title', 'Terbitkan Sertifikat Baru')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Terbitkan Sertifikat Baru</h1>
        <a href="{{ route('admin.certificates.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form method="post" action="{{ route('admin.certificates.store') }}">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Peserta <span class="text-red-500">*</span></label>
                            <select name="peserta_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('peserta_id') border-red-300 @enderror" required>
                                <option value="">-- Pilih Peserta --</option>
                                @foreach ($peserta as $p)
                                    <option value="{{ $p->id }}" {{ old('peserta_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }} ({{ $p->nomor_peserta }})
                                    </option>
                                @endforeach
                            </select>
                            @error('peserta_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kursus <span class="text-red-500">*</span></label>
                            <select name="kursus_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('kursus_id') border-red-300 @enderror" required>
                                <option value="">-- Pilih Kursus --</option>
                                @foreach ($kursus as $k)
                                    <option value="{{ $k->id }}" {{ old('kursus_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama ?? $k->judul }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kursus_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Masa Berlaku (Hari) - Opsional</label>
                            <input type="number" name="validity_days" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('validity_days') border-red-300 @enderror"
                                placeholder="Misal: 365 (1 tahun)" value="{{ old('validity_days') }}" min="1">
                            <p class="mt-2 text-sm text-gray-500">Kosongkan jika sertifikat berlaku selamanya</p>
                            @error('validity_days')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex space-x-3">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Terbitkan
                            </button>
                            <a href="{{ route('admin.certificates.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
