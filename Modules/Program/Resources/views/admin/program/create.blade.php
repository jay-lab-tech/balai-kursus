@extends('program::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Program</h2>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.program.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="nama" class="form-label fw-500">Nama Program</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle me-2"></i>Simpan</button>
                            <a href="{{ route('admin.program.index') }}" class="btn btn-secondary btn-lg"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
