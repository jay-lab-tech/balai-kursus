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
                                <th class="fw-bold text-muted border-0">Tanggal</th>
                                <th class="fw-bold text-muted border-0">Jumlah</th>
                                <th class="fw-bold text-muted border-0">Metode</th>
                                <th class="fw-bold text-muted border-0">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($p->payments()->where('status', 'success')->get() as $bayar)
                                <tr>
                                    <td class="border-0 text-muted">{{ $bayar->created_at->format('d M Y H:i') }}</td>
                                    <td class="border-0 fw-500">Rp {{ number_format($bayar->amount) }}</td>
                                    <td class="border-0">{{ ucfirst($bayar->payment_method ?? 'Midtrans') }}</td>
                                    <td class="border-0">
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Berhasil</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Belum ada pembayaran</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <hr class="my-3">

                @if($p->isLunas())
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Pembayaran Lunas!</strong> Anda sudah menyelesaikan semua pembayaran untuk kursus ini.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @else
                    <!-- Midtrans Payment Section -->
                    <div class="card border-0 bg-light p-4">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-credit-card me-2 text-success"></i>Pembayaran Online</h6>
                        <p class="text-muted mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            Bayar menggunakan Midtrans: Transfer Bank, E-Wallet (GoPay, Dana, OVO), Kartu Kredit, dan metode lainnya.
                        </p>
                        
                        <div class="mb-3">
                            <label for="amountOnline{{ $p->id }}" class="form-label fw-500">Nominal Pembayaran</label>
                            <small class="text-muted d-block mb-2">
                                Sisa yang harus dibayar: <span class="fw-bold text-danger">Rp {{ number_format($p->sisa()) }}</span>
                            </small>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="amountOnline{{ $p->id }}" 
                                       placeholder="Masukkan nominal" max="{{ $p->sisa() }}" 
                                       min="1000" step="1000" value="{{ $p->sisa() }}">
                            </div>
                            <small class="text-muted d-block mt-2">
                                💡 Minimal: Rp 1.000 &nbsp;|&nbsp; Maksimal: Rp {{ number_format($p->sisa()) }} 
                                <br>Bisa membayar sebagian atau penuh sesuai kemampuan
                            </small>
                        </div>

                        <button type="button" class="btn btn-success w-100 btn-lg" 
                                id="payBtn{{ $p->id }}"
                                onclick="processOnlinePayment('{{ $p->id }}')">
                            <i class="bi bi-credit-card me-2"></i>Lanjutkan ke Pembayaran
                        </button>
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
        const payBtn = document.getElementById('payBtn' + pendaftaranId);
        const amount = parseInt(amountInput.value);

        // Validate amount
        if (!amount || amount < 1000) {
            alert('Nominal pembayaran minimal Rp 1.000');
            return;
        }

        // Disable button
        payBtn.disabled = true;
        const originalText = payBtn.innerHTML;
        payBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memproses...';

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
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.error || 'Gagal membuat pembayaran');
                });
            }
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
                        // Redirect to success page anyway (payment is processing)
                        window.location.href = `/peserta/pembayaran-success/${data.order_id}`;
                    },
                    onError: function(result) {
                        // Redirect to failed page
                        window.location.href = `/peserta/pembayaran-failed/${data.order_id}`;
                    },
                    onClose: function() {
                        // Re-enable button
                        payBtn.disabled = false;
                        payBtn.innerHTML = originalText;
                    }
                });
            } else {
                throw new Error(data.error || 'Gagal membuat pembayaran');
            }
        })
        .catch((error) => {
            alert('Error: ' + error.message);
            // Re-enable button
            payBtn.disabled = false;
            payBtn.innerHTML = originalText;
        });
    }
</script>

@endsection



