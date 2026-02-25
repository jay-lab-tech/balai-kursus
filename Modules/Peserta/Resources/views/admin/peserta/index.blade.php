@extends('peserta::layouts.master')

@section('title', 'Management Peserta')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-people me-2"></i>Management Peserta</h2>
        <a href="/admin/peserta/create" class="btn btn-primary btn-lg"><i class="bi bi-plus-circle me-2"></i>Tambah Peserta</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form method="GET" action="" class="mb-3 d-flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, nomor peserta, instansi..." class="form-control" />
        <select name="filter" class="form-select">
            <option value="">Semua</option>
            <option value="aktif" {{ request('filter')=='aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ request('filter')=='nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <button type="submit" class="btn btn-primary">Cari/Filter</button>
    </form>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="fw-bold text-muted border-0">No</th>
                        <th class="fw-bold text-muted border-0">Nama</th>
                        <th class="fw-bold text-muted border-0">Email</th>
                        <th class="fw-bold text-muted border-0">No Peserta</th>
                        <th class="fw-bold text-muted border-0">No HP</th>
                        <th class="fw-bold text-muted border-0">Instansi</th>
                        <th class="fw-bold text-muted border-0">Aksi</th>
                    </tr>
                </thead>
                <tbody id="adminPesertaTableBody">

                @foreach($pesertas as $p)
                <tr style="transition: background-color 0.2s ease;">
                    <td class="border-0 text-muted">{{ $loop->iteration }}</td>
                    <td class="border-0 fw-500">{{ $p->user->name }}</td>
                    <td class="border-0 text-muted">{{ $p->user->email }}</td>
                    <td class="border-0">{{ $p->nomor_peserta }}</td>
                    <td class="border-0">{{ $p->no_hp }}</td>
                    <td class="border-0">{{ $p->instansi }}</td>
                    <td class="border-0">
                        <a href="/admin/peserta/{{ $p->id }}/edit" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                        <form action="/admin/peserta/{{ $p->id }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterInput = document.getElementById('filterInputAdminPeserta');
            filterInput.addEventListener('input', function() {
                const filter = filterInput.value.toLowerCase();
                const rows = document.querySelectorAll('#adminPesertaTableBody tr');
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        });
        </script>
    </div>
</div>

@endsection

@push('styles')
<style>
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush
