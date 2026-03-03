@extends('level::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-pencil me-2"></i>Edit Level</h2>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.level.update', $level->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Field program dihapus, hanya edit nama level -->

                        <div class="mb-3">
                            <label for="nama" class="form-label fw-500">Nama Level</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ $level->nama }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="warna" class="form-label fw-500">Warna Level</label>
                            <input type="color" class="form-control form-control-color" id="warna" name="warna" value="{{ $level->warna ?? '#2196f3' }}">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle me-2"></i>Update</button>
                            <a href="{{ route('admin.level.index') }}" class="btn btn-secondary btn-lg"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
