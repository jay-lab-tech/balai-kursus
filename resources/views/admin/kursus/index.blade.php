<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-book me-2"></i>Manajemen Kursus</h2>
        <a href="{{ route('admin.kursus.create') }}" class="btn btn-primary btn-lg"><i class="bi bi-plus-circle me-2"></i>Tambah Kursus</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="fw-bold text-muted border-0">No</th>
                            <th class="fw-bold text-muted border-0">Nama</th>
                            <th class="fw-bold text-muted border-0">Program</th>
                            <th class="fw-bold text-muted border-0">Level</th>
                            <th class="fw-bold text-muted border-0">Instruktur</th>
                            <th class="fw-bold text-muted border-0">Harga</th>
                            <th class="fw-bold text-muted border-0">Kuota</th>
                            <th class="fw-bold text-muted border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

    @foreach($kursus as $k)
                        <tr style="transition: background-color 0.2s ease;">
                            <td class="border-0 text-muted">{{ $loop->iteration }}</td>
                            <td class="border-0 fw-500">{{ $k->nama }}</td>
                            <td class="border-0">{{ $k->program->nama }}</td>
                            <td class="border-0">{{ $k->level->nama }}</td>
                            <td class="border-0">{{ $k->instruktur->nama_instr }}</td>
                            <td class="border-0 fw-500">Rp {{ number_format($k->harga) }}</td>
                            <td class="border-0"><span class="badge bg-info">{{ $k->kuota }}</span></td>
                            <td class="border-0">
                                <a href="{{ route('admin.kursus.edit', $k->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>

                                <form action="{{ route('admin.kursus.destroy', $k->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')"><i class="bi bi-trash"></i> Hapus</button>
                                </form>

                                <a href="{{ route('admin.kursus.peserta', $k->id) }}" class="btn btn-sm btn-info"><i class="bi bi-people"></i> Peserta</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
