@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1>Verifikasi Sertifikat</h1>
    @if($certificate->status === 'revoked')
        <div class="alert alert-danger mt-3">
            <strong>⚠️ Sertifikat Dicabut</strong>
            <p class="mb-0">Sertifikat ini telah dicabut pada {{ optional($certificate->revoked_at)->format('d M Y H:i') }}.</p>
            @if($certificate->revoked_reason)
                <p class="small text-muted mb-0 mt-2"><strong>Alasan:</strong> {{ $certificate->revoked_reason }}</p>
            @endif
        </div>
    @endif

    <div class="card mt-4">
        <div class="card-header bg-light">
            <h5>Detail Sertifikat</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <tr><th>No. Sertifikat</th><td>{{ $certificate->no_sertifikat }}</td></tr>
                <tr><th>Nama</th><td>{{ $certificate->peserta->nama ?? '-' }}</td></tr>
                <tr><th>Kursus</th><td>{{ $certificate->kursus->judul ?? '-' }}</td></tr>
                <tr><th>Tanggal</th><td>{{ optional($certificate->issued_at)->format('d M Y') }}</td></tr>
                <tr><th>Status</th><td>
                    @if($certificate->status === 'generated')
                        <span class="badge bg-success">Valid</span>
                    @elseif($certificate->status === 'queued')
                        <span class="badge bg-warning">Diproses</span>
                    @elseif($certificate->status === 'revoked')
                        <span class="badge bg-danger">Dicabut</span>
                    @endif
                </td></tr>
            </table>
        </div>
    </div>

    @if($certificate->status === 'generated' && $certificate->file_path)
        <div class="mt-4">
            <a href="{{ route('certificate.download', $certificate->id) }}" class="btn btn-primary">
                📥 Unduh Sertifikat PDF
            </a>
        </div>
    @endif
</div>
@endsection
