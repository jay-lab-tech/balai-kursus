@extends('layouts.app-bootstrap')

@section('title', 'Pendaftaran Kursus')

@section('content')
<!-- Load Midtrans Snap -->
@if(config('midtrans.is_production'))
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif

<div class="container-fluid py-4">
    <h2 class="fw-bold text-dark mb-4"><i class="bi bi-file-text me-2"></i>Riwayat Pendaftaran</h2>

    @forelse($pendaftarans as $p)
        <div class="card border-0 shadow-sm mb-4" style="transition: all 0.3s ease; border-left: 4px solid #667eea;">
            <div class="card-body p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-book me-2 text-primary"></i>{{ $p->kursus->nama ?? 'N/A' }}</h5>
                
                <div class="row mb-4 g-3">
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Total Biaya</small>
                        <p class="mb-0 fw-bold">Rp {{ number_format($p->total_bayar ?? 0) }}</p>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Terbayar</small>
                        <p class="mb-0 fw-bold text-success">Rp {{ number_format($p->terbayar ?? 0) }}</p>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Status</small>
                        <p class="mb-0">
                            <span class="badge bg-info">{{ ucfirst($p->status_pembayaran ?? 'pending') }}</span>
                        </p>
                    </div>
                    <div class="col-6 col-md-3">
                        <small class="text-muted d-block">Sisa</small>
                        <p class="mb-0 fw-bold text-danger">Rp {{ number_format(($p->total_bayar ?? 0) - ($p->terbayar ?? 0)) }}</p>
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
                            @forelse($p->pembayarans as $bayar)
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
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Belum ada riwayat pembayaran</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <hr class="my-3">

                @if($p->isLunas())
                    <div class="alert alert-success" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Pembayaran Lunas!</strong> Anda sudah menyelesaikan semua pembayaran untuk kursus ini.
                    </div>
                @else
                    <div class="row g-3">
                        <!-- Payment Method Tabs -->
                        <div class="col-12">
                            <ul class="nav nav-tabs" id="paymentTabs{{ $p->id }}" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="online-tab{{ $p->id }}" data-bs-toggle="tab" 
                                            data-bs-target="#online{{ $p->id }}" type="button" role="tab">
                                        <i class="bi bi-credit-card me-2"></i>Pembayaran Online
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="manual-tab{{ $p->id }}" data-bs-toggle="tab" 
                                            data-bs-target="#manual{{ $p->id }}" type="button" role="tab">
                                        <i class="bi bi-upload me-2"></i>Upload Bukti
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="tab-content" id="paymentTabsContent{{ $p->id }}">
                        <!-- Online Payment Tab -->
                        <div class="tab-pane fade show active" id="online{{ $p->id }}" role="tabpanel">
                            <div class="mt-4 card border-0 bg-light p-4">
                                <p class="text-muted mb-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Bayar menggunakan berbagai metode pembayaran (kartu kredit, transfer bank, e-wallet, dll)
                                </p>
                                
                                <div class="mb-3">
                                    <label for="amountOnline{{ $p->id }}" class="form-label fw-500">Nominal Pembayaran</label>
                                    <small class="text-muted d-block mb-2">
                                        Sisa Pembayaran: Rp {{ number_format($p->sisa()) }}
                                    </small>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="amountOnline{{ $p->id }}" 
                                               placeholder="Masukkan nominal" max="{{ $p->sisa() }}" 
                                               min="1000" step="1000" value="{{ $p->sisa() }}">
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        Minimal: Rp 1.000 | Maksimal: Rp {{ number_format($p->sisa()) }}
                                    </small>
                                </div>

                                <button type="button" class="btn btn-success w-100" 
                                        onclick="processOnlinePayment('{{ $p->id }}')">
                                    <i class="bi bi-credit-card me-2"></i>Lanjutkan ke Pembayaran
                                </button>
                            </div>
                        </div>

                        <!-- Manual Payment Tab -->
                        <div class="tab-pane fade" id="manual{{ $p->id }}" role="tabpanel">
                            <div class="mt-4">
                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-receipt me-2 text-primary"></i>Bayar Angsuran</h6>
                                <form action="{{ route('peserta.bayar', $p->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="jumlah" class="form-label fw-500">Jumlah Bayar</label>
                                            <small class="text-muted d-block mb-2">Maksimal: Rp {{ number_format($p->sisa()) }}</small>
                                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror" 
                                                   id="jumlah" name="jumlah" placeholder="Masukkan jumlah bayar" 
                                                   max="{{ $p->sisa() }}" min="1000" step="1000" required>
                                            @error('jumlah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="bukti" class="form-label fw-500">Bukti Pembayaran</label>
                                            <input type="file" class="form-control @error('bukti') is-invalid @enderror" 
                                                   id="bukti" name="bukti" accept="image/*" required>
                                            @error('bukti')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
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
                    </div>
                @endif
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

<script>
    function processOnlinePayment(pendaftaranId) {
        const amountInput = document.getElementById('amountOnline' + pendaftaranId);
        const amount = parseInt(amountInput.value);

        // Validate amount
        if (!amount || amount < 1000) {
            alert('Nominal pembayaran minimal Rp 1.000');
            return;
        }

        // Disable button
        event.target.disabled = true;
        event.target.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memproses...';

        // Send request to create payment
        fetch(`/peserta/pembayaran-online/${pendaftaranId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                amount: amount
            })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.snap_token) {
                // Show Snap payment popup
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        // Redirect to success page
                        window.location.href = `/peserta/pembayaran-success/${data.order_id}`;
                    },
                    onPending: function(result) {
                        console.log('Payment pending', result);
                    },
                    onError: function(result) {
                        // Redirect to failed page
                        window.location.href = `/peserta/pembayaran-failed/${data.order_id}`;
                    },
                    onClose: function() {
                        // Re-enable button
                        event.target.disabled = false;
                        event.target.innerHTML = '<i class="bi bi-credit-card me-2"></i>Lanjutkan ke Pembayaran';
                    }
                });
            } else {
                throw new Error(data.error || 'Gagal membuat pembayaran');
            }
        })
        .catch((error) => {
            alert('Error: ' + error.message);
            // Re-enable button
            event.target.disabled = false;
            event.target.innerHTML = '<i class="bi bi-credit-card me-2"></i>Lanjutkan ke Pembayaran';
        });
    }
</script>

@endsection



