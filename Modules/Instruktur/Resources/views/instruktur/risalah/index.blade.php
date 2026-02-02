@extends('layouts.app-bootstrap')

@section('title', 'Risalah - ' . ($kursus->nama_kursus ?? $kursus->nama ?? 'Instruktur'))

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0"><i class="bi bi-file-earmark"></i> {{ $kursus->nama_kursus ?? $kursus->nama }}</h5>
            <small class="text-muted">Daftar Pertemuan & Risalah</small>
        </div>
        <a href="{{ url('/instruktur/kursus/'.$kursus->id.'/risalah/create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Pertemuan
        </a>
    </div>
    <div class="card-body">
        @if(count($risalah) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pertemuan</th>
                            <th>Tanggal</th>
                            <th>Topik</th>
                            <th>Peserta Hadir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($risalah as $r)
                        <tr>
                            <td><strong>Pertemuan {{ $r->pertemuan_ke }}</strong></td>
                            <td>{{ $r->tgl_pertemuan ? date('d/m/Y', strtotime($r->tgl_pertemuan)) : '-' }}</td>
                            <td>{{ Str::limit($r->topik ?? '-', 30) }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $r->absensi_count ?? 0 }} / {{ $r->peserta_count ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ url('/instruktur/risalah/'.$r->id.'/absensi') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-clipboard-check"></i> Absensi
                                </a>
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $r->id }}">
                                    <i class="bi bi-eye"></i> Detail
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                <h5 class="mt-3">Belum ada pertemuan</h5>
                <p class="text-muted">Mulai buat pertemuan dan risalah untuk kursus ini</p>
                <a href="{{ url('/instruktur/kursus/'.$kursus->id.'/risalah/create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Buat Pertemuan Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
