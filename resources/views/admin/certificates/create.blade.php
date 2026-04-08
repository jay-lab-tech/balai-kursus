@extends('layouts.admin')

@section('title', 'Buat Sertifikat')

@section('page-title', 'Buat Sertifikat')

@section('page-description', 'Buat sertifikat baru, pilih kursus dan peserta, lalu unggah file sertifikat untuk dipublikasikan nanti.')

@section('content')
<div class="space-y-8 max-w-4xl">
    <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
            <i class="bi bi-plus-circle-fill text-red-400"></i>
            Sertifikat Baru
        </div>
        <h1 class="mt-5 text-3xl font-bold text-white">Buat sertifikat baru untuk peserta.</h1>
        <p class="mt-3 max-w-3xl text-base leading-7 text-slate-300">Pilih kursus terlebih dahulu, lalu sistem akan menampilkan peserta yang relevan agar sertifikat bisa dihubungkan dengan benar sebelum dipublish.</p>
    </section>

    @if($errors->any())
        <div class="rounded-[1.5rem] border border-red-500/20 bg-red-600/10 px-5 py-4 text-red-100 shadow-lg">
            <div class="flex items-start gap-3">
                <i class="bi bi-exclamation-octagon-fill mt-0.5 text-lg text-red-300"></i>
                <div>
                    <p class="font-semibold">Form belum bisa disimpan</p>
                    <ul class="mt-2 space-y-1 text-sm text-red-100/90">
                        @foreach($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.certificates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Informasi Sertifikat</h2>
            <div class="mt-6 space-y-6">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Nama Sertifikat</label>
                    <input type="text" name="certificate_name" value="{{ old('certificate_name') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Gambar Sertifikat</label>
                    <input type="file" name="certificate_image" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-slate-200 file:mr-4 file:rounded-xl file:border-0 file:bg-red-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-red-500" required>
                </div>
            </div>
        </section>

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Relasi Kursus dan Peserta</h2>
            <div class="mt-6 space-y-6">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Kursus</label>
                    <select name="course_id" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required id="course-select">
                        <option value="">Pilih Kursus</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Peserta</label>
                    <select name="participant_id" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required id="participant-select">
                        <option value="">Pilih Peserta</option>
                    </select>
                    <small class="mt-2 block text-slate-400">Peserta ditampilkan berdasarkan kursus yang dipilih.</small>
                </div>
            </div>
        </section>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-red-600 to-red-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-red-500 hover:to-red-600">
                <i class="bi bi-check-circle-fill"></i>
                Simpan Sertifikat
            </button>
            <a href="{{ route('admin.certificates.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                <i class="bi bi-arrow-left"></i>
                Batal
            </a>
        </div>
    </form>
</div>
<script>
const courseSelect = document.getElementById('course-select');
const participantSelect = document.getElementById('participant-select');
const initialParticipantId = @json(old('participant_id'));

function loadParticipants(courseId, selectedId = null) {
    if (!courseId) {
        participantSelect.innerHTML = '<option value="">Pilih Peserta</option>';
        return;
    }

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

if (courseSelect.value) {
    loadParticipants(courseSelect.value, initialParticipantId);
}
</script>
@endsection
