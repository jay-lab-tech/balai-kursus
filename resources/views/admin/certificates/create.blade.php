@extends('layouts.app-bootstrap')

@section('title', 'Terbitkan Sertifikat Baru')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-plus-circle me-2"></i>Terbitkan Sertifikat Baru</h2>
        <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline-secondary btn-lg">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.certificates.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-person me-2"></i>Peserta <span class="text-danger">*</span></label>
                            <select name="peserta_id" class="form-select form-select-lg @error('peserta_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Peserta --</option>
                                @foreach ($peserta as $p)
                                    <option value="{{ $p->id }}" {{ old('peserta_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }} ({{ $p->nomor_peserta }})
                                    </option>
                                @endforeach
                            </select>
                            @error('peserta_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-book me-2"></i>Kursus <span class="text-danger">*</span></label>
                            <select name="kursus_id" class="form-select form-select-lg @error('kursus_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kursus --</option>
                                @foreach ($kursus as $k)
                                    <option value="{{ $k->id }}" {{ old('kursus_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama ?? $k->judul }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kursus_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="bi bi-alarm me-2"></i>Masa Berlaku (Hari) - Opsional</label>
                            <input type="number" name="validity_days" class="form-control form-control-lg @error('validity_days') is-invalid @enderror"
                                placeholder="Misal: 365 (1 tahun)" value="{{ old('validity_days') }}" min="1">
                            <small class="form-text text-muted d-block mt-2">Kosongkan jika sertifikat berlaku selamanya</small>
                            @error('validity_days')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Terbitkan
                            </button>
                            <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-x-circle me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
