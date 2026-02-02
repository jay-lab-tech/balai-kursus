@extends('layouts.app-bootstrap')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-house me-2"></i>Dashboard Peserta</h2>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
            </button>
        </form>
    </div>

    @forelse($pendaftarans as $p)
        <div class="card border-0 shadow-sm mb-4" style="transition: all 0.3s ease; border-left: 4px solid #667eea;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-book me-2 text-primary"></i>{{ $p->kursus->nama }}</h5>
                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Total Biaya</small>
                                <p class="mb-0 fw-bold">Rp {{ number_format($p->total_bayar) }}</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Terbayar</small>
                                <p class="mb-0 fw-bold text-success">Rp {{ number_format($p->terbayar) }}</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Sisa Pembayaran</small>
                                <p class="mb-0 fw-bold text-danger">Rp {{ number_format($p->sisa()) }}</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Status</small>
                                @if($p->isLunas())
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>LUNAS</span>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle me-1"></i>BELUM LUNAS</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="progress mb-2" style="height: 30px; border-radius: 10px; overflow: hidden;">
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ $p->progress() }}%; background: linear-gradient(90deg, #667eea, #764ba2); font-weight: 600;"
                                 aria-valuenow="{{ $p->progress() }}" aria-valuemin="0" aria-valuemax="100">
                                <small class="text-white">{{ $p->progress() }}%</small>
                            </div>
                        </div>
                        <small class="text-muted d-block text-center">Progress Pembayaran</small>
                    </div>
                </div>

                @if(!$p->isLunas())
                    <hr class="my-3">
                    <a href="{{ url('/peserta/pendaftaran') }}" class="btn btn-primary">
                        <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                    </a>
                @endif
            </div>
        </div>
    @empty
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Informasi:</strong> Belum ada kursus yang diikuti. Silakan daftar ke kursus terlebih dahulu.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endforelse
</div>

<style>
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-2px);
    }
</style>
@endsection
