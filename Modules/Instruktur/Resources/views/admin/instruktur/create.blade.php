@extends('layouts.admin')

@section('title', 'Tambah Instruktur')

@section('page-title', 'Tambah Instruktur')

@section('content')
<div class="space-y-6">
    <div class="max-w-2xl">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6"><i class="bi bi-plus-circle me-2"></i>Tambah Instruktur</h2>
                <form method="POST" action="{{ route('admin.instruktur.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4"><i class="bi bi-key me-2"></i>Akun Login</h3>
                            <div class="space-y-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama User</label>
                                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="name" name="name" required>
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="email" name="email" required>
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                    <input type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="password" name="password" required>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4"><i class="bi bi-person me-2"></i>Profil Instruktur</h3>
                            <div class="space-y-6">
                                <div>
                                    <label for="nama_instr" class="block text-sm font-medium text-gray-700">Nama Instruktur</label>
                                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="nama_instr" name="nama_instr" required>
                                </div>

                                <div>
                                    <label for="spesialisasi" class="block text-sm font-medium text-gray-700">Spesialisasi</label>
                                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="spesialisasi" name="spesialisasi" required>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"><i class="bi bi-check-circle me-2"></i>Simpan</button>
                            <a href="{{ route('admin.instruktur.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
