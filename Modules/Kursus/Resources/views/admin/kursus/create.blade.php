@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Kursus</h2>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.kursus.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="nama" class="form-label fw-500">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>

                        <div class="mb-3">
                            <label for="program_id" class="form-label fw-500">Program</label>
                            <select class="form-select" id="program_id" name="program_id" required>
                                <option value="">-- Pilih Program --</option>
                                @foreach($program as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="level_id" class="form-label fw-500">Level</label>
                            <select class="form-select" id="level_id" name="level_id" required>
                                <option value="">-- Pilih Level --</option>
                                @foreach($level as $l)
                                    <option value="{{ $l->id }}">{{ $l->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="instruktur_id" class="form-label fw-500">Instruktur</label>
                            <select class="form-select" id="instruktur_id" name="instruktur_id" required>
                                <option value="">-- Pilih Instruktur --</option>
                                @foreach($instruktur as $i)
                                    <option value="{{ $i->id }}">{{ $i->nama_instr }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="harga" class="form-label fw-500">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga" required>
                        </div>

                        <div class="mb-3">
                            <label for="kuota" class="form-label fw-500">Kuota</label>
                            <input type="number" class="form-control" id="kuota" name="kuota" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle me-2"></i>Simpan</button>
                            <a href="{{ route('admin.kursus.index') }}" class="btn btn-secondary btn-lg"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
