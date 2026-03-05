@extends('layouts.app-bootstrap')

@section('title', 'Template Sertifikat')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-file-earmark-text me-2"></i>Template Sertifikat</h2>
        <a href="{{ route('admin.templates.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Buat Template Baru
        </a>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-bold"><i class="bi bi-file-earmark me-2"></i>Nama Template</th>
                        <th class="fw-bold"><i class="bi bi-book me-2"></i>Kursus</th>
                        <th class="fw-bold text-center"><i class="bi bi-star me-2"></i>Default</th>
                        <th class="fw-bold text-center"><i class="bi bi-award me-2"></i>Digunakan</th>
                        <th class="fw-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($templates as $template)
                        <tr class="align-middle">
                            <td><strong>{{ $template->name }}</strong></td>
                            <td>{{ $template->kursus->nama ?? $template->kursus->judul ?? '(Global Default)' }}</td>
                            <td class="text-center">
                                @if ($template->is_default)
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Ya</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $template->certificates()->count() }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.templates.edit', $template) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </a>
                                <form method="post" action="{{ route('admin.templates.destroy', $template) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                        onclick="return confirm('Hapus template ini? Tindakan tidak dapat dibatalkan.')">
                                        <i class="bi bi-trash me-1"></i>Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-2"></i>Belum ada template. <a href="{{ route('admin.templates.create') }}">Buat template baru</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $templates->links() }}
    </div>
</div>
@endsection
