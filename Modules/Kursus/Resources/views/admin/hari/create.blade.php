@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <h2 class="fw-bold">Tambah Hari</h2>
    <div class="card mt-3">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.hari.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Hari</label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" required value="{{ old('nama') }}" placeholder="Contoh: Senin">
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Urutan (1-7)</label>
                    <input type="number" name="urutan" class="form-control @error('urutan') is-invalid @enderror" min="1" max="7" required value="{{ old('urutan') }}" placeholder="Contoh: 1 untuk Senin">
                    @error('urutan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.hari.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
