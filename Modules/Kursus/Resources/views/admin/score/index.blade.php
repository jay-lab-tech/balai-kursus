@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-list me-2"></i>Daftar Nilai Peserta</h2>
        <a href="{{ route('admin.score.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Tambah Nilai
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($scores->isEmpty())
                <div class="alert alert-info">Belum ada data nilai</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Peserta</th>
                                <th>Kursus</th>
                                <th>Listening</th>
                                <th>Speaking</th>
                                <th>Reading</th>
                                <th>Writing</th>
                                <th>Assignment</th>
                                <th>Nilai Akhir</th>
                                <th>Status</th>
                                <th>Evaluator</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($scores as $key => $score)
                                <tr>
                                    <td>{{ $scores->firstItem() + $key }}</td>
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
                                        <a href="{{ route('admin.score.edit', $score->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.score.destroy', $score->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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
    </div>
</div>
@endsection
