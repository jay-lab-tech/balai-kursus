@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-door-closed me-2"></i>Master Kelas</h2>
        <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Tambah Kelas
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($kelas->isEmpty())
                <div class="alert alert-info">Belum ada kelas</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Kapasitas</th>
                                <th>Fasilitas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kelas as $key => $k)
                                <tr>
                                    <td>{{ $kelas->firstItem() + $key }}</td>
                                    <td><strong>{{ $k->nama }}</strong></td>
                                    <td><span class="badge bg-info">{{ $k->kapasitas }} orang</span></td>
                                    <td>{{ $k->fasilitas }}</td>
                                    <td>
                                        <a href="{{ route('admin.kelas.show', $k->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.kelas.edit', $k->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" style="display:inline;">
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
                    {{ $kelas->links('pagination::bootstrap-5') }}
                </nav>
            @endif
        </div>
    </div>
</div>
@endsection
