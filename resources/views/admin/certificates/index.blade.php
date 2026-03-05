@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h1>Manajemen Sertifikat</h1>
        </div>
        <div class="col-auto d-flex gap-2">
            <a href="{{ route('admin.certificates.create') }}" class="btn btn-primary">
                + Terbitkan Sertifikat
            </a>
            <a href="{{ route('admin.certificates.batch.create') }}" class="btn btn-info">
                📋 Massal
            </a>
            <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">
                🎨 Template
            </a>
            <a href="{{ route('admin.certificates.analytics') }}" class="btn btn-success">
                📊 Analytics
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
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
        <div class="alert alert-success alert-dismissible fade show">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Table --}}
    <div class="card">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Sertifikat</th>
                    <th>Peserta</th>
                    <th>Kursus</th>
                    <th>Status</th>
                    <th>Terbit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($certificates as $cert)
                    <tr>
                        <td>
                            <strong>{{ $cert->no_sertifikat }}</strong>
                        </td>
                        <td>{{ $cert->peserta->nama ?? '-' }}</td>
                        <td>{{ $cert->kursus->judul ?? '-' }}</td>
                        <td>
                            @if ($cert->status === 'generated')
                                <span class="badge bg-success">Generated</span>
                            @elseif ($cert->status === 'queued')
                                <span class="badge bg-warning">Antri</span>
                            @elseif ($cert->status === 'revoked')
                                <span class="badge bg-danger">Dicabut</span>
                            @endif
                        </td>
                        <td>{{ optional($cert->issued_at)->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.certificates.show', $cert) }}" class="btn btn-sm btn-info">
                                Detail
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

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $certificates->links() }}
    </div>
</div>
@endsection
