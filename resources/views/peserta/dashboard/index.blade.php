@extends('layouts.app-bootstrap')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard Peserta
            </h2>
        </div>
    </div>

    @php
        $peserta = auth()->user()->peserta;
        $pendaftarans = $peserta->pendaftarans ?? [];
        $totalKursus = count($pendaftarans);
        $totalBayar = collect($pendaftarans)->sum('terbayar');
    @endphp

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted d-block mb-1">Kursus Diikuti</small>
                            <h4 class="fw-bold text-dark mb-0">{{ $totalKursus }}</h4>
                        </div>
                        <i class="bi bi-book text-primary" style="font-size: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted d-block mb-1">Total Terbayar</small>
                            <h6 class="fw-bold text-dark mb-0">Rp {{ number_format($totalBayar) }}</h6>
                        </div>
                        <i class="bi bi-cash-coin text-success" style="font-size: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted d-block mb-1">Nama Peserta</small>
                            <h6 class="fw-bold text-dark mb-0">{{ $peserta->nama_peserta }}</h6>
                        </div>
                        <i class="bi bi-person-circle text-warning" style="font-size: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted d-block mb-1">Aksi Cepat</small>
                            <a href="{{ url('/peserta/kursus') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-arrow-right me-1"></i>Lihat Kursus
                            </a>
                        </div>
                        <i class="bi bi-arrow-right text-info" style="font-size: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kursus yang Diikuti -->
    <div class="row">
        <div class="col-12">
            <h5 class="fw-bold text-dark mb-3">Kursus yang Sedang Diikuti</h5>
        </div>
    </div>

    @if(count($pendaftarans) > 0)
        <div class="row g-4">
            @foreach($pendaftarans as $p)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title fw-bold text-dark mb-2">{{ $p->kursus->nama }}</h6>
                        <small class="text-muted d-block mb-3">
                            <i class="bi bi-collection me-1"></i>{{ $p->kursus->program->nama }} | 
                            <i class="bi bi-bookmark me-1"></i>{{ $p->kursus->level->nama }}
                        </small>
                        
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Terbayar</small>
                                <h6 class="fw-bold text-success">Rp {{ number_format($p->terbayar) }}</h6>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Status</small>
                                @if($p->isLunas())
                                    <span class="badge bg-success"><i class="bi bi-check me-1"></i>LUNAS</span>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>BELUM</span>
                                @endif
                            </div>
                        </div>

                        <a href="/peserta/kursus/{{ $p->kursus->id }}" class="btn btn-sm btn-primary w-100 mb-2">
                            <i class="bi bi-arrow-right me-1"></i>Lihat Detail
                        </a>
                        
                        @if(!$p->isLunas())
                            <a href="{{ url('/peserta/pendaftaran') }}" class="btn btn-sm btn-outline-success w-100">
                                <i class="bi bi-credit-card me-1"></i>Bayar
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Belum ada kursus.</strong> <a href="/peserta/kursus">Daftar kursus sekarang</a>
        </div>
    @endif
</div>
@endsection
