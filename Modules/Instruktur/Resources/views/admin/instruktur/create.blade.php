@extends('instruktur::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Instruktur</h2>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.instruktur.store') }}">
                        @csrf

                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-key me-2"></i>Akun Login</h5>
                        <div class="mb-3">
                            <label for="name" class="form-label fw-500">Nama User</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-500">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-500">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-person me-2"></i>Profil Instruktur</h5>
                        <div class="mb-3">
                            <label for="nama_instr" class="form-label fw-500">Nama Instruktur</label>
                            <input type="text" class="form-control" id="nama_instr" name="nama_instr" required>
                        </div>

                        <div class="mb-3">
                            <label for="spesialisasi" class="form-label fw-500">Spesialisasi</label>
                            <input type="text" class="form-control" id="spesialisasi" name="spesialisasi" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle me-2"></i>Simpan</button>
                            <a href="{{ route('admin.instruktur.index') }}" class="btn btn-secondary btn-lg"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
</div>
