@extends('layouts.user')
@section('content')
<div class="container">
    <h1>Sertifikat Saya</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Sertifikat</th>
                <th>Kursus</th>
                <th>Gambar</th>
                <th>Download PDF</th>
            </tr>
        </thead>
        <tbody>
            @foreach($certificates as $certificate)
            <tr>
                <td>{{ $certificate->certificate_name }}</td>
                <td>{{ $certificate->course->nama ?? '-' }}</td>
                <td><img src="{{ asset('storage/' . $certificate->certificate_image_path) }}" width="100"></td>
                <td>
                    <a href="{{ route('user.certificates.download', $certificate->id) }}" class="btn btn-primary">Download PDF</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
