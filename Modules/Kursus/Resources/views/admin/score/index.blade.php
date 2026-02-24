@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-list me-2"></i>Daftar Nilai Peserta</h2>
        <a href="{{ route('admin.score.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Tambah Nilai
        </a>
    </div>
    @if($scores->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Peserta</th>
                        <th>Kursus</th>
                        <th>Listening</th>
                        <th>Speaking</th>
                        <th>Reading</th>
                        <th>Writing</th>
                        <th>Assignment</th>
                        <th>Final Score</th>
                        <th>Status</th>
                        <th>Evaluator</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="adminScoreTableBody">
                    @foreach($scores as $score)
                        <tr>
                            <td>{{ $score->pendaftaran->peserta->user->name }}</td>
                            <td>{{ $score->pendaftaran->kursus->nama }}</td>
                            <td><span class="badge bg-info">{{ $score->listening }}</span></td>
                            <td><span class="badge bg-info">{{ $score->speaking }}</span></td>
                            <td><span class="badge bg-info">{{ $score->reading }}</span></td>
                            <td><span class="badge bg-info">{{ $score->writing }}</span></td>
                            <td><span class="badge bg-info">{{ $score->assignment }}</span></td>
                            <td>
                                <span class="badge {{ $score->final_score >= 70 ? 'bg-success' : 'bg-warning' }}">
                                    {{ $score->final_score }}
                                </span>
                            </td>
                            <td>
                                @if($score->status == 'pass')
                                    <span class="badge bg-success">Lulus</span>
                                @elseif($score->status == 'fail')
                                    <span class="badge bg-danger">Gagal</span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                            <td>{{ $score->evaluator->nama_instr ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.score.show', $score->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <nav>
            {{ $scores->links('pagination::bootstrap-5') }}
        </nav>
    @endif
</div>
@endsection
