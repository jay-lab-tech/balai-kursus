@extends('layouts.app-bootstrap')

@section('title', 'Dashboard Instruktur')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-house me-2"></i>Dashboard Instruktur</h2>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
            </button>
        </form>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm" style="border-left: 4px solid #667eea;">
                <div class="card-body">
                    <h6 class="card-title fw-bold text-dark mb-3">
                        <i class="bi bi-book me-2 text-primary"></i>Kelola Kursus
                    </h6>
                    <p class="card-text text-muted mb-3">
                        Kelola semua kursus yang Anda ajar
                    </p>
                    <a href="/instruktur/kursus" class="btn btn-primary btn-sm">
                        <i class="bi bi-arrow-right me-2"></i>Akses
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
