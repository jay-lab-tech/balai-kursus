@extends('profile.layout')

@section('content')
<div class="max-w-5xl mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-6">Sertifikat Saya</h2>
    @if($certificates->isEmpty())
        <div class="text-gray-600">Belum ada sertifikat yang diterbitkan.</div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($certificates as $certificate)
        <div class="bg-white rounded shadow p-4 flex flex-col">
            <div class="mb-2 font-semibold">{{ $certificate->certificate_name }}</div>
            <div class="text-sm text-gray-500 mb-2">Kursus: {{ $certificate->course->nama ?? '-' }}</div>
            <div class="text-sm text-gray-500 mb-2">Diterbitkan: {{ $certificate->created_at->format('d M Y') }}</div>
            <div class="flex space-x-2 mt-2">
                <a href="{{ route('profile.certificate.detail', $certificate->id) }}" class="bg-yellow-500 text-black px-3 py-2 rounded text-center font-semibold">Detail</a>
                <a href="{{ route('profile.certificate.download', $certificate->id) }}" class="bg-blue-600 text-white px-3 py-2 rounded text-center font-semibold">Download PDF</a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
