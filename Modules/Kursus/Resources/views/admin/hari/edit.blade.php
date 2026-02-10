@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <h2 class="fw-bold">Edit Hari</h2>
    <div class="card mt-3">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.hari.update', $hari->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nama Hari</label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" required value="{{ old('nama', $hari->nama) }}">
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Urutan (1-7)</label>
                    <input type="number" name="urutan" class="form-control @error('urutan') is-invalid @enderror" min="1" max="7" required value="{{ old('urutan', $hari->urutan) }}">
                    @error('urutan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button class="btn btn-primary">Update</button>
                <a href="{{ route('admin.hari.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
