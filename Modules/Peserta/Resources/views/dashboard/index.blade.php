@extends('peserta::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-0">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard Peserta
        </h2>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-book me-2"></i>Kursus yang Saya Ikuti
                    </h5>

                    @if($pendaftarans && count($pendaftarans) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th class="fw-bold text-muted border-0">Nama Kursus</th>
                                        <th class="fw-bold text-muted border-0">Program</th>
                                        <th class="fw-bold text-muted border-0">Level</th>
                                        <th class="fw-bold text-muted border-0">Status Pembayaran</th>
                                        <th class="fw-bold text-muted border-0">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendaftarans as $p)
                                    <tr>
                                        <td class="fw-bold border-0">{{ $p->kursus->nama }}</td>
                                        <td class="border-0">{{ $p->kursus->program->nama }}</td>
                                        <td class="border-0">{{ $p->kursus->level->nama }}</td>
                                        <td class="border-0">
                                            @if($p->status_pembayaran === 'selesai')
                                                <span class="badge bg-success">Selesai</span>
                                            @elseif($p->status_pembayaran === 'dp')
                                                <span class="badge bg-warning">DP</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($p->status_pembayaran) }}</span>
                                            @endif
                                        </td>
                                        <td class="border-0">
                                            <a href="/peserta/kursus/{{ $p->kursus_id }}" class="btn btn-sm btn-primary me-1">
                                                <i class="bi bi-eye me-1"></i>Detail
                                            </a>
                                            <a href="/peserta/kursus/{{ $p->kursus_id }}/risalah" class="btn btn-sm btn-info">
                                                <i class="bi bi-file-earmark me-1"></i>Risalah
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            Anda belum mendaftar di kursus apapun. <a href="/peserta/kursus" class="alert-link">Lihat daftar kursus tersedia</a>.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-link-45deg me-2"></i>Akses Cepat
                    </h5>
                    <div class="d-grid gap-2">
                        <a href="/peserta/kursus" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-book me-2"></i>Lihat Semua Kursus
                        </a>
                        <a href="/peserta/pendaftaran" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-clipboard me-2"></i>Pendaftaran Saya
                        </a>
                        <a href="/peserta/riwayat-pembayaran" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-receipt me-2"></i>Riwayat Pembayaran
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
