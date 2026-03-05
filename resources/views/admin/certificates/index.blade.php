@extends('layouts.app-bootstrap')

@section('title', 'Manajemen Sertifikat')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-award me-2"></i>Manajemen Sertifikat</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.certificates.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-circle me-2"></i>Terbitkan
            </a>
            <a href="{{ route('admin.certificates.batch.create') }}" class="btn btn-info btn-lg">
                <i class="bi bi-file-earmark-text me-2"></i>Massal
            </a>
            <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary btn-lg">
                <i class="bi bi-palette me-2"></i>Template
            </a>
            <a href="{{ route('admin.certificates.analytics') }}" class="btn btn-success btn-lg">
                <i class="bi bi-graph-up me-2"></i>Analytics
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="get" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Cari No. / Nama</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                        placeholder="Cari sertifikat atau peserta...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="queued" {{ request('status') === 'queued' ? 'selected' : '' }}>Antri</option>
                        <option value="generated" {{ request('status') === 'generated' ? 'selected' : '' }}>Generated</option>
                        <option value="revoked" {{ request('status') === 'revoked' ? 'selected' : '' }}>Dicabut</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kursus</label>
                    <select name="kursus_id" class="form-select">
                        <option value="">Semua Kursus</option>
                        @foreach (\App\Models\Kursus::all() as $k)
                            <option value="{{ $k->id }}" {{ request('kursus_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->judul }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
                </div>
            </form>
        </div>
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

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <table class="table table-hover mb-0">
            <thead style="background-color: #f8f9fa;">
                <tr>
                    <th class="fw-bold text-muted border-0"><i class="bi bi-hash me-2"></i>No. Sertifikat</th>
                    <th class="fw-bold text-muted border-0"><i class="bi bi-person me-2"></i>Peserta</th>
                    <th class="fw-bold text-muted border-0"><i class="bi bi-book me-2"></i>Kursus</th>
                    <th class="fw-bold text-muted border-0"><i class="bi bi-tag me-2"></i>Status</th>
                    <th class="fw-bold text-muted border-0"><i class="bi bi-calendar me-2"></i>Terbit</th>
                    <th class="fw-bold text-muted border-0">Aksi</th>
                </tr>
            </thead>
            <tbody id="certificateTableBody">
                @forelse ($certificates as $cert)
                    <tr style="transition: background-color 0.2s ease;">
                        <td class="border-0 fw-bold">{{ $cert->no_sertifikat }}</td>
                        <td class="border-0">{{ $cert->peserta->nama ?? '-' }}</td>
                        <td class="border-0">{{ $cert->kursus->nama ?? '-' }}</td>
                        <td class="border-0">
                            @if ($cert->status === 'generated')
                                <span class="badge bg-success text-white"><i class="bi bi-check-circle me-1"></i>Generated</span>
                            @elseif ($cert->status === 'applied')
                                <span class="badge bg-primary text-white"><i class="bi bi-check2-all me-1"></i>Diterbitkan</span>
                            @elseif ($cert->status === 'rejected')
                                <span class="badge bg-warning text-dark"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                            @elseif ($cert->status === 'revoked')
                                <span class="badge bg-danger text-white"><i class="bi bi-slash-circle me-1"></i>Dicabut</span>
                            @else
                                <span class="badge bg-secondary text-white">{{ $cert->status }}</span>
                            @endif
                        </td>
                        <td class="border-0 text-muted">{{ optional($cert->issued_at)->format('d M Y') }}</td>
                        <td class="border-0">
                            <a href="{{ route('admin.certificates.show', $cert) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Tidak ada sertifikat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    </div>
    
    {{-- Pagination --}}
    <div class="mt-4">
        {{ $certificates->links() }}
    </div>
</div>
@endsection

@push('styles')
<style>
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush
