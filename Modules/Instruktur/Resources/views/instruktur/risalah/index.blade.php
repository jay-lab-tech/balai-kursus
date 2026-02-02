@extends('instruktur::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="bi bi-file-earmark me-2"></i>{{ $kursus->nama }}
            </h2>
            <small class="text-muted">Daftar Pertemuan & Risalah</small>
        </div>
        <a href="/instruktur/kursus/{{ $kursus->id }}/risalah/create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah Pertemuan
        </a>
    </div>

    @if($risalahs && count($risalahs) > 0)
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="fw-bold text-muted border-0">Pertemuan</th>
                            <th class="fw-bold text-muted border-0">Tanggal</th>
                            <th class="fw-bold text-muted border-0">Materi</th>
                            <th class="fw-bold text-muted border-0">Peserta Hadir</th>
                            <th class="fw-bold text-muted border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($risalahs as $r)
                        <tr>
                            <td class="fw-bold border-0">Pertemuan {{ $r->pertemuan_ke }}</td>
                            <td class="border-0">{{ $r->tgl_pertemuan ? \Carbon\Carbon::parse($r->tgl_pertemuan)->format('d/m/Y') : '-' }}</td>
                            <td class="border-0">{{ Str::limit($r->materi ?? '-', 40) }}</td>
                            <td class="border-0">
                                <span class="badge bg-info">
                                    {{ $r->absensis()->count() ?? 0 }}
                                </span>
                            </td>
                            <td class="border-0">
                                <a href="/instruktur/risalah/{{ $r->id }}/absensi" class="btn btn-sm btn-primary">
                                    <i class="bi bi-clipboard-check me-1"></i>Absensi
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Belum ada risalah.</strong> Klik tombol "Tambah Pertemuan" untuk membuat risalah baru.
        </div>
    @endif
</div>
@endsection
