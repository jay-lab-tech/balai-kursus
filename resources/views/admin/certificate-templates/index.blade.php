@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h1>Template Sertifikat</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.templates.create') }}" class="btn btn-primary">
                + Buat Template
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Kursus</th>
                    <th>Default</th>
                    <th>Digunakan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($templates as $template)
                    <tr>
                        <td><strong>{{ $template->name }}</strong></td>
                        <td>{{ $template->kursus->judul ?? '(Global Default)' }}</td>
                        <td>
                            @if ($template->is_default)
                                <span class="badge bg-success">Ya</span>
                            @endif
                        </td>
                        <td>{{ $template->certificates()->count() }}</td>
                        <td>
                            <a href="{{ route('admin.templates.edit', $template) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form method="post" action="{{ route('admin.templates.destroy', $template) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Hapus template ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada template.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $templates->links() }}
    </div>
</div>
@endsection
