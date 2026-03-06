@extends('layouts.admin')

@section('title', 'Terbitkan Sertifikat Massal')

@section('page-title', 'Terbitkan Sertifikat Massal')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-lightning-fill me-2"></i>Terbitkan Sertifikat Massal</h2>
        <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline-secondary btn-lg">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="post" action="{{ route('admin.certificates.batch.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Basic Settings --}}
                        <div class="row g-3 mb-4">
                            <div class="col-lg-6">
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

                            <div class="col-lg-6">
                                <label class="form-label fw-bold"><i class="bi bi-alarm me-2"></i>Masa Berlaku (Hari) - Opsional</label>
                                <input type="number" name="validity_days" class="form-control form-control-lg @error('validity_days') is-invalid @enderror"
                                    placeholder="Misal: 365 (1 tahun)" value="{{ old('validity_days') }}" min="1">
                                <small class="form-text text-muted d-block mt-2">Kosongkan jika sertifikat berlaku selamanya</small>
                                @error('validity_days')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Peserta Selection Method --}}
                        <h5 class="fw-bold mb-3"><i class="bi bi-person-check me-2"></i>Pilih Cara Memasukkan Data Peserta:</h5>

                        <ul class="nav nav-tabs mb-4 border-bottom" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold" id="multi-select-tab" data-bs-toggle="tab"
                                    data-bs-target="#multi-select" type="button" role="tab">
                                    <i class="bi bi-list-check me-2"></i>Multi-Select
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold" id="csv-tab" data-bs-toggle="tab"
                                    data-bs-target="#csv" type="button" role="tab">
                                    <i class="bi bi-file-earmark-csv me-2"></i>Upload CSV
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            {{-- Multi-Select Tab --}}
                            <div class="tab-pane fade show active" id="multi-select" role="tabpanel">
                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="bi bi-person me-2"></i>Pilih Peserta <span class="text-danger">*</span></label>
                                    <div class="border rounded p-4" style="max-height: 500px; overflow-y: auto; background-color: #f8f9fa;">
                                        @forelse ($peserta as $p)
                                            <div class="form-check mb-3">
                                                <input type="checkbox" name="peserta_ids[]" value="{{ $p->id }}"
                                                    class="form-check-input" id="peserta_{{ $p->id }}"
                                                    {{ in_array($p->id, old('peserta_ids') ?? []) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-500" for="peserta_{{ $p->id }}">
                                                    <strong>{{ $p->nama }}</strong>
                                                    <span class="text-muted small">({{ $p->nomor_peserta ?? 'No ID' }})</span>
                                                </label>
                                            </div>
                                        @empty
                                            <p class="text-muted text-center py-3"><i class="bi bi-inbox me-2"></i>Tidak ada peserta tersedia</p>
                                        @endforelse
                                    </div>
                                    @error('peserta_ids')
                                        <div class="alert alert-danger mt-2 mb-0">
                                            <i class="bi bi-exclamation-triangle me-2"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- CSV Tab --}}
                            <div class="tab-pane fade" id="csv" role="tabpanel">
                                <div class="mb-3">
                                    <label class="form-label fw-bold"><i class="bi bi-file-earmark-csv me-2"></i>Upload File CSV</label>
                                    <input type="file" name="csv_file" class="form-control form-control-lg" accept=".csv,.txt">
                                    <small class="form-text text-muted d-block mt-2">
                                        <i class="bi bi-info-circle me-1"></i><strong>Format:</strong> Satu ID peserta per baris (nomor saja, tanpa header)
                                    </small>
                                    <small class="form-text text-muted d-block mt-1">
                                        <i class="bi bi-lightbulb me-1"></i><strong>Catatan:</strong> Dapat dikombinasikan dengan pilihan Multi-Select di atas
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="mt-4 d-flex gap-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Terbitkan Massal
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
