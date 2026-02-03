@extends('program::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-folder me-2"></i>Manajemen Program</h2>
        <a href="{{ route('admin.program.create') }}" class="btn btn-primary btn-lg"><i class="bi bi-plus-circle me-2"></i>Tambah Program</a>
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
                        <th class="fw-bold text-muted border-0">Nama Program</th>
                        <th class="fw-bold text-muted border-0">Jumlah Level</th>
                        <th class="fw-bold text-muted border-0 text-nowrap" style="white-space:nowrap;">Jumlah Kursus</th>
                        <th class="fw-bold text-muted border-0 text-nowrap" style="width:1%; white-space:nowrap;">Aksi</th>
                    </tr>
                </thead>
                <tbody>

    @foreach($program as $p)
                    <tr style="transition: background-color 0.2s ease;">
                        <td class="border-0 text-muted">{{ $loop->iteration }}</td>
                        <td class="border-0 fw-500">{{ $p->nama }}</td>
                        <td class="border-0">
                            <span class="badge bg-info me-1">{{ $p->levels->count() }}</span>
                        </td>
                        <td class="border-0 text-nowrap" style="white-space:nowrap;">
                            <span class="badge bg-success me-2">{{ $p->kursuses->count() }}</span>
                        </td>
                        <td class="border-0 text-end">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.program.edit', $p->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>

                                <form action="{{ route('admin.program.destroy', $p->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus program?')"><i class="bi bi-trash"></i> Hapus</button>
                                </form>
                            </div>
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
