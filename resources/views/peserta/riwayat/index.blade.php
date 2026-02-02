@extends('layouts.app-bootstrap')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
        <h5 class="mb-0 text-white"><i class="bi bi-clock-history me-2"></i> Riwayat Pembayaran Saya</h5>
    </div>
    <div class="card-body p-4">
        @forelse($pendaftarans as $p)
        <div class="card border-0 shadow-sm mb-4 transition-all" style="transition: all 0.3s ease; border-left: 4px solid #667eea; hover-effect: true;">
            <div class="card-body p-4">
                <div class="row align-items-start">
                    <div class="col-md-7">
                        <h5 class="mb-3 fw-bold text-dark">
                            <i class="bi bi-book text-primary"></i> {{ $p->kursus->nama_kursus ?? $p->kursus->nama }}
                        </h5>
                        
                        <div class="row mb-4 g-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Total Biaya</small>
                                <p class="mb-0 fs-6"><strong class="text-dark">Rp {{ number_format($p->total_bayar) }}</strong></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Terbayar</small>
                                <p class="mb-0 fs-6"><strong class="text-success">Rp {{ number_format($p->terbayar) }}</strong></p>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Sisa Pembayaran</small>
                                <p class="mb-0 fs-6"><strong class="text-danger">Rp {{ number_format($p->total_bayar - $p->terbayar) }}</strong></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Status</small>
                                <p class="mb-0">
                                    @if($p->isLunas())
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> LUNAS</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle"></i> BELUM LUNAS</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-5">
                        <div class="progress" style="height: 30px; border-radius: 10px; overflow: hidden;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ $p->progress() }}%; background: linear-gradient(90deg, #4f46e5, #7c3aed); font-weight: 600;" 
                                 aria-valuenow="{{ $p->progress() }}" aria-valuemin="0" aria-valuemax="100">
                                <small class="text-white" style="font-weight: 600;">{{ $p->progress() }}%</small>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">Progress Pembayaran</small>
                    </div>
                </div>
                
                <hr class="my-4" style="border-top: 1px solid #e0e0e0;">
                
                <!-- Detail Angsuran -->
                <div>
                    <h6 class="mb-3 fw-bold text-dark"><i class="bi bi-list-check text-primary me-2"></i> Detail Angsuran</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th class="border-0 fw-bold text-muted">Angsuran</th>
                                    <th class="border-0 fw-bold text-muted">Jumlah</th>
                                    <th class="border-0 fw-bold text-muted">Tanggal Bayar</th>
                                    <th class="border-0 fw-bold text-muted">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($p->pembayarans as $bayar)
                                <tr style="transition: background-color 0.2s ease;">
                                    <td class="border-0"><strong class="text-dark">{{ $bayar->angsuran_ke }}</strong></td>
                                    <td class="border-0 text-dark">Rp {{ number_format($bayar->jumlah) }}</td>
                                    <td class="border-0 text-muted">{{ $bayar->updated_at ? $bayar->updated_at->format('d/m/Y H:i') : '-' }}</td>
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
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        Belum ada data pembayaran
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <div style="font-size: 3rem; color: #ddd;" class="mb-3">
                <i class="bi bi-inbox"></i>
            </div>
            <h5 class="mt-3 fw-bold text-dark">Belum ada riwayat pembayaran</h5>
            <p class="text-muted mb-4">Daftarkan diri Anda ke kursus untuk memulai pembayaran</p>
            <a href="{{ url('/peserta/kursus') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-bookmark me-2"></i> Lihat Daftar Kursus
            </a>
        </div>
        @endforelse
    </div>
</div>

<style>
    .card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-2px);
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .progress {
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    
    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
</style>
@endsection
