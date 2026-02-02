@extends('instruktur::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-person-badge me-2"></i>Manajemen Instruktur</h2>
        <a href="{{ route('admin.instruktur.create') }}" class="btn btn-primary btn-lg"><i class="bi bi-plus-circle me-2"></i>Tambah Instruktur</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="fw-bold text-muted border-0">No</th>
                        <th class="fw-bold text-muted border-0">Nama</th>
                        <th class="fw-bold text-muted border-0">Email</th>
                        <th class="fw-bold text-muted border-0">Spesialisasi</th>
                        <th class="fw-bold text-muted border-0">Aksi</th>
                    </tr>
                </thead>
                <tbody>

    @foreach($instrukturs as $i)
                    <tr style="transition: background-color 0.2s ease;">
                        <td class="border-0 text-muted">{{ $loop->iteration }}</td>
                        <td class="border-0 fw-500">{{ $i->nama_instr }}</td>
                        <td class="border-0 text-muted">{{ $i->user->email }}</td>
                        <td class="border-0">{{ $i->spesialisasi }}</td>
                        <td class="border-0">
                            <a href="{{ route('admin.instruktur.edit', $i->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>

                            <form action="{{ route('admin.instruktur.destroy', $i->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')"><i class="bi bi-trash"></i> Hapus</button>
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
