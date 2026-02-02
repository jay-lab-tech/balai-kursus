@extends('layouts.app-bootstrap')

@section('title', 'Tambah Pertemuan')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Pertemuan</h2>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="/instruktur/kursus/{{ $kursus->id }}/risalah">
                        @csrf

                        <input type="hidden" name="kursus_id" value="{{ request('kursus') }}">

                        <div class="mb-3">
                            <label for="pertemuan_ke" class="form-label fw-500">Pertemuan Ke</label>
                            <input type="number" class="form-control" id="pertemuan_ke" name="pertemuan_ke" required>
                        </div>

                        <div class="mb-3">
                            <label for="tgl_pertemuan" class="form-label fw-500">Tanggal Pertemuan</label>
                            <input type="date" class="form-control" id="tgl_pertemuan" name="tgl_pertemuan" required>
                        </div>

                        <div class="mb-3">
                            <label for="materi" class="form-label fw-500">Materi</label>
                            <input type="text" class="form-control" id="materi" name="materi" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle me-2"></i>Simpan</button>
                            <a href="/instruktur/kursus" class="btn btn-secondary btn-lg"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
