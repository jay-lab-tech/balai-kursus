@extends('layouts.admin')
@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Sertifikat</h1>
        <form action="{{ route('admin.certificates.update', $certificate->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block font-semibold mb-1">Nama Sertifikat</label>
                <input type="text" name="certificate_name" class="form-input w-full border-gray-300 rounded" value="{{ $certificate->certificate_name }}" required>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Gambar Sertifikat</label>
                <input type="file" name="certificate_image" class="form-input w-full border-gray-300 rounded">
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $certificate->certificate_image_path) }}" width="120">
                </div>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Kursus</label>
                <select name="course_id" class="form-select w-full border-gray-300 rounded" required id="course-select">
                    <option value="">Pilih Kursus</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ $certificate->course_id == $course->id ? 'selected' : '' }}>{{ $course->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Peserta</label>
                <select name="participant_id" class="form-select w-full border-gray-300 rounded" required id="participant-select">
                    <option value="">Pilih Peserta</option>
                </select>
                <small class="text-gray-500">Peserta ditampilkan berdasarkan kursus yang dipilih</small>
            </div>
            <div class="flex gap-2 mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow">Update</button>
                <a href="{{ route('admin.certificates.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-4 py-2 rounded shadow">Batal</a>
            </div>
        </form>
        <form action="{{ route('admin.certificates.destroy', $certificate->id) }}" method="POST" class="mt-6">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded shadow" onclick="return confirm('Yakin hapus sertifikat ini?')">Hapus Sertifikat</button>
        </form>
    </div>
</div>
<script>
const courseSelect = document.getElementById('course-select');
const participantSelect = document.getElementById('participant-select');
function loadParticipants(courseId, selectedId = null) {
    fetch('/admin/get-participants?course_id=' + courseId)
        .then(res => res.json())
        .then(data => {
            participantSelect.innerHTML = '<option value="">Pilih Peserta</option>';
            data.forEach(peserta => {
                participantSelect.innerHTML += `<option value="${peserta.id}" ${selectedId == peserta.id ? 'selected' : ''}>${peserta.nomor_peserta} - ${peserta.nama}</option>`;
            });
        });
}
courseSelect.addEventListener('change', function() {
    loadParticipants(this.value);
});
// Initial load
loadParticipants(courseSelect.value, {{ $certificate->participant_id }});
</script>
@endsection
