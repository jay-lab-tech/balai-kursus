@extends('layouts.admin')

@section('title', 'Tambah Kelas')

@section('page-title', 'Tambah Kelas')

@section('content')
<div class="max-w-4xl">
    <div class="rounded-2xl bg-white p-6 shadow">
        <form method="POST" action="{{ route('admin.kursus.store') }}" class="space-y-6">
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="periode" class="block text-sm font-medium text-gray-700">Periode</label>
                    <input type="text" id="periode" name="periode" value="{{ old('periode') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="program_id" class="block text-sm font-medium text-gray-700">Program</label>
                    <select id="program_id" name="program_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Pilih program</option>
                        @foreach($program as $item)
                            <option value="{{ $item->id }}" {{ (string) old('program_id') === (string) $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="level_id" class="block text-sm font-medium text-gray-700">Level</label>
                    <select id="level_id" name="level_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Pilih level</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ (string) old('level_id') === (string) $level->id ? 'selected' : '' }}>{{ $level->nama }} ({{ $level->rentang_nilai }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
                    <input type="number" id="harga" name="harga" value="{{ old('harga') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="harga_upi" class="block text-sm font-medium text-gray-700">Harga UPI</label>
                    <input type="number" id="harga_upi" name="harga_upi" value="{{ old('harga_upi') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="kuota" class="block text-sm font-medium text-gray-700">Kuota</label>
                    <input type="number" id="kuota" name="kuota" value="{{ old('kuota') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="buka" {{ old('status') === 'buka' ? 'selected' : '' }}>Buka</option>
                    <option value="tutup" {{ old('status') === 'tutup' ? 'selected' : '' }}>Tutup</option>
                    <option value="berjalan" {{ old('status') === 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                </select>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">Simpan</button>
                <a href="{{ route('admin.kursus.index') }}" class="rounded-xl bg-gray-200 px-5 py-3 font-semibold text-gray-800 hover:bg-gray-300">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
