@extends('peserta::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="bi bi-file-earmark me-2"></i>{{ $kursus->nama }}
            </h2>
            <small class="text-muted">Daftar Risalah Pertemuan</small>
        </div>
        <a href="/peserta/kursus" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <form method="GET" action="" class="mb-3 d-flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari materi, catatan..." class="form-control" />
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>
    @if($risalahs && count($risalahs) > 0)
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="fw-bold text-muted border-0">Pertemuan</th>
                            <th class="fw-bold text-muted border-0">Tanggal</th>
                            <th class="fw-bold text-muted border-0">Materi</th>
                            <th class="fw-bold text-muted border-0">Catatan</th>
                            <th class="fw-bold text-muted border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($risalahs as $r)
                        <tr>
                            <td class="fw-bold border-0">Pertemuan {{ $r->pertemuan_ke }}</td>
                            <td class="border-0">
                                {{ $r->tgl_pertemuan ? \Carbon\Carbon::parse($r->tgl_pertemuan)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="border-0">
                                <span class="badge bg-info">
                                    {{ $r->materi ? 'Ada' : 'Belum ada' }}
                                </span>
                            </td>
                            <td class="border-0">
                                {{ $r->catatan ? Str::limit($r->catatan, 40) : '-' }}
                            </td>
                            <td class="border-0">
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#risalahModal{{ $r->id }}">
                                    <i class="bi bi-eye me-1"></i>Lihat Risalah
                                </button>
                                @if($r->dokumen)
                                    <a href="{{ route('instruktur.risalah.download', $r->id) }}" class="btn btn-sm btn-success ms-1" target="_blank">
                                        <i class="bi bi-download me-1"></i>Download Dokumen
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- All Modals -->
        @foreach($risalahs as $r)
        <!-- Modal -->
        <div class="modal fade" id="risalahModal{{ $r->id }}" tabindex="-1" aria-labelledby="risalahModalLabel{{ $r->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="risalahModalLabel{{ $r->id }}">
                            <i class="bi bi-file-earmark me-2"></i>Risalah Pertemuan {{ $r->pertemuan_ke }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kursus</label>
                            <p class="form-control-plaintext">{{ $kursus->nama }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pertemuan Ke-</label>
                            <p class="form-control-plaintext">{{ $r->pertemuan_ke }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Pertemuan</label>
                            <p class="form-control-plaintext">
                                {{ $r->tgl_pertemuan ? \Carbon\Carbon::parse($r->tgl_pertemuan)->translatedFormat('d F Y') : '-' }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Materi</label>
                            @if($r->materi)
                                <div class="alert alert-light border">
                                    {{ $r->materi }}
                                </div>
                            @else
                                <p class="form-control-plaintext text-muted">Belum ada materi</p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan</label>
                            @if($r->catatan)
                                <div class="alert alert-light border">
                                    {{ $r->catatan }}
                                </div>
                            @else
                                <p class="form-control-plaintext text-muted">Tidak ada catatan</p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jumlah Peserta Hadir</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info">{{ $r->absensis()->count() ?? 0 }} peserta</span>
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle me-2"></i>Belum ada risalah untuk kursus ini. Instruktur akan menambahkan risalah setelah setiap pertemuan.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
@endsection
