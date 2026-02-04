@extends('instruktur::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">
            <i class="bi bi-file-earmark-plus me-2"></i>Buat Risalah Baru
        </h2>
        <a href="/instruktur/kursus/{{ $kursus->id }}/risalah" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="/instruktur/kursus/{{ $kursus->id }}/risalah" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kursus</label>
                            <input type="text" class="form-control" value="{{ $kursus->nama }}" disabled>
                            <small class="text-muted d-block mt-1">
                                Program: {{ $kursus->program->nama }} | Level: {{ $kursus->level->nama }}
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold">Nama Risalah</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" name="nama" placeholder="Misal: Pertemuan 1 - Pengenalan Laravel" 
                                   value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jadwal_id" class="form-label fw-bold">Pilih Jadwal (opsional)</label>
                            <select name="jadwal_id" id="jadwal_id" class="form-select">
                                <option value="">-- Pilih jadwal yang dibuat admin --</option>
                                @foreach($kursus->jadwals as $jadwal)
                                    <option value="{{ $jadwal->id }}">Pertemuan {{ $jadwal->pertemuan_ke ?? '-' }} - {{ $jadwal->tgl_pertemuan->format('d M Y') }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Jika dipilih, tanggal risalah akan mengikuti jadwal.</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Buat Risalah
                            </button>
                            <a href="/instruktur/kursus/{{ $kursus->id }}/risalah" class="btn btn-outline-secondary">
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
