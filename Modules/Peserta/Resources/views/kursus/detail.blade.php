@extends('peserta::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="bi bi-book me-2"></i>{{ $kursus->nama }}
            </h2>
            <small class="text-muted">Pertemuan dan Risalah</small>
        </div>
        <a href="/peserta/kursus/saya" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Info Kursus</h5>
                    <div class="row">
                        <div class="col-sm-6 mb-2">
                            <small class="text-muted d-block">Program</small>
                            <strong>{{ $kursus->program->nama }}</strong>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <small class="text-muted d-block">Level</small>
                            <strong>{{ $kursus->level->nama }}</strong>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <small class="text-muted d-block">Instruktur</small>
                            <strong>{{ $kursus->instruktur->nama_instr ?? 'N/A' }}</strong>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <small class="text-muted d-block">Periode</small>
                            <strong>{{ $kursus->periode }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-calendar me-2"></i>Daftar Pertemuan
                    </h5>

                    @if($risalahs && count($risalahs) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th class="fw-bold text-muted border-0">Pertemuan</th>
                                        <th class="fw-bold text-muted border-0">Tanggal</th>
                                        <th class="fw-bold text-muted border-0">Status</th>
                                        <th class="fw-bold text-muted border-0">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($risalahs as $risalah)
                                    <tr>
                                        <td class="fw-bold border-0">Pertemuan {{ $risalah->pertemuan_ke }}</td>
                                        <td class="border-0">
                                            @if($risalah->tgl_pertemuan)
                                                {{ \Carbon\Carbon::parse($risalah->tgl_pertemuan)->translatedFormat('d F Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="border-0">
                                            @if($risalah->materi)
                                                <span class="badge bg-success">Ada</span>
                                            @else
                                                <span class="badge bg-warning">Belum</span>
                                            @endif
                                        </td>
                                        <td class="border-0">
                                            <a href="#" class="btn btn-sm btn-primary" onclick="showRisalah({{ $risalah->id }})">
                                                <i class="bi bi-eye me-1"></i>Lihat
                                            </a>
                                            @if($risalah->dokumen)
                                                <a href="{{ route('instruktur.risalah.download', $risalah->id) }}" class="btn btn-sm btn-success ms-1" target="_blank">
                                                    <i class="bi bi-download me-1"></i>Download Dokumen
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            Belum ada pertemuan untuk kursus ini.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-info-circle me-2"></i>Status Pendaftaran
                    </h5>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">Status Pembayaran</small>
                        @if($pendaftaran->status_pembayaran === 'selesai')
                            <span class="badge bg-success p-2">Pembayaran Selesai</span>
                        @elseif($pendaftaran->status_pembayaran === 'dp')
                            <span class="badge bg-warning p-2">DP (Cicilan)</span>
                        @else
                            <span class="badge bg-secondary p-2">{{ ucfirst($pendaftaran->status_pembayaran) }}</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">Total Bayar</small>
                        <strong class="text-dark">Rp {{ number_format($pendaftaran->total_bayar, 0, ',', '.') }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">Terbayar</small>
                        <strong class="text-dark">Rp {{ number_format($pendaftaran->terbayar, 0, ',', '.') }}</strong>
                    </div>

                    <div class="progress mb-3">
                        @php
                            $progress = $pendaftaran->total_bayar > 0 ? ($pendaftaran->terbayar / $pendaftaran->total_bayar) * 100 : 0;
                        @endphp
                        <div class="progress-bar" style="width: {{ $progress }}%"></div>
                    </div>

                    <small class="text-muted">{{ number_format($progress, 0) }}% terbayar</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODALS CONTAINER -->
@if($risalahs && count($risalahs) > 0)
@foreach($risalahs as $risalah)
<div class="modal fade" id="risalahModal{{ $risalah->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Risalah Pertemuan {{ $risalah->pertemuan_ke }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal</label>
                    <p class="form-control-plaintext">
                        {{ $risalah->tgl_pertemuan ? \Carbon\Carbon::parse($risalah->tgl_pertemuan)->translatedFormat('d F Y') : '-' }}
                    </p>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label fw-bold">Materi Pembelajaran</label>
                    @if($risalah->materi)
                        <div class="alert alert-light border">{{ $risalah->materi }}</div>
                    @else
                        <p class="text-muted">Materi belum ditambahkan</p>
                    @endif
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Catatan</label>
                    @if($risalah->catatan)
                        <div class="alert alert-light border">{{ $risalah->catatan }}</div>
                    @else
                        <p class="text-muted">Tidak ada catatan</p>
                    @endif
                </div>
                <hr>
                <div class="mb-0">
                    <label class="form-label fw-bold">Peserta Hadir</label>
                    <p class="form-control-plaintext"><span class="badge bg-info">{{ $risalah->absensis()->count() ?? 0 }} peserta</span></p>
                </div>
            </div>
            <div class="modal-footer">
                @if($risalah->dokumen)
                    <a href="{{ route('instruktur.risalah.download', $risalah->id) }}" class="btn btn-success" target="_blank">
                        <i class="bi bi-download me-1"></i>Download Dokumen
                    </a>
                @endif
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif

<script>
function showRisalah(id) {
    const modal = new bootstrap.Modal(document.getElementById('risalahModal' + id));
    modal.show();
}
</script>
@endsection
