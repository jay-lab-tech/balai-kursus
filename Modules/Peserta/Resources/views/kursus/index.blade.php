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

    <div class="mb-3">
        <input type="text" id="filterInputPesertaKursus" placeholder="Cari nama kursus, program, level, instruktur..." class="form-control" />
    </div>
    <div class="row">
        @forelse($kursus as $k)
        <div class="col-md-6 col-lg-4 mb-4 peserta-kursus-item">
            @php
                $levelColors = [
                    'Beginner' => '#e3f2fd',
                    'Intermediate' => '#fff3e0',
                    'Advanced' => '#f3e5f5',
                    'Expert' => '#e8f5e9',
                ];
                $bgColor = $levelColors[$k->level->nama] ?? '#f8f9fa';
            @endphp
            <div class="card border-0 shadow-sm h-100" style="background-color: {{ $bgColor }};">
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

                    <div class="d-grid gap-2">
                        <a href="/peserta/kursus/{{ $k->id }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye me-2"></i>Lihat Detail
                        </a>
                        <form action="{{ route('peserta.kursus.daftar', $k->id) }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Daftar Kursus
                            </button>
                        </form>
                    </div>
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterInput = document.getElementById('filterInputPesertaKursus');
        filterInput.addEventListener('input', function() {
            const filter = filterInput.value.toLowerCase();
            const items = document.querySelectorAll('.peserta-kursus-item');
            items.forEach(item => {
                const text = item.innerText.toLowerCase();
                item.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    });
    </script>
</div>
@endsection
