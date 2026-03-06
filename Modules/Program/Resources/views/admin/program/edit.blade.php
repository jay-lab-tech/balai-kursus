@extends('layouts.admin')

@section('title', 'Edit Program')

@section('page-title', 'Edit Program')

@section('content')
<div class="space-y-6">
    <div class="max-w-2xl">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6"><i class="bi bi-pencil me-2"></i>Edit Program</h2>
                <form method="POST" action="{{ route('admin.program.update', $program->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Program</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="nama" name="nama" value="{{ $program->nama }}" required>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"><i class="bi bi-check-circle me-2"></i>Update</button>
                            <a href="{{ route('admin.program.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
