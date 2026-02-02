@extends('instruktur::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard Instruktur
            </h2>
        </div>
    </div>

    @php
        $instruktur = auth()->user()->instruktur;
        $kursus = \App\Models\Kursus::where('instruktur_id', $instruktur->id)->get();
        $totalPeserta = $kursus->sum(function($k) { return $k->pendaftarans()->count(); });
        $totalRisalah = $kursus->sum(function($k) { return $k->risalahs()->count(); });
    @endphp

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted d-block mb-1">Total Kursus</small>
                            <h4 class="fw-bold text-dark mb-0">{{ $kursus->count() }}</h4>
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
                            <small class="text-muted d-block mb-1">Total Peserta</small>
                            <h4 class="fw-bold text-dark mb-0">{{ $totalPeserta }}</h4>
                        </div>
                        <i class="bi bi-people text-success" style="font-size: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted d-block mb-1">Total Pertemuan</small>
                            <h4 class="fw-bold text-dark mb-0">{{ $totalRisalah }}</h4>
                        </div>
                        <i class="bi bi-calendar-check text-info" style="font-size: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted d-block mb-1">Nama Instruktur</small>
                            <h6 class="fw-bold text-dark mb-0">{{ $instruktur->nama_instr }}</h6>
                        </div>
                        <i class="bi bi-person-circle text-warning" style="font-size: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kursus Aktif -->
    <div class="row">
        <div class="col-12">
            <h5 class="fw-bold text-dark mb-3">Kursus yang Anda Ajarkan</h5>
        </div>
    </div>

    @if($kursus->count() > 0)
        <div class="row g-4">
            @foreach($kursus as $k)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title fw-bold text-dark mb-2">{{ $k->nama }}</h6>
                        <small class="text-muted d-block mb-3">
                            <i class="bi bi-collection me-1"></i>{{ $k->program->nama }} | 
                            <i class="bi bi-bookmark me-1"></i>{{ $k->level->nama }}
                        </small>
                        
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Peserta</small>
                                <h6 class="fw-bold text-primary">{{ $k->pendaftarans()->count() }}</h6>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Pertemuan</small>
                                <h6 class="fw-bold text-info">{{ $k->risalahs()->count() }}</h6>
                            </div>
                        </div>

                        <a href="/instruktur/kursus/{{ $k->id }}" class="btn btn-sm btn-primary w-100">
                            <i class="bi bi-arrow-right me-1"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Belum ada kursus.</strong> Hubungi admin untuk ditugaskan mengajar kursus.
        </div>
    @endif
</div>
@endsection
