@extends('layouts.app-bootstrap')

@section('title', 'Daftar Kursus')

@section('content')
<div class="container-fluid py-4">
    <h2 class="fw-bold text-dark mb-4"><i class="bi bi-book me-2"></i>Daftar Kursus</h2>

    <div class="row g-4">
        @foreach($kursus as $k)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="transition: all 0.3s ease;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-dark mb-2">
                            <i class="bi bi-bookmark me-2 text-primary"></i>{{ $k->nama }}
                        </h5>
                        
                        <div class="mb-3">
                            <small class="text-muted d-block"><strong>Program:</strong> {{ $k->program->nama }}</small>
                            <small class="text-muted d-block"><strong>Level:</strong> {{ $k->level->nama }}</small>
                            <small class="text-muted d-block"><strong>Instruktur:</strong> {{ $k->instruktur->nama_instr }}</small>
                        </div>

                        <div class="alert alert-warning mb-3" role="alert">
                            <strong>Rp {{ number_format($k->harga) }}</strong>
                        </div>

                        <div class="mt-auto d-grid gap-2">
                            <a href="{{ route('peserta.kursus.show', $k->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-eye me-2"></i>Detail
                            </a>

                            @php
                                $peserta = auth()->user()->peserta ?? null;
                                $sudahDaftar = false;
                                if ($peserta) {
                                    $sudahDaftar = $peserta->pendaftarans()->where('kursus_id', $k->id)->exists();
                                }
                            @endphp

                            @if($sudahDaftar)
                                <button class="btn btn-success w-100" disabled>
                                    <i class="bi bi-check-circle me-2"></i>Sudah Daftar
                                </button>
                            @else
                                <form method="POST" action="{{ route('peserta.kursus.daftar', $k->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-check-circle me-2"></i>Daftar Kursus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-4px);
    }
</style>
@endsection
