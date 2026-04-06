@extends('layouts.admin')

@section('title', 'Assign/Update Level Peserta')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white shadow rounded-lg p-8">
    <h2 class="text-2xl font-bold mb-6">Assign/Update Level Peserta</h2>
    <form method="POST" action="{{ route('admin.kursus.assignLevel', [$kursus->id, $pendaftaran->id]) }}">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Nama Peserta</label>
            <div class="bg-gray-100 rounded px-4 py-2">{{ $pendaftaran->peserta->user->name }}</div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Level</label>
            <select name="level_id" class="form-select w-full rounded border-gray-300">
                <option value="">-- Pilih Level --</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" {{ (string) old('level_id', $pendaftaran->level_id) === (string) $level->id ? 'selected' : '' }}>{{ $level->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Simpan</button>
            <a href="{{ route('admin.kursus.peserta', $kursus->id) }}" class="ml-2 px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Batal</a>
        </div>
    </form>
</div>
@endsection
