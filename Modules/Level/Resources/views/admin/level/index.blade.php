@extends('level::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-stack me-2"></i>Manajemen Level</h2>
        <a href="{{ route('admin.level.create') }}" class="btn btn-primary btn-lg"><i class="bi bi-plus-circle me-2"></i>Tambah Level</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="fw-bold text-muted border-0">No</th>
                        <th class="fw-bold text-muted border-0">Program</th>
                        <th class="fw-bold text-muted border-0">Nama Level</th>
                        <th class="fw-bold text-muted border-0">Aksi</th>
                    </tr>
                </thead>
                <tbody>

@foreach($level as $l)
                    <tr style="transition: background-color 0.2s ease;">
                        <td class="border-0 text-muted">{{ $loop->iteration }}</td>
                        <td class="border-0 fw-500">{{ $l->program->nama }}</td>
                        <td class="border-0">{{ $l->nama }}</td>
                        <td class="border-0">
                            <a href="{{ route('admin.level.edit', $l->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>

                            <form action="{{ route('admin.level.destroy', $l->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus level?')"><i class="bi bi-trash"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
