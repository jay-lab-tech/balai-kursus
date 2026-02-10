@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <h2 class="fw-bold">Edit Kelas</h2>
    <div class="card mt-3">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.kelas.update', $kela->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nama Kelas</label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" required value="{{ old('nama', $kela->nama) }}">
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Kapasitas (orang)</label>
                    <input type="number" name="kapasitas" class="form-control @error('kapasitas') is-invalid @enderror" min="1" required value="{{ old('kapasitas', $kela->kapasitas) }}">
                    @error('kapasitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Fasilitas</label>
                    <input type="text" name="fasilitas" class="form-control @error('fasilitas') is-invalid @enderror" required value="{{ old('fasilitas', $kela->fasilitas) }}">
                    @error('fasilitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="2">{{ old('keterangan', $kela->keterangan) }}</textarea>
                    @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button class="btn btn-primary">Update</button>
                <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
