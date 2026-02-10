@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-geo-alt me-2"></i>Master Lokasi</h2>
        <a href="{{ route('admin.lokasi.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Tambah Lokasi
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($lokasis->isEmpty())
                <div class="alert alert-info">Belum ada lokasi</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Kota</th>
                                <th>No Telp</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lokasis as $key => $lokasi)
                                <tr>
                                    <td>{{ $lokasis->firstItem() + $key }}</td>
                                    <td><strong>{{ $lokasi->nama }}</strong></td>
                                    <td>{{ $lokasi->alamat }}</td>
                                    <td>{{ $lokasi->kota }}</td>
                                    <td>{{ $lokasi->no_telp }}</td>
                                    <td>
                                        <a href="{{ route('admin.lokasi.show', $lokasi->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.lokasi.edit', $lokasi->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.lokasi.destroy', $lokasi->id) }}" method="POST" style="display:inline;">
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
                    {{ $lokasis->links('pagination::bootstrap-5') }}
                </nav>
            @endif
        </div>
    </div>
</div>
@endsection
