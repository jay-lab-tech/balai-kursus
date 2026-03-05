@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="mb-4">Terbitkan Sertifikat Baru</h1>

            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('admin.certificates.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Peserta <span class="text-danger">*</span></label>
                            <select name="peserta_id" class="form-select @error('peserta_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Peserta --</option>
                                @foreach ($peserta as $p)
                                    <option value="{{ $p->id }}" {{ old('peserta_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('peserta_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kursus <span class="text-danger">*</span></label>
                            <select name="kursus_id" class="form-select @error('kursus_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kursus --</option>
                                @foreach ($kursus as $k)
                                    <option value="{{ $k->id }}" {{ old('kursus_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->judul }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kursus_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Masa Berlaku (Hari) - Opsional</label>
                            <input type="number" name="validity_days" class="form-control @error('validity_days') is-invalid @enderror"
                                placeholder="Misal: 365 (1 tahun)" value="{{ old('validity_days') }}" min="1">
                            <small class="form-text text-muted">Kosongkan jika sertifikat berlaku selamanya</small>
                            @error('validity_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Terbitkan
                            </button>
                            <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline-secondary">
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
