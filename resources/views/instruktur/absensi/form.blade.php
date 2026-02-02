@extends('layouts.app-bootstrap')

@section('title', 'Absensi Pertemuan')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-clipboard-check me-2"></i>Absensi Pertemuan {{ $risalah->pertemuan_ke }}</h2>
        <small class="text-muted">{{ $risalah->tgl_pertemuan }}</small>
    </div>

    <div class="row">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST">
                        @csrf

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th class="fw-bold text-muted border-0">No</th>
                                        <th class="fw-bold text-muted border-0">Nama Peserta</th>
                                        <th class="fw-bold text-muted border-0">Status Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendaftaran as $p)
                                    <tr>
                                        <td class="border-0 text-muted">{{ $loop->iteration }}</td>
                                        <td class="border-0 fw-500">{{ $p->peserta->user->name }}</td>
                                        <td class="border-0">
                                            <select class="form-select form-select-sm" name="absen[{{ $p->id }}]" required>
                                                <option value="">-- Pilih Status --</option>
                                                <option value="H">✓ Hadir</option>
                                                <option value="S">🏥 Sakit</option>
                                                <option value="I">📝 Izin</option>
                                                <option value="A">✗ Alpha</option>
                                            </select>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Simpan Absensi
                            </button>
                            <a href="/instruktur/kursus" class="btn btn-secondary btn-lg">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
