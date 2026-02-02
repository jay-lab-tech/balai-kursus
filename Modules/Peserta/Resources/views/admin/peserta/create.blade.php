@extends('peserta::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-person-plus me-2"></i>Tambah Peserta</h2>
        <a href="/admin/peserta" class="btn btn-secondary">Kembali</a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="/admin/peserta" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Peserta</label>
                    <input type="text" name="nomor_peserta" class="form-control" value="{{ old('nomor_peserta') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Instansi</label>
                    <input type="text" name="instansi" class="form-control" value="{{ old('instansi') }}">
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary">Simpan</button>
                    <a href="/admin/peserta" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
