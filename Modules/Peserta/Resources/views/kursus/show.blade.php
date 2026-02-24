@extends('peserta::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="bi bi-book me-2"></i>{{ $kursus->nama }}
            </h2>
            <small class="text-muted">Detail Kursus</small>
        </div>
        <a href="/peserta/kursus" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali</a>
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Informasi Kursus</h5>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <small class="text-muted d-block">Program</small>
                            <strong>{{ $kursus->program->nama }}</strong>
                        </div>
                        <div class="col-sm-4">
                            <small class="text-muted d-block">Level</small>
                            <strong>{{ $kursus->level->nama }}</strong>
                        </div>
                        <div class="col-sm-4">
                            <small class="text-muted d-block">Periode</small>
                            <strong>{{ $kursus->periode }}</strong>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <small class="text-muted d-block">Instruktur</small>
                            <strong>{{ $kursus->instruktur->nama_instr }}</strong>
                        </div>
                        <div class="col-sm-4">
                            <small class="text-muted d-block">Kuota</small>
                            <strong>{{ $kursus->pendaftarans()->count() }}/{{ $kursus->kuota }} peserta</strong>
                        </div>
                        <div class="col-sm-4">
                            <small class="text-muted d-block">Harga</small>
                            <strong>Rp {{ number_format($kursus->harga, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-calendar me-2"></i>Jadwal Pertemuan
                    </h5>

                    @if($kursus->jadwals && count($kursus->jadwals) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless mb-0">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th class="fw-bold text-muted">Hari</th>
                                        <th class="fw-bold text-muted">Jam</th>
                                        <th class="fw-bold text-muted">Lokasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kursus->jadwals as $jadwal)
                                    <tr>
                                        <td>{{ $jadwal->hari->nama ?? '-' }}</td>
                                        <td>{{ $jadwal->jam_mulai ?? '-' }} - {{ $jadwal->jam_selesai ?? '-' }}</td>
                                        <td>{{ $jadwal->lokasi->nama ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Belum ada jadwal tersedia</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-window-stack me-2"></i>Menu
                    </h5>

                    @php
                        $peserta = Auth::user()->peserta;
                        $sudahDaftar = $peserta && \App\Models\Pendaftaran::where('peserta_id', $peserta->id)
                            ->where('kursus_id', $kursus->id)->exists();
                    @endphp

                    @if($sudahDaftar)
                        <a href="/peserta/kursus/{{ $kursus->id }}/risalah" class="btn btn-primary btn-sm w-100 mb-2">
                            <i class="bi bi-file-earmark me-2"></i>Lihat Risalah Pertemuan
                        </a>
                        <a href="/peserta/pendaftaran" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="bi bi-list me-2"></i>Lihat Pendaftaran Ku
                        </a>
                    @else
                        <form action="{{ route('peserta.kursus.daftar', $kursus->id) }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Daftar Kursus Ini
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
