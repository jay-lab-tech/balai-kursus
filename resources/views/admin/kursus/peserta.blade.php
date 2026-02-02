<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-people me-2"></i>Peserta Kursus: {{ $kursus->nama }}</h2>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="fw-bold text-muted border-0">No</th>
                        <th class="fw-bold text-muted border-0">Nama</th>
                        <th class="fw-bold text-muted border-0">Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peserta as $p)
                    <tr style="transition: background-color 0.2s ease;">
                        <td class="border-0 text-muted">{{ $loop->iteration }}</td>
                        <td class="border-0 fw-500">{{ $p->peserta->user->name }}</td>
                        <td class="border-0 text-muted">{{ $p->peserta->user->email }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.kursus.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
    </div>
</div>

<style>
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
