@extends('peserta::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-0">
            <i class="bi bi-bookmark-check me-2"></i>Kursus Saya
        </h2>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($pendaftarans && count($pendaftarans) > 0)
        <div class="row">
            @foreach($pendaftarans as $p)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100 d-flex flex-column">
                    <div class="card-body flex-grow-1">
                        <div class="mb-3">
                            <h5 class="card-title fw-bold text-primary mb-2">{{ $p->kursus->nama }}</h5>
                            <small class="text-muted d-block mb-2">
                                <i class="bi bi-collection me-1"></i>{{ $p->kursus->program->nama }}
                            </small>
                            <small class="text-muted d-block mb-2">
                                <i class="bi bi-bookmark me-1"></i>{{ $p->kursus->level->nama }}
                            </small>
                            <small class="text-muted d-block mb-2">
                                <i class="bi bi-person-circle me-1"></i>{{ $p->kursus->instruktur->nama_instr ?? 'N/A' }}
                            </small>
                        </div>

                        <div class="bg-light p-3 rounded mb-3">
                            <small class="text-muted d-block">Status Pembayaran</small>
                            @if($p->status_pembayaran === 'selesai')
                                <span class="badge bg-success">Selesai</span>
                            @elseif($p->status_pembayaran === 'dp')
                                <span class="badge bg-warning">DP (Cicilan)</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($p->status_pembayaran) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="/peserta/kursus/{{ $p->kursus->id }}/detail" class="btn btn-primary btn-sm">
                            <i class="bi bi-eye me-1"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            Anda belum mendaftar di kursus apapun. 
            <a href="/peserta/kursus" class="alert-link">Daftar kursus sekarang</a>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
@endsection
