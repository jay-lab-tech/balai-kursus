@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-3">
        <a href="{{ route('admin.kelas.edit', $kela->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $kela->nama }}</h5>
            <p><strong>Kapasitas:</strong> {{ $kela->kapasitas }} orang</p>
            <p><strong>Fasilitas:</strong> {{ $kela->fasilitas }}</p>
            @if($kela->keterangan)
                <p><strong>Keterangan:</strong> {{ $kela->keterangan }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
