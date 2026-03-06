@extends('layouts.admin')

@section('title', 'Cabut Sertifikat')

@section('page-title', 'Cabut Sertifikat')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-shield-x me-2"></i>Cabut Sertifikat</h2>
        <a href="{{ route('admin.certificates.show', $certificate) }}" class="btn btn-outline-secondary btn-lg">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h6 class="mb-3 fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Pencabutan Sertifikat</h6>
                <p class="mb-2">Anda akan mencabut sertifikat:</p>
                <ul class="mb-0 ms-3">
                    <li><strong>No. Sertifikat:</strong> {{ $certificate->no_sertifikat }}</li>
                    <li><strong>Peserta:</strong> {{ $certificate->peserta->nama }}</li>
                    <li><strong>Kursus:</strong> {{ $certificate->kursus->nama ?? $certificate->kursus->judul }}</li>
                    <li><strong>Status:</strong> {{ $certificate->status }}</li>
                </ul>
                <p class="mb-0 mt-2 text-muted small"><i class="bi bi-info-circle me-1"></i>Pencabutan tidak dapat dibatalkan.</p>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.certificates.revoke', $certificate) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="bi bi-chat-left-text me-2"></i>Alasan Pencabutan <span class="text-danger">*</span></label>
                            <textarea name="revoked_reason" class="form-control form-control-lg @error('revoked_reason') is-invalid @enderror" rows="6"
                                placeholder="Jelaskan secara detail alasan pencabutan sertifikat ini..." required>{{ old('revoked_reason') }}</textarea>
                            <small class="form-text text-muted d-block mt-2">Alasan ini akan ditampilkan dalam laporan riwayat sertifikat.</small>
                            @error('revoked_reason')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-danger btn-lg"
                                onclick="return confirm('⚠️ Pencabutan tidak dapat dibatalkan. Lanjutkan?')">
                                <i class="bi bi-shield-x me-2"></i>Cabut Sertifikat
                            </button>
                            <a href="{{ route('admin.certificates.show', $certificate) }}" class="btn btn-outline-secondary btn-lg">
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
