@extends('layouts.admin')

@section('title', 'Edit Sertifikat')

@section('page-title', 'Edit Sertifikat')

@section('page-description', 'Perbarui informasi sertifikat, file gambar, kursus, dan peserta yang terhubung.')

@section('content')
<div class="space-y-8 max-w-4xl">
    <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
            <i class="bi bi-pencil-square text-red-400"></i>
            Update Sertifikat
        </div>
        <h1 class="mt-5 text-3xl font-bold text-white">Perbarui sertifikat <span class="text-yellow-300">{{ $certificate->certificate_name }}</span>.</h1>
        <p class="mt-3 max-w-3xl text-base leading-7 text-slate-300">Gunakan formulir ini untuk mengganti file sertifikat, memperbaiki nama, atau memindahkan relasinya ke kursus dan peserta yang benar.</p>
    </section>

    @if($errors->any())
        <div class="rounded-[1.5rem] border border-red-500/20 bg-red-600/10 px-5 py-4 text-red-100 shadow-lg">
            <div class="flex items-start gap-3">
                <i class="bi bi-exclamation-octagon-fill mt-0.5 text-lg text-red-300"></i>
                <div>
                    <p class="font-semibold">Perubahan belum bisa disimpan</p>
                    <ul class="mt-2 space-y-1 text-sm text-red-100/90">
                        @foreach($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.certificates.update', $certificate->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Informasi Sertifikat</h2>
            <div class="mt-6 space-y-6">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Nama Sertifikat</label>
                    <input type="text" name="certificate_name" value="{{ old('certificate_name', $certificate->certificate_name) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Gambar Sertifikat</label>
                    <input type="file" name="certificate_image" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-slate-200 file:mr-4 file:rounded-xl file:border-0 file:bg-red-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-red-500">
                    <div class="mt-4 rounded-[1.5rem] border border-white/10 bg-white/5 p-4">
                        <p class="mb-3 text-sm font-medium text-slate-300">Preview gambar saat ini</p>
                        <img src="{{ asset('storage/' . $certificate->certificate_image_path) }}" alt="Preview sertifikat" class="max-h-56 rounded-xl border border-white/10">
                    </div>
                </div>
            </div>
        </section>

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Relasi Kursus dan Peserta</h2>
            <div class="mt-6 space-y-6">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Kursus</label>
                    <select name="course_id" class="admin-native-select w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required id="course-select">
                        <option value="">Pilih Kursus</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $certificate->course_id) == $course->id ? 'selected' : '' }}>{{ $course->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Peserta</label>
                    <select name="participant_id" class="admin-native-select w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required id="participant-select">
                        <option value="">Pilih Peserta</option>
                    </select>
                    <small class="mt-2 block text-slate-400">Peserta ditampilkan berdasarkan kursus yang dipilih.</small>
                </div>
            </div>
        </section>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-red-600 to-red-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-red-500 hover:to-red-600">
                <i class="bi bi-check-circle-fill"></i>
                Update Sertifikat
            </button>
            <a href="{{ route('admin.certificates.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                <i class="bi bi-arrow-left"></i>
                Batal
            </a>
        </div>
    </form>

    <form action="{{ route('admin.certificates.destroy', $certificate->id) }}" method="POST" class="admin-panel rounded-[2rem] p-6" onsubmit="return confirm('Yakin hapus sertifikat ini?')">
        @csrf
        @method('DELETE')
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-white">Hapus Sertifikat</h2>
                <p class="mt-2 text-sm text-slate-400">Tindakan ini permanen dan akan menghapus sertifikat dari daftar admin.</p>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl border border-red-500/20 bg-red-600/10 px-5 py-3 text-sm font-semibold text-red-200 transition hover:bg-red-600/20">
                <i class="bi bi-trash"></i>
                Hapus Sertifikat
            </button>
        </div>
    </form>
</div>
<script>
const courseSelect = document.getElementById('course-select');
const participantSelect = document.getElementById('participant-select');
const participantsEndpoint = @json(route('admin.certificates.participants'));

function loadParticipants(courseId, selectedId = null) {
    if (!courseId) {
        participantSelect.innerHTML = '<option value="">Pilih Peserta</option>';
        return;
    }

    participantSelect.innerHTML = '<option value="">Memuat peserta...</option>';

    fetch(`${participantsEndpoint}?course_id=${encodeURIComponent(courseId)}`)
        .then(res => {
            if (!res.ok) {
                throw new Error('Gagal memuat peserta');
            }

            return res.json();
        })
        .then(data => {
            participantSelect.innerHTML = '<option value="">Pilih Peserta</option>';

            if (!Array.isArray(data) || data.length === 0) {
                participantSelect.innerHTML = '<option value="">Tidak ada peserta pada kursus ini</option>';
                return;
            }

            data.forEach(peserta => {
                participantSelect.innerHTML += `<option value="${peserta.id}" ${selectedId == peserta.id ? 'selected' : ''}>${peserta.nomor_peserta} - ${peserta.nama}</option>`;
            });
        })
        .catch(() => {
            participantSelect.innerHTML = '<option value="">Gagal memuat peserta</option>';
        });
}
courseSelect.addEventListener('change', function() {
    loadParticipants(this.value);
});
loadParticipants(courseSelect.value, {{ old('participant_id', $certificate->participant_id) }});
</script>
@endsection
