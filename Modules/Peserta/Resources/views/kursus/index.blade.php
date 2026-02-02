@extends('peserta::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-book me-2"></i>Daftar Kursus</h2>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        @forelse($kursus as $k)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary">{{ $k->nama }}</h5>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">
                            <i class="bi bi-collection me-1"></i>Program: <strong>{{ $k->program->nama }}</strong>
                        </small>
                        <small class="text-muted d-block">
                            <i class="bi bi-bookmark me-1"></i>Level: <strong>{{ $k->level->nama }}</strong>
                        </small>
                        <small class="text-muted d-block">
                            <i class="bi bi-person-circle me-1"></i>Instruktur: <strong>{{ $k->instruktur->nama_instr }}</strong>
                        </small>
                    </div>

                    <div class="bg-light p-3 rounded mb-3">
                        <div class="text-center">
                            <small class="text-muted d-block">Harga Kursus</small>
                            <h6 class="fw-bold text-dark mb-0">Rp {{ number_format($k->harga, 0, ',', '.') }}</h6>
                        </div>
                    </div>

                    <form action="{{ route('peserta.kursus.daftar', $k->id) }}" method="POST" class="d-grid">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Daftar Kursus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle me-2"></i>Belum ada kursus yang tersedia saat ini.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
