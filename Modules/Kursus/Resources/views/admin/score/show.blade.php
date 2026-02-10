@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-eye me-2"></i>Detail Nilai Peserta</h2>
        <div>
            <a href="{{ route('admin.score.edit', $score->id) }}" class="btn btn-warning btn-lg">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('admin.score.index') }}" class="btn btn-secondary btn-lg">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Informasi Peserta</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Nama Peserta:</strong> {{ $score->pendaftaran->peserta->user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Nomor Peserta:</strong> {{ $score->pendaftaran->peserta->nomor_peserta }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Kursus:</strong> {{ $score->pendaftaran->kursus->nama }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Nomor Pendaftaran:</strong> {{ $score->pendaftaran->nomor }}</p>
                        </div>
                    </div>

                    <hr>

                    <h5 class="fw-bold mb-3">Nilai</h5>
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="text-muted mb-2">Listening</p>
                                    <h3 class="fw-bold">{{ $score->listening }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="text-muted mb-2">Speaking</p>
                                    <h3 class="fw-bold">{{ $score->speaking }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="text-muted mb-2">Reading</p>
                                    <h3 class="fw-bold">{{ $score->reading }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="text-muted mb-2">Writing</p>
                                    <h3 class="fw-bold">{{ $score->writing }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="text-muted mb-2">Assignment</p>
                                    <h3 class="fw-bold">{{ $score->assignment }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card {{ $score->final_score >= 70 ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                                <div class="card-body">
                                    <p class="text-muted mb-2">Nilai Akhir</p>
                                    <h3 class="fw-bold">{{ $score->final_score }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5 class="fw-bold mb-3">Field Tambahan</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <p><strong>UKTP:</strong> {{ $score->uktp ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>UKAP:</strong> {{ $score->ukap ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Var 1:</strong> {{ $score->var1 ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Var 2:</strong> {{ $score->var2 ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <p><strong>Var 3:</strong> {{ $score->var3 ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Var 4:</strong> {{ $score->var4 ?? '-' }}</p>
                        </div>
                    </div>

                    <hr>

                    <h5 class="fw-bold mb-3">Evaluasi</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Status:</strong> 
                                @if($score->status == 'pass')
                                    <span class="badge bg-success">Lulus</span>
                                @elseif($score->status == 'fail')
                                    <span class="badge bg-danger">Gagal</span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Dievaluasi oleh:</strong> {{ $score->evaluator->nama_instr ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tanggal Evaluasi:</strong> {{ $score->evaluated_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    @if($score->keterangan)
                        <hr>
                        <h5 class="fw-bold mb-3">Keterangan</h5>
                        <p>{{ $score->keterangan }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Ringkasan</h5>
                    <p>
                        <strong>Rata-rata Skill:</strong>
                        <br>
                        <span class="badge bg-info">
                            {{ round(($score->listening + $score->speaking + $score->reading + $score->writing) / 4, 1) }}
                        </span>
                    </p>
                    <p>
                        <strong>Hasil:</strong>
                        <br>
                        @if($score->status == 'pass')
                            <span class="badge bg-success">LULUS</span>
                        @elseif($score->status == 'fail')
                            <span class="badge bg-danger">GAGAL</span>
                        @else
                            <span class="badge bg-secondary">PENDING</span>
                        @endif
                    </p>
                    <p>
                        <strong>Dibuat:</strong>
                        <br>
                        {{ $score->created_at->format('d M Y H:i') }}
                    </p>
                    <p>
                        <strong>Diperbarui:</strong>
                        <br>
                        {{ $score->updated_at->format('d M Y H:i') }}
                    </p>

                    <form action="{{ route('admin.score.destroy', $score->id) }}" method="POST" style="margin-top: 20px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Yakin ingin menghapus?')">
                            <i class="bi bi-trash me-2"></i>Hapus Nilai
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
