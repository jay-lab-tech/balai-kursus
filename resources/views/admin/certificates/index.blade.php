@extends('layouts.admin')
@section('content')
<div class="container mx-auto py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Sertifikat</h1>
        <a href="{{ route('admin.certificates.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow">+ Tambah Sertifikat</a>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Nama Sertifikat</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Kursus</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Peserta</th>
                    <!-- Kolom gambar dihapus -->
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Dibuat</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($certificates as $certificate)
                <tr>
                    <td class="px-4 py-2">
                        <span class="font-bold text-gray-800">{{ $certificate->certificate_name }}</span>
                    </td>
                    <td class="px-4 py-2">
                        <span class="text-gray-700">{{ $certificate->course->nama ?? '-' }}</span>
                    </td>
                    <td class="px-4 py-2">
                        <div class="flex flex-col">
                            <span class="font-semibold text-gray-900">{{ $certificate->participant->user->name ?? '-' }}</span>
                            <span class="text-gray-500 text-xs">{{ $certificate->participant->user->email ?? '-' }}</span>
                            <span class="inline-block bg-purple-200 text-purple-800 rounded px-2 py-1 text-xs mt-1">{{ $certificate->participant->nomor_peserta ?? '-' }}</span>
                        </div>
                    </td>
                    <!-- Kolom gambar dihapus -->
                    <td class="px-4 py-2 text-gray-600 text-xs">
                        {{ $certificate->created_at->format('d M Y') }}
                    </td>
                    <td class="px-4 py-2">
                        @if($certificate->status == 'pending')
                            <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded text-xs">Pending</span>
                            <form action="{{ route('admin.certificates.publish', $certificate->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs ml-2">Publish</button>
                            </form>
                        @else
                            <span class="bg-green-200 text-green-800 px-2 py-1 rounded text-xs">Published</span>
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.certificates.edit', $certificate->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-3 py-1 rounded shadow">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
