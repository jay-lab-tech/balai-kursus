@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <h1 class="mb-4">Buat Template Sertifikat</h1>

            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('admin.templates.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama Template <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                placeholder="Misal: Template Balai Default" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Berlaku untuk Kursus (Opsional)</label>
                            <select name="kursus_id" class="form-select">
                                <option value="">-- Template Global (Semua Kursus) --</option>
                                @foreach ($kursus as $k)
                                    <option value="{{ $k->id }}" {{ old('kursus_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->judul }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">HTML Template <span class="text-danger">*</span></label>
                            <textarea name="html_template" class="form-control @error('html_template') is-invalid @enderror"
                                rows="10" placeholder="Masukkan HTML template..." required>{{ old('html_template') ?? $defaultTemplate?->html_template ?? '' }}</textarea>
                            <small class="form-text text-muted d-block mt-2">
                                Gunakan placeholder: <code>{{'{{'}} NAMA {'}}'}}</code>, <code>{{'{{'}} KURSUS {'}}'}}</code>,
                                <code>{{'{{'}} TANGGAL {'}}'}}</code>, <code>{{'{{'}} NO_SERTIF {'}}'}}</code>
                            </small>
                            @error('html_template')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanda Tangan Digital (Opsional)</label>
                            <input type="file" name="signature" class="form-control" accept="image/*">
                            <small class="form-text text-muted">Format: PNG, JPG (Max 2MB)</small>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_default" value="1" class="form-check-input"
                                {{ old('is_default') ? 'checked' : '' }}>
                            <label class="form-check-label">Jadikan sebagai template default</label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Buat Template</button>
                            <a href="{{ route('admin.templates.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
