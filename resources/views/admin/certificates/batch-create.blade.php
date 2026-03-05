@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <h1 class="mb-4">Terbitkan Sertifikat Massal</h1>

            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('admin.certificates.batch.store') }}" enctype="multipart/form-data">
                        @csrf

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
                            <input type="number" name="validity_days" class="form-control" placeholder="Misal: 365 (1 tahun)"
                                value="{{ old('validity_days') }}" min="1">
                            <small class="form-text text-muted">Kosongkan jika sertifikat berlaku selamanya</small>
                        </div>

                        <hr>

                        <h5 class="mb-3">Pilih cara memasukkan data peserta:</h5>

                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="multi-select-tab" data-bs-toggle="tab"
                                    data-bs-target="#multi-select" type="button" role="tab">Multi-Select</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="csv-tab" data-bs-toggle="tab"
                                    data-bs-target="#csv" type="button" role="tab">Upload CSV</button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            {{-- Multi-Select Tab --}}
                            <div class="tab-pane fade show active" id="multi-select" role="tabpanel">
                                <label class="form-label">Pilih Peserta <span class="text-danger">*</span></label>
                                <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                                    @foreach ($peserta as $p)
                                        <div class="form-check">
                                            <input type="checkbox" name="peserta_ids[]" value="{{ $p->id }}"
                                                class="form-check-input" id="peserta_{{ $p->id }}"
                                                {{ in_array($p->id, old('peserta_ids') ?? []) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="peserta_{{ $p->id }}">
                                                {{ $p->nama }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- CSV Tab --}}
                            <div class="tab-pane fade" id="csv" role="tabpanel">
                                <label class="form-label">Upload CSV</label>
                                <input type="file" name="csv_file" class="form-control" accept=".csv,.txt">
                                <small class="form-text text-muted d-block mt-2">
                                    Format: Satu ID peserta per baris (nomor saja, tanpa header). Kombinasi dengan multi-select di atas.
                                </small>
                            </div>
                        </div>

                        @error('peserta_ids')
                            <div class="alert alert-danger mt-3">{{ $message }}</div>
                        @enderror

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Terbitkan Massal</button>
                            <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
