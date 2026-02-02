@extends('level::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-pencil me-2"></i>Edit Level</h2>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.level.update', $level->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="program_id" class="form-label fw-500">Program</label>
                            <select class="form-select" id="program_id" name="program_id" required>
                                @foreach($program as $p)
                                    <option value="{{ $p->id }}"
                                        {{ $level->program_id == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label fw-500">Nama Level</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ $level->nama }}" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle me-2"></i>Update</button>
                            <a href="{{ route('admin.level.index') }}" class="btn btn-secondary btn-lg"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
