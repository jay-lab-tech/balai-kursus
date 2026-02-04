@extends('layouts.app-bootstrap')

@section('title', $kursus->nama)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-book me-2"></i>{{ $kursus->nama }}</h2>
        <a href="/instruktur/kursus" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    {{-- Admin membuat pertemuan; instruktur tidak dapat menambah pertemuan --}}

    @forelse($risalah as $r)
        <div class="card border-0 shadow-sm mb-4" style="transition: all 0.3s ease; border-left: 4px solid #667eea;">
            <div class="card-header bg-light">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="bi bi-calendar me-2 text-primary"></i>
                            Pertemuan {{ $r->pertemuan_ke }}
                        </h5>
                        <small class="text-muted">
                            <i class="bi bi-calendar2 me-1"></i>{{ $r->tgl_pertemuan }} | 
                            <i class="bi bi-file-text me-1"></i>{{ $r->materi }}
                        </small>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="/instruktur/risalah/{{ $r->id }}/edit" class="btn btn-sm btn-secondary me-1">
                            <i class="bi bi-pencil-square me-1"></i>Edit
                        </a>
                        <a href="/instruktur/risalah/{{ $r->id }}/absensi" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil me-2"></i>Isi Absensi
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th class="fw-bold text-muted border-0">Nama Peserta</th>
                                <th class="fw-bold text-muted border-0">Status</th>
                                <th class="fw-bold text-muted border-0">Jam Datang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($r->absensis as $a)
                            <tr>
                                <td class="border-0 fw-500">{{ $a->pendaftaran->peserta->user->name }}</td>
                                <td class="border-0">
                                    @php
                                        $status = strtoupper($a->status);
                                        $badgeClass = match($status) {
                                            'HADIR' => 'bg-success',
                                            'ABSEN' => 'bg-danger',
                                            'IZIN' => 'bg-warning text-dark',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                                </td>
                                <td class="border-0 text-muted">{{ $a->jam_datang ?? '-' }}</td>
                            </tr>

                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3 border-0">
                                    <i class="bi bi-inbox"></i> Belum ada absensi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Informasi:</strong> Belum ada pertemuan. Hubungi admin untuk menambahkan pertemuan.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endforelse
</div>

<style>
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
