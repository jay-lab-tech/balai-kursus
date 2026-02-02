@extends('instruktur::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-pencil me-2"></i>Edit Instruktur</h2>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.instruktur.update', $instruktur->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-500">Nama User</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $instruktur->user->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-500">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $instruktur->user->email }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="nama_instr" class="form-label fw-500">Nama Instruktur</label>
                            <input type="text" class="form-control" id="nama_instr" name="nama_instr" value="{{ $instruktur->nama_instr }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="spesialisasi" class="form-label fw-500">Spesialisasi</label>
                            <input type="text" class="form-control" id="spesialisasi" name="spesialisasi" value="{{ $instruktur->spesialisasi }}" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle me-2"></i>Update</button>
                            <a href="{{ route('admin.instruktur.index') }}" class="btn btn-secondary btn-lg"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection