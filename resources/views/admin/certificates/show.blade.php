@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h1>Detail Sertifikat</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline-secondary">
                ← Kembali
            </a>
        </div>
    </div>

    {{-- Status Messages --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- Left: Info Card --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">{{ $certificate->no_sertifikat }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Status</div>
                        <div class="col-sm-8">
                            @if ($certificate->status === 'generated')
                                <span class="badge bg-success">Generated</span>
                            @elseif ($certificate->status === 'queued')
                                <span class="badge bg-warning">Antri</span>
                            @elseif ($certificate->status === 'revoked')
                                <span class="badge bg-danger">Dicabut</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Peserta</div>
                        <div class="col-sm-8">
                            <strong>{{ $certificate->peserta->nama ?? '-' }}</strong>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Kursus</div>
                        <div class="col-sm-8">
                            <strong>{{ $certificate->kursus->judul ?? '-' }}</strong>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Terbit</div>
                        <div class="col-sm-8">
                            {{ optional($certificate->issued_at)->format('d M Y H:i') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Di-generate</div>
                        <div class="col-sm-8">
                            {{ optional($certificate->generated_at)->format('d M Y H:i') ?? '-' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Kode Verifikasi</div>
                        <div class="col-sm-8">
                            <code>{{ $certificate->verification_code }}</code>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Masa Berlaku</div>
                        <div class="col-sm-8">
                            @if ($certificate->expires_at)
                                sampai {{ $certificate->expires_at->format('d M Y') }}
                                @if ($certificate->getExpiryStatus() === 'expired')
                                    <span class="badge bg-danger">Kadaluarsa</span>
                                @elseif ($certificate->getExpiryStatus() === 'active')
                                    <span class="badge bg-success">Aktif</span>
                                    @if ($certificate->daysUntilExpiry() <= 7)
                                        <span class="badge bg-warning">Akan Berakhir</span>
                                    @endif
                                @endif
                            @else
                                <span class="text-muted">Selamanya</span>
                            @endif
                        </div>
                    </div>

                    {{-- Revocation Info --}}
                    @if ($certificate->status === 'revoked')
                        <hr>
                        <h6>Informasi Pencabutan</h6>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">Dicabut pada</div>
                            <div class="col-sm-8">
                                {{ optional($certificate->revoked_at)->format('d M Y H:i') }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">Dicabut oleh</div>
                            <div class="col-sm-8">
                                {{ optional($certificate->revokedBy)->name ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">Alasan</div>
                            <div class="col-sm-8">
                                <p class="mb-0">{{ $certificate->revoked_reason }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Actions --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Aksi</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    {{-- Download --}}
                    @if ($certificate->status === 'generated' && $certificate->file_path)
                        <a href="{{ route('certificate.download', $certificate->id) }}" class="btn btn-primary btn-sm"
                            target="_blank">
                            📥 Unduh PDF
                        </a>
                    @endif

                    {{-- View Public Verification --}}
                    <a href="{{ route('certificate.verify', $certificate->verification_code) }}" class="btn btn-outline-primary btn-sm"
                        target="_blank">
                        🔍 Buka Verifikasi Publik
                    </a>

                    {{-- Regenerate --}}
                    @if ($certificate->status !== 'generated' || !$certificate->file_path)
                        <form method="post" action="{{ route('admin.certificates.regenerate', $certificate) }}"
                            style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm w-100"
                                onclick="return confirm('Regenerate sertifikat ini?')">
                                🔄 Regenerate
                            </button>
                        </form>
                    @endif

                    {{-- Revoke --}}
                    @if ($certificate->status !== 'revoked')
                        <a href="{{ route('admin.certificates.editRevoke', $certificate) }}" class="btn btn-danger btn-sm">
                            ✗ Cabut Sertifikat
                        </a>
                    @endif
                </div>
            </div>

            {{-- QR Code Preview --}}
            @php
                $verifyUrl = route('certificate.verify', $certificate->verification_code);
            @endphp
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">QR Code</h6>
                </div>
                <div class="card-body text-center">
                    @if (class_exists(\Endroid\QrCode\Writer\PngWriter::class))
                        @php
                            $qrCode = \Endroid\QrCode\QrCode::create($verifyUrl);
                            $writer = new \Endroid\QrCode\Writer\PngWriter();
                            $result = $writer->write($qrCode);
                            $qr = base64_encode($result->getString());
                        @endphp
                        <img src="data:image/png;base64,{{ $qr }}" class="img-fluid" style="max-width: 180px">
                        <p class="small mt-2 text-muted">{{ $verifyUrl }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
