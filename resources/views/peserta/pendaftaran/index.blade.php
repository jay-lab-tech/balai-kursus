@extends('layouts.app-bootstrap')

@section('title', 'Pendaftaran Kursus')

@section('content')
<div class="container-fluid py-4">
    <h2 class="fw-bold text-dark mb-4"><i class="bi bi-file-text me-2"></i>Riwayat Pendaftaran</h2>

    @forelse($pendaftarans as $p)
        <div class="card border-0 shadow-sm mb-4" style="transition: all 0.3s ease; border-left: 4px solid #667eea;">
            <div class="card-body p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-book me-2 text-primary"></i>{{ $p->kursus->nama }}</h5>
                
                <div class="row mb-4 g-3">
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Total Biaya</small>
                        <p class="mb-0 fw-bold">Rp {{ number_format($p->total_bayar) }}</p>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Terbayar</small>
                        <p class="mb-0 fw-bold text-success">Rp {{ number_format($p->terbayar) }}</p>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Status</small>
                        <p class="mb-0">
                            <span class="badge bg-info">{{ ucfirst($p->status_pembayaran) }}</span>
                        </p>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Sisa</small>
                        <p class="mb-0 fw-bold text-danger">Rp {{ number_format($p->total_bayar - $p->terbayar) }}</p>
                    </div>
                </div>

                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-receipt me-2 text-primary"></i>Riwayat Pembayaran</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-sm table-hover mb-0">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th class="fw-bold text-muted border-0">Angsuran Ke</th>
                                <th class="fw-bold text-muted border-0">Jumlah</th>
                                <th class="fw-bold text-muted border-0">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($p->pembayarans as $bayar)
                                <tr>
                                    <td class="border-0">Angsuran {{ $bayar->angsuran_ke }}</td>
                                    <td class="border-0 fw-500">Rp {{ number_format($bayar->jumlah) }}</td>
                                    <td class="border-0">
                                        @php
                                            $status = strtolower($bayar->status);
                                        @endphp
                                        @if($status === 'verified')
                                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Verified</span>
                                        @elseif($status === 'pending')
                                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Pending</span>
                                        @elseif($status === 'rejected')
                                            <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <hr class="my-3">

                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-credit-card me-2 text-primary"></i>Bayar Angsuran</h6>
                <form action="/peserta/bayar/{{ $p->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="jumlah" class="form-label fw-500">Jumlah Bayar</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Masukkan jumlah bayar" required>
                        </div>
                        <div class="col-md-6">
                            <label for="bukti" class="form-label fw-500">Bukti Pembayaran</label>
                            <input type="file" class="form-control" id="bukti" name="bukti" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload me-2"></i>Upload Bukti Pembayaran
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @empty
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Informasi:</strong> Belum ada pendaftaran. Silakan daftar ke kursus terlebih dahulu.
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



