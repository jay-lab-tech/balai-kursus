<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-pencil me-2"></i>Edit Peserta</h2>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="/admin/peserta/{{ $peserta->id }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama" class="form-label fw-500">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ $peserta->user->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-500">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $peserta->user->email }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="nomor_peserta" class="form-label fw-500">Nomor Peserta</label>
                            <input type="text" class="form-control" id="nomor_peserta" name="nomor_peserta" value="{{ $peserta->nomor_peserta }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="no_hp" class="form-label fw-500">No HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ $peserta->no_hp }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="instansi" class="form-label fw-500">Instansi</label>
                            <input type="text" class="form-control" id="instansi" name="instansi" value="{{ $peserta->instansi }}">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle me-2"></i>Update</button>
                            <a href="/admin/peserta" class="btn btn-secondary btn-lg"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
