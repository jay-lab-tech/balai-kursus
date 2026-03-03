@extends('level::layouts.master')

@section('content')
<div class="container-fluid py-4">
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-500">Nama Level</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="warna" class="form-label fw-500">Warna Level</label>
                            <input type="color" class="form-control form-control-color" id="warna" name="warna" value="#2196f3">
                        </div>
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.level.store') }}">
                        @csrf

                        <!-- Field program dihapus, hanya input nama level -->

                        <div class="mb-3">
                            <label for="nama" class="form-label fw-500">Nama Level</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="warna" class="form-label fw-500">Warna Level</label>
                            <input type="color" class="form-control form-control-color" id="warna" name="warna" value="#2196f3">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle me-2"></i>Simpan</button>
                            <a href="{{ route('admin.level.index') }}" class="btn btn-secondary btn-lg"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
