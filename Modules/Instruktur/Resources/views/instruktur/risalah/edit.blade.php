@extends('instruktur::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">
            <i class="bi bi-pencil-square me-2"></i>Edit Risalah
        </h2>
        <a href="/instruktur/kursus/{{ $risalah->kursus_id }}/risalah" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="/instruktur/risalah/{{ $risalah->id }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kursus</label>
                            <input type="text" class="form-control" value="{{ $risalah->kursus->nama }}" disabled>
                            <small class="text-muted d-block mt-1">
                                Program: {{ $risalah->kursus->program->nama }} | Level: {{ $risalah->kursus->level->nama }}
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="materi" class="form-label fw-bold">Materi</label>
                            <input type="text" class="form-control @error('materi') is-invalid @enderror"
                                   id="materi" name="materi" value="{{ old('materi', $risalah->materi) }}" required>
                            @error('materi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label fw-bold">Catatan (opsional)</label>
                            <textarea name="catatan" id="catatan" rows="6" class="form-control">{{ old('catatan', $risalah->catatan) }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                            </button>
                            <a href="/instruktur/kursus/{{ $risalah->kursus_id }}/risalah" class="btn btn-outline-secondary">
                                <i class="bi bi-x me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
