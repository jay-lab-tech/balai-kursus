@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-3">
        <a href="{{ route('admin.lokasi.edit', $lokasi->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('admin.lokasi.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $lokasi->nama }}</h5>
            <p><strong>Alamat:</strong> {{ $lokasi->alamat }}</p>
            <p><strong>Kota:</strong> {{ $lokasi->kota }}</p>
            <p><strong>Provinsi:</strong> {{ $lokasi->provinsi }}</p>
            <p><strong>No Telp:</strong> {{ $lokasi->no_telp }}</p>
            @if($lokasi->keterangan)
                <p><strong>Keterangan:</strong> {{ $lokasi->keterangan }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
