@extends('profile.layout')

@section('content')
<div class="max-w-xl mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-6">Detail Sertifikat</h2>
    <div class="bg-white rounded shadow p-6">
        <div class="mb-2 font-semibold">{{ $certificate->certificate_name }}</div>
        <div class="text-sm text-gray-500 mb-2">Kursus: {{ $certificate->course->nama ?? '-' }}</div>
        <div class="text-sm text-gray-500 mb-2">Diterbitkan: {{ $certificate->created_at->format('d M Y') }}</div>
        <div class="mb-4">
            <img src="{{ asset('storage/'.$certificate->certificate_image_path) }}" alt="Certificate" class="w-full h-64 object-cover rounded">
        </div>
        <a href="{{ route('profile.certificate.download', $certificate->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded text-center font-semibold">Download PDF</a>
        <a href="{{ route('profile.certificates') }}" class="ml-2 bg-yellow-500 text-black px-4 py-2 rounded text-center font-semibold">Kembali</a>
    </div>
</div>
@endsection
