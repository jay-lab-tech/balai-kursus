@extends('profile.layout')

@section('content')
<div class="max-w-2xl mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-6">Edit Profile</h2>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('PATCH')
        <div>
            <label class="block text-sm font-medium">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded px-3 py-2" required>
            @error('name')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded px-3 py-2" required>
            @error('email')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
        </div>
        {{-- Informasi Tambahan Peserta --}}
        @if(isset($user->role) && $user->role === 'peserta')
        <hr class="my-4">
        <h5 class="mb-3 text-gray-700 font-semibold">Data Peserta</h5>
        <div>
            <label class="block text-sm font-medium">Nomor Peserta</label>
            <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100" value="{{ $peserta->nomor_peserta ?? '-' }}" readonly disabled>
            <span class="text-xs text-gray-500">Nomor peserta digenerate otomatis oleh sistem.</span>
        </div>
        <div>
            <label class="block text-sm font-medium">Nomor HP / WhatsApp</label>
            <input type="text" name="no_hp" value="{{ old('no_hp', $peserta->no_hp ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Contoh: 08123456789">
            @error('no_hp')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Asal Instansi / Sekolah</label>
            <input type="text" name="instansi" value="{{ old('instansi', $peserta->instansi ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Contoh: Universitas Pendidikan Indonesia">
            @error('instansi')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
        </div>
        @endif
        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Profile</button>
        </div>
    </form>
</div>
@endsection