@extends('layouts.app-bootstrap')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="container-fluid py-4">
    <h2 class="fw-bold text-dark mb-4"><i class="bi bi-credit-card me-2"></i>Verifikasi Pembayaran</h2>

    <div class="row g-4">
        @forelse($pembayarans as $p)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm" style="transition: all 0.3s ease;">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-2">
                        <i class="bi bi-person me-2"></i>{{ $p->pendaftaran->peserta->user->name }}
                    </h6>
                    
                    <div class="mb-3 p-2 bg-light rounded">
                        <small class="text-muted d-block">Jumlah Pembayaran</small>
                        <p class="mb-0 fw-bold text-primary">Rp {{ number_format($p->jumlah) }}</p>
                    </div>

                    <small class="text-muted d-block mb-2">Bukti Pembayaran:</small>
                    <div class="mb-3">
                        <img src="{{ asset('storage/'.$p->bukti_path) }}" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                    </div>

                    <form action="/admin/pembayaran/{{ $p->id }}/verifikasi" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle me-2"></i>VERIFIKASI
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Informasi:</strong> Tidak ada pembayaran yang menunggu verifikasi.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endforelse
    </div>
</div>

<style>
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-4px);
    }
</style>
@endsection
