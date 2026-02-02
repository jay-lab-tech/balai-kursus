@extends('layouts.app-bootstrap')

@section('title', 'Kursus - Instruktur')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-book me-2"></i>Kursus Yang Diampu</h2>
        <a href="{{ url('/instruktur/dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    @if(count($kursus) > 0)
        <div class="row g-4">
            @foreach($kursus as $k)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="transition: all 0.3s ease; border-top: 4px solid #667eea;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-dark mb-2">
                            <i class="bi bi-bookmark me-2 text-primary"></i>{{ $k->nama_kursus ?? $k->nama }}
                        </h5>
                        <p class="text-muted mb-2">
                            <i class="bi bi-folder me-2"></i>{{ $k->program->nama_program ?? $k->program->nama ?? 'N/A' }}
                        </p>
                        <p class="text-muted mb-3">
                            <i class="bi bi-mortarboard me-2"></i>{{ $k->level->nama_level ?? $k->level->nama ?? 'N/A' }}
                        </p>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="p-2 bg-light rounded">
                                    <small class="text-muted d-block">Peserta</small>
                                    <p class="mb-0"><strong class="text-primary">{{ $k->peserta_count ?? 0 }}</strong></p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 bg-light rounded">
                                    <small class="text-muted d-block">Pertemuan</small>
                                    <p class="mb-0"><strong class="text-primary">{{ $k->risalah_count ?? 0 }}</strong></p>
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ url('/instruktur/kursus/'.$k->id) }}" class="btn btn-primary w-100">
                            <i class="bi bi-arrow-right me-2"></i>Kelola Pertemuan
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #6c757d;"></i>
                <h5 class="mt-3 text-dark">Belum ada kursus</h5>
                <p class="text-muted">Hubungi admin untuk mendapatkan kursus</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

<style>
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-4px);
    }
</style>
@endsection
