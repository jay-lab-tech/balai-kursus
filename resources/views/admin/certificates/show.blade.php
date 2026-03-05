@extends('layouts.app-bootstrap')

@section('title', 'Detail Sertifikat')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-award me-2"></i>Detail Sertifikat</h2>
        <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline-secondary btn-lg">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    {{-- Status Messages --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Left: Info Card --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-0 bg-light py-3">
                    <h5 class="mb-0 fw-bold text-dark">{{ $certificate->no_sertifikat }}</h5>
                </div>
                <div class="card-body">
                    {{-- Status --}}
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-sm-4">
                            <label class="form-label fw-bold text-muted"><i class="bi bi-tag me-2"></i>Status</label>
                        </div>
                        <div class="col-sm-8">
                            @if ($certificate->status === 'generated')
                                <span class="badge bg-success text-white fs-6"><i class="bi bi-hourglass-split me-1"></i>Di-Generate</span>
                            @elseif ($certificate->status === 'applied')
                                <span class="badge bg-primary text-white fs-6"><i class="bi bi-check-circle me-1"></i>Diterbitkan</span>
                            @elseif ($certificate->status === 'rejected')
                                <span class="badge bg-warning text-dark fs-6"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                            @elseif ($certificate->status === 'revoked')
                                <span class="badge bg-danger text-white fs-6"><i class="bi bi-slash-circle me-1"></i>Dicabut</span>
                            @else
                                <span class="badge bg-secondary text-white fs-6">{{ $certificate->status }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Peserta --}}
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-sm-4">
                            <label class="form-label fw-bold text-muted"><i class="bi bi-person me-2"></i>Peserta</label>
                        </div>
                        <div class="col-sm-8">
                            <strong>{{ $certificate->peserta->nama ?? '-' }}</strong>
                            <div class="text-muted small mt-1">{{ $certificate->peserta->nomor_peserta ?? '-' }}</div>
                        </div>
                    </div>

                    {{-- Kursus --}}
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-sm-4">
                            <label class="form-label fw-bold text-muted"><i class="bi bi-book me-2"></i>Kursus</label>
                        </div>
                        <div class="col-sm-8">
                            <strong>{{ $certificate->kursus->nama ?? $certificate->kursus->judul ?? '-' }}</strong>
                        </div>
                    </div>

                    {{-- Tanggal Terbit --}}
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-sm-4">
                            <label class="form-label fw-bold text-muted"><i class="bi bi-calendar me-2"></i>Tanggal Terbit</label>
                        </div>
                        <div class="col-sm-8">
                            {{ optional($certificate->issued_at)->format('d M Y H:i') ?? '<span class="text-muted">-</span>' }}
                        </div>
                    </div>

                    {{-- Generated At --}}
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-sm-4">
                            <label class="form-label fw-bold text-muted"><i class="bi bi-hourglass-split me-2"></i>Di-Generate</label>
                        </div>
                        <div class="col-sm-8">
                            {{ optional($certificate->generated_at)->format('d M Y H:i') ?? '<span class="text-muted">-</span>' }}
                        </div>
                    </div>

                    {{-- Verification Code --}}
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-sm-4">
                            <label class="form-label fw-bold text-muted"><i class="bi bi-key me-2"></i>Kode Verifikasi</label>
                        </div>
                        <div class="col-sm-8">
                            <code class="bg-light p-2 rounded">{{ $certificate->verification_code }}</code>
                        </div>
                    </div>

                    {{-- Masa Berlaku --}}
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label class="form-label fw-bold text-muted"><i class="bi bi-alarm me-2"></i>Masa Berlaku</label>
                        </div>
                        <div class="col-sm-8">
                            @if ($certificate->expires_at)
                                <strong>Sampai {{ $certificate->expires_at->format('d M Y') }}</strong>
                                @if ($certificate->getExpiryStatus() === 'expired')
                                    <span class="badge bg-danger ms-2"><i class="bi bi-exclamation-triangle me-1"></i>Kadaluarsa</span>
                                @elseif ($certificate->getExpiryStatus() === 'active')
                                    <span class="badge bg-success ms-2"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                                    @if ($certificate->daysUntilExpiry() <= 7)
                                        <span class="badge bg-warning ms-2 text-dark"><i class="bi bi-exclamation-triangle me-1"></i>Akan Berakhir</span>
                                    @endif
                                @endif
                            @else
                                <span class="text-muted">Selamanya</span>
                            @endif
                        </div>
                    </div>

                    {{-- Revocation Info --}}
                    @if ($certificate->status === 'revoked')
                        <div class="alert alert-danger border-0 mt-4 mb-0" role="alert">
                            <h6 class="mb-3 fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Informasi Pencabutan</h6>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted small fw-bold">Dicabut pada:</div>
                                <div class="col-sm-8">{{ optional($certificate->revoked_at)->format('d M Y H:i') }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4 text-muted small fw-bold">Dicabut oleh:</div>
                                <div class="col-sm-8">{{ optional($certificate->revokedBy)->name ?? '-' }}</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 text-muted small fw-bold">Alasan:</div>
                                <div class="col-sm-8">{{ $certificate->revoked_reason }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Actions --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header border-0 bg-light py-3">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-lightning me-2"></i>Aksi</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    {{-- Apply (Terbitkan) --}}
                    @if ($certificate->status === 'generated' && $certificate->file_path)
                        <form method="post" action="{{ route('admin.certificates.apply', $certificate) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Terbitkan sertifikat ini dan kirim email ke peserta?')">
                                <i class="bi bi-check-circle me-2"></i>Terbitkan & Kirim Email
                            </button>
                        </form>
                    @endif

                    {{-- Reject --}}
                    @if ($certificate->status === 'generated')
                        <a href="#" class="btn btn-warning w-100" data-bs-toggle="collapse" data-bs-target="#rejectForm">
                            <i class="bi bi-x-circle me-2"></i>Tolak
                        </a>
                        <div class="collapse mt-2" id="rejectForm">
                            <form method="post" action="{{ route('admin.certificates.reject', $certificate) }}">
                                @csrf
                                <div class="mb-2">
                                    <textarea name="reject_reason" class="form-control form-control-sm" placeholder="Alasan penolakan..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-warning btn-sm w-100">Tolak</button>
                            </form>
                        </div>
                    @endif

                    {{-- Re-apply (Terbitkan Kembali) --}}
                    @if ($certificate->status === 'rejected' && $certificate->file_path)
                        <form method="post" action="{{ route('admin.certificates.reapply', $certificate) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-info w-100" onclick="return confirm('Terbitkan kembali dan kirim email ke peserta?')">
                                <i class="bi bi-arrow-clockwise me-2"></i>Terbitkan Kembali
                            </button>
                        </form>
                    @endif

                    {{-- Download --}}
                    @if ($certificate->file_path)
                        <a href="{{ route('certificate.download', $certificate->id) }}" class="btn btn-primary w-100" target="_blank">
                            <i class="bi bi-download me-2"></i>Unduh PDF
                        </a>
                    @endif

                    {{-- View Public Verification --}}
                    <a href="{{ route('certificate.verify', $certificate->verification_code) }}" class="btn btn-outline-primary w-100" target="_blank">
                        <i class="bi bi-search me-2"></i>Buka Verifikasi Publik
                    </a>

                    {{-- Regenerate --}}
                    @if ($certificate->status !== 'applied' || !$certificate->file_path)
                        <form method="post" action="{{ route('admin.certificates.regenerate', $certificate) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning w-100" onclick="return confirm('Regenerate sertifikat ini?')">
                                <i class="bi bi-arrow-repeat me-2"></i>Regenerate
                            </button>
                        </form>
                    @endif

                    {{-- Revoke --}}
                    @if ($certificate->status !== 'revoked')
                        <a href="{{ route('admin.certificates.editRevoke', $certificate) }}" class="btn btn-danger w-100">
                            <i class="bi bi-shield-x me-2"></i>Cabut Sertifikat
                        </a>
                    @endif
                </div>
            </div>

            {{-- QR Code Preview --}}
            @php
                $verifyUrl = route('certificate.verify', $certificate->verification_code);
            @endphp
            <div class="card border-0 shadow-sm">
                <div class="card-header border-0 bg-light py-3">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-qr-code me-2"></i>QR Code</h6>
                </div>
                <div class="card-body text-center">
                    @if (class_exists(\Endroid\QrCode\Writer\PngWriter::class))
                        @php
                            $qrCode = \Endroid\QrCode\QrCode::create($verifyUrl);
                            $writer = new \Endroid\QrCode\Writer\PngWriter();
                            $result = $writer->write($qrCode);
                            $qr = base64_encode($result->getString());
                        @endphp
                        <img src="data:image/png;base64,{{ $qr }}" class="img-fluid rounded border" style="max-width: 200px;">
                        <p class="small mt-3 text-muted mb-0">{{ substr($verifyUrl, 0, 25) }}...</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
