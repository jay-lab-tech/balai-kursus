@extends('layouts.app-bootstrap')

@section('title', 'Buat Template Sertifikat')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-plus-circle me-2"></i>Buat Template Sertifikat</h2>
        <a href="{{ route('admin.templates.index') }}" class="btn btn-outline-secondary btn-lg">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.templates.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3 mb-4">
                            <div class="col-lg-6">
                                <label class="form-label fw-bold"><i class="bi bi-file-earmark me-2"></i>Nama Template <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    placeholder="Misal: Template Balai Default" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label fw-bold"><i class="bi bi-book me-2"></i>Berlaku untuk Kursus (Opsional)</label>
                                <select name="kursus_id" class="form-select form-select-lg">
                                    <option value="">-- Template Global (Semua Kursus) --</option>
                                    @foreach ($kursus as $k)
                                        <option value="{{ $k->id }}" {{ old('kursus_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama ?? $k->judul }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="bi bi-code me-2"></i>HTML Template <span class="text-danger">*</span></label>
                            <textarea name="html_template" class="form-control form-control-lg @error('html_template') is-invalid @enderror"
                                rows="12" placeholder="Masukkan HTML template sertifikat Anda di sini..." required>{{ old('html_template') ?? $defaultTemplate?->html_template ?? '' }}</textarea>
                            <small class="form-text text-muted d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i><strong>Placeholder yang tersedia:</strong>
                                <code class="bg-light p-2 d-block mt-1">
                                    {{'{{'}} NAMA {'}}'}} - Nama Peserta<br>
                                    {{'{{'}} KURSUS {'}}'}} - Nama Kursus<br>
                                    {{'{{'}} TANGGAL {'}}'}} - Tanggal Penerbitan<br>
                                    {{'{{'}} NO_SERTIF {'}}'}} - Nomor Sertifikat
                                </code>
                            </small>
                            @error('html_template')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="bi bi-pen me-2"></i>Tanda Tangan Digital (Opsional)</label>
                            <input type="file" name="signature" class="form-control form-control-lg" accept="image/*">
                            <small class="form-text text-muted d-block mt-2"><i class="bi bi-info-circle me-1"></i>Format: PNG, JPG (Max 2MB)</small>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="is_default" value="1" class="form-check-input" id="is_default"
                                    {{ old('is_default') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_default">
                                    <i class="bi bi-star-fill me-1 text-warning"></i>Jadikan sebagai template default
                                </label>
                                <div class="form-text text-muted">Template ini akan digunakan secara otomatis untuk semua sertifikat baru.</div>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Buat Template
                            </button>
                            <a href="{{ route('admin.templates.index') }}" class="btn btn-outline-secondary btn-lg">
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
