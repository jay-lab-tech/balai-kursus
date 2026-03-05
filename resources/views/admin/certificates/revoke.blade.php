@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="mb-4">Cabut Sertifikat</h1>

            <div class="card">
                <div class="card-body">
                    <div class="alert alert-warning">
                        <strong>⚠️ Konfirmasi Pencabutan</strong>
                        <p class="mb-0">Anda akan mencabut sertifikat <strong>{{ $certificate->no_sertifikat }}</strong> untuk
                            <strong>{{ $certificate->peserta->nama }}</strong> ({{ $certificate->kursus->judul }}).
                        </p>
                    </div>

                    <form method="post" action="{{ route('admin.certificates.revoke', $certificate) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Alasan Pencabutan <span class="text-danger">*</span></label>
                            <textarea name="revoked_reason" class="form-control @error('revoked_reason') is-invalid @enderror" rows="5"
                                placeholder="Jelaskan alasan pencabutan sertifikat...">{{ old('revoked_reason') }}</textarea>
                            @error('revoked_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Pencabutan tidak dapat dibatalkan. Lanjutkan?')">
                                Cabut Sertifikat
                            </button>
                            <a href="{{ route('admin.certificates.show', $certificate) }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
