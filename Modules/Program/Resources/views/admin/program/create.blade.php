@extends('layouts.admin')

@section('title', 'Tambah Program')

@section('page-title', 'Tambah Program')

@section('content')
<div class="space-y-6">
    <div class="max-w-2xl">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6"><i class="bi bi-plus-circle me-2"></i>Tambah Program</h2>
                <form method="POST" action="{{ route('admin.program.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Program</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="nama" name="nama" value="{{ old('nama') }}" required>
                        </div>

                        <div>
                            <label for="warna" class="block text-sm font-medium text-gray-700">Warna Program</label>
                            <input type="color" class="mt-1 h-12 w-full rounded-xl border border-gray-300 shadow-sm" id="warna" name="warna" value="{{ old('warna', '#eab308') }}">
                            <p class="mt-2 text-xs text-gray-500">Warna ini dipakai untuk kartu program di halaman peserta.</p>
                            <div class="mt-3 rounded-2xl p-4 text-white shadow-sm" style="background: linear-gradient(135deg, {{ old('warna', '#eab308') }}, #111827);">
                                Preview warna program
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"><i class="bi bi-check-circle me-2"></i>Simpan</button>
                            <a href="{{ route('admin.program.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
