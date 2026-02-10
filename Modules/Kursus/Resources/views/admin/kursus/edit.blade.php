@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-pencil me-2"></i>Edit Kursus</h2>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.kursus.update', $kursus->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama" class="form-label fw-500">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ $kursus->nama }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="program_id" class="form-label fw-500">Program</label>
                            <select class="form-select" id="program_id" name="program_id" required>
                                @foreach($program as $p)
                                    <option value="{{ $p->id }}"
                                        {{ $kursus->program_id == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="level_id" class="form-label fw-500">Level</label>
                            <select class="form-select" id="level_id" name="level_id" required>
                                <option value="">-- Pilih Level --</option>
                                @foreach($level as $l)
                                    <option value="{{ $l->id }}"
                                        {{ $kursus->level_id == $l->id ? 'selected' : '' }}>
                                        {{ $l->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="instruktur_id" class="form-label fw-500">Instruktur</label>
                            <select class="form-select" id="instruktur_id" name="instruktur_id" required>
                                @foreach($instruktur as $i)
                                    <option value="{{ $i->id }}"
                                        {{ $kursus->instruktur_id == $i->id ? 'selected' : '' }}>
                                        {{ $i->nama_instr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="instruktur_id_2" class="form-label fw-500">Instruktur Kedua (Opsional)</label>
                            <select class="form-select" id="instruktur_id_2" name="instruktur_id_2">
                                <option value="">-- Pilih Instruktur --</option>
                                @foreach($instruktur as $i)
                                    <option value="{{ $i->id }}"
                                        {{ $kursus->instruktur_id_2 == $i->id ? 'selected' : '' }}>
                                        {{ $i->nama_instr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="periode" class="form-label fw-500">Periode</label>
                            <input type="text" class="form-control" id="periode" name="periode" value="{{ $kursus->periode }}" placeholder="Contoh: Februari 2026">
                        </div>

                        <div class="mb-3">
                            <label for="harga" class="form-label fw-500">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga" value="{{ $kursus->harga }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="harga_upi" class="form-label fw-500">Harga UPI (Opsional)</label>
                            <input type="number" class="form-control" id="harga_upi" name="harga_upi" value="{{ $kursus->harga_upi }}">
                        </div>

                        <div class="mb-3">
                            <label for="kuota" class="form-label fw-500">Kuota</label>
                            <input type="number" class="form-control" id="kuota" name="kuota" value="{{ $kursus->kuota }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label fw-500">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="buka" {{ $kursus->status == 'buka' ? 'selected' : '' }}>Buka</option>
                                <option value="tutup" {{ $kursus->status == 'tutup' ? 'selected' : '' }}>Tutup</option>
                                <option value="berjalan" {{ $kursus->status == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle me-2"></i>Update</button>
                            <a href="{{ route('admin.kursus.index') }}" class="btn btn-secondary btn-lg"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const programSelect = document.getElementById('program_id');
    const levelSelect = document.getElementById('level_id');
    const currentLevelId = {{ $kursus->level_id }};

    // Function to load levels
    function loadLevels(programId, selectLevel = null) {
        levelSelect.innerHTML = '<option value="">-- Pilih Level --</option>';
        
        if (programId) {
            fetch(`/admin/program/${programId}/levels`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(level => {
                        const option = document.createElement('option');
                        option.value = level.id;
                        option.textContent = level.nama;
                        
                        // Select the option if it matches selectLevel or currentLevelId
                        if (selectLevel ? level.id === selectLevel : level.id === currentLevelId) {
                            option.selected = true;
                        }
                        levelSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        }
    }

    // Load levels on page load
    if (programSelect.value) {
        loadLevels(programSelect.value);
    }

    // Handle program change
    programSelect.addEventListener('change', function() {
        loadLevels(this.value);
    });
});
</script>
@endsection
