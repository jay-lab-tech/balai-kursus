@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-calendar me-2"></i>Master Hari</h2>
        <a href="{{ route('admin.hari.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Tambah Hari
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($haris->isEmpty())
                <div class="alert alert-info">Belum ada hari</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Urutan</th>
                                <th>Nama</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($haris as $key => $hari)
                                <tr>
                                    <td><span class="badge bg-secondary">{{ $hari->urutan }}</span></td>
                                    <td><strong>{{ $hari->nama }}</strong></td>
                                    <td>
                                        <a href="{{ route('admin.hari.edit', $hari->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.hari.destroy', $hari->id) }}" method="POST" style="display:inline;">
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
                    {{ $haris->links('pagination::bootstrap-5') }}
                </nav>
            @endif
        </div>
    </div>
</div>
@endsection
