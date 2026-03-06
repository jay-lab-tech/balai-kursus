@extends('layouts.admin')

@section('title', 'Edit Kursus')

@section('page-title', 'Edit Kursus')

@section('content')
<div class="space-y-6">
    <div class="max-w-4xl">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6"><i class="bi bi-pencil me-2"></i>Edit Kursus</h2>
                <form method="POST" action="{{ route('admin.kursus.update', $kursus->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="nama" name="nama" value="{{ $kursus->nama }}" required>
                        </div>

                        <div>
                            <label for="program_id" class="block text-sm font-medium text-gray-700">Program</label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="program_id" name="program_id" required>
                                @foreach($program as $p)
                                    <option value="{{ $p->id }}"
                                        {{ $kursus->program_id == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="level_id" class="block text-sm font-medium text-gray-700">Level</label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="level_id" name="level_id" required>
                                <option value="">-- Pilih Level --</option>
                                @foreach($level as $l)
                                    <option value="{{ $l->id }}" {{ $kursus->level_id == $l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="instruktur_id" class="block text-sm font-medium text-gray-700">Instruktur</label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="instruktur_id" name="instruktur_id" required>
                                @foreach($instruktur as $i)
                                    <option value="{{ $i->id }}"
                                        {{ $kursus->instruktur_id == $i->id ? 'selected' : '' }}>
                                        {{ $i->nama_instr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="instruktur_id_2" class="block text-sm font-medium text-gray-700">Instruktur Kedua (Opsional)</label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="instruktur_id_2" name="instruktur_id_2">
                                <option value="">-- Pilih Instruktur --</option>
                                @foreach($instruktur as $i)
                                    <option value="{{ $i->id }}"
                                        {{ $kursus->instruktur_id_2 == $i->id ? 'selected' : '' }}>
                                        {{ $i->nama_instr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="periode" class="block text-sm font-medium text-gray-700">Periode</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="periode" name="periode" value="{{ $kursus->periode }}" placeholder="Contoh: Februari 2026">
                        </div>

                        <div>
                            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="tanggal_mulai" name="tanggal_mulai" value="{{ $kursus->tanggal_mulai }}" required>
                        </div>

                        <div>
                            <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                            <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="tanggal_selesai" name="tanggal_selesai" value="{{ $kursus->tanggal_selesai }}" required>
                        </div>

                        <div>
                            <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
                            <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="harga" name="harga" value="{{ $kursus->harga }}" required>
                        </div>

                        <div>
                            <label for="harga_upi" class="block text-sm font-medium text-gray-700">Harga UPI (Opsional)</label>
                            <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="harga_upi" name="harga_upi" value="{{ $kursus->harga_upi }}">
                        </div>

                        <div>
                            <label for="kuota" class="block text-sm font-medium text-gray-700">Kuota</label>
                            <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="kuota" name="kuota" value="{{ $kursus->kuota }}" required>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" id="status" name="status" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="buka" {{ $kursus->status == 'buka' ? 'selected' : '' }}>Buka</option>
                                <option value="tutup" {{ $kursus->status == 'tutup' ? 'selected' : '' }}>Tutup</option>
                                <option value="berjalan" {{ $kursus->status == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                            </select>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"><i class="bi bi-check-circle me-2"></i>Update</button>
                            <a href="{{ route('admin.kursus.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                        </div>
                    </div>
                </form>
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
