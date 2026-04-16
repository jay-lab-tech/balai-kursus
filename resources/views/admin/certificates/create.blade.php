@extends('layouts.admin')

@section('title', 'Buat Draft Sertifikat')

@section('page-title', 'Buat Draft Sertifikat')

@section('page-description', 'Pilih kursus dan peserta, lalu sistem akan membangun draft sertifikat resmi dari template aktif.')

@section('content')
<div class="space-y-8 max-w-5xl">
    <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
            <i class="bi bi-plus-circle-fill text-red-400"></i>
            Draft Sertifikat
        </div>
        <h1 class="mt-5 text-3xl font-bold text-white">Bangun sertifikat resmi dari data kursus.</h1>
        <p class="mt-3 max-w-3xl text-base leading-7 text-slate-300">Admin cukup memilih kursus, peserta, dan tanggal terbit. Sistem akan menarik data program, jam pelajaran, serta template resmi secara otomatis.</p>
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

    @if(!$activeTemplate)
        <div class="rounded-[1.5rem] border border-yellow-400/20 bg-yellow-400/10 px-5 py-4 text-yellow-100 shadow-lg">
            <div class="flex items-start gap-3">
                <i class="bi bi-exclamation-triangle-fill mt-0.5 text-lg text-yellow-300"></i>
                <div>
                    <p class="font-semibold">Belum ada template aktif</p>
                    <p class="mt-1 text-sm text-yellow-100/90">Aktifkan atau buat template sertifikat terlebih dahulu sebelum membuat draft baru.</p>
                    <a href="{{ route('admin.templates.index') }}" class="mt-3 inline-flex items-center gap-2 rounded-xl border border-yellow-300/20 bg-white/5 px-4 py-2 text-sm font-semibold text-yellow-100 transition hover:bg-white/10">
                        <i class="bi bi-layout-text-window-reverse"></i>
                        Kelola Template
                    </a>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.certificates.store') }}" method="POST" class="space-y-6">
        @csrf

        <section class="grid gap-6 xl:grid-cols-[1.3fr_0.9fr]">
            <div class="admin-panel rounded-[2rem] p-6 sm:p-8">
                <h2 class="text-xl font-bold text-white">Data Sertifikat</h2>
                <div class="mt-6 space-y-6">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-300">Nama Sertifikat</label>
                        <input type="text" name="certificate_name" value="{{ old('certificate_name') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" placeholder="Contoh: Sertifikat IELTS Preparation Course" required>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Kursus</label>
                            <select name="course_id" class="admin-native-select w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required id="course-select">
                                <option value="">Pilih Kursus</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->nama }}{{ $course->program ? ' - ' . $course->program->nama : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Tanggal Terbit</label>
                            <input type="date" name="issued_date" value="{{ old('issued_date', now()->toDateString()) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-300">Peserta</label>
                        <select name="participant_id" class="admin-native-select w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required id="participant-select">
                            <option value="">Pilih Peserta</option>
                        </select>
                        <small class="mt-2 block text-slate-400">Peserta ditampilkan berdasarkan kursus yang dipilih dan status pendaftaran yang sudah aktif atau selesai.</small>
                    </div>
                </div>
            </div>

            <aside class="admin-panel rounded-[2rem] p-6 sm:p-8">
                <h2 class="text-xl font-bold text-white">Template Aktif</h2>
                @if($activeTemplate)
                    <div class="mt-5 rounded-[1.5rem] border border-white/10 bg-white/5 p-5">
                        <p class="text-sm font-semibold text-white">{{ $activeTemplate->name }}</p>
                        <p class="mt-2 text-sm text-slate-300">{{ $activeTemplate->institution_name }}</p>
                        <p class="text-sm text-slate-400">{{ $activeTemplate->unit_name }}</p>

                        <dl class="mt-5 space-y-3 text-sm">
                            <div class="flex items-start justify-between gap-3">
                                <dt class="text-slate-400">Penandatangan</dt>
                                <dd class="text-right text-white">{{ $activeTemplate->signer_name }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-3">
                                <dt class="text-slate-400">Jabatan</dt>
                                <dd class="text-right text-white">{{ $activeTemplate->signer_title }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-3">
                                <dt class="text-slate-400">Prefix Nomor</dt>
                                <dd class="text-right text-white">{{ $activeTemplate->certificate_prefix }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-3">
                                <dt class="text-slate-400">Kota Terbit</dt>
                                <dd class="text-right text-white">{{ $activeTemplate->city }}</dd>
                            </div>
                        </dl>

                        <a href="{{ route('admin.templates.edit', $activeTemplate) }}" class="mt-5 inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                            <i class="bi bi-sliders"></i>
                            Ubah Template
                        </a>
                    </div>
                @else
                    <p class="mt-4 text-sm text-slate-300">Belum ada template aktif yang dapat dipakai.</p>
                @endif
            </aside>
        </section>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.certificates.batch.create') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                <i class="bi bi-collection"></i>
                Generate Batch
            </a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-red-600 to-red-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-red-500 hover:to-red-600" {{ !$activeTemplate ? 'disabled' : '' }}>
                <i class="bi bi-check-circle-fill"></i>
                Simpan Draft Sertifikat
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
const participantsEndpoint = @json(route('admin.certificates.participants'));

function loadParticipants(courseId, selectedId = null) {
    if (!courseId) {
        participantSelect.innerHTML = '<option value="">Pilih Peserta</option>';
        return;
    }

    participantSelect.innerHTML = '<option value="">Memuat peserta...</option>';

    fetch(`${participantsEndpoint}?course_id=${encodeURIComponent(courseId)}`)
        .then((res) => {
            if (!res.ok) {
                throw new Error('Gagal memuat peserta');
            }

            return res.json();
        })
        .then((data) => {
            participantSelect.innerHTML = '<option value="">Pilih Peserta</option>';

            if (!Array.isArray(data) || data.length === 0) {
                participantSelect.innerHTML = '<option value="">Tidak ada peserta yang eligible</option>';
                return;
            }

            data.forEach((peserta) => {
                participantSelect.innerHTML += `<option value="${peserta.id}" ${selectedId == peserta.id ? 'selected' : ''}>${peserta.nomor_peserta} - ${peserta.nama}</option>`;
            });
        })
        .catch(() => {
            participantSelect.innerHTML = '<option value="">Gagal memuat peserta</option>';
        });
}

courseSelect.addEventListener('change', function () {
    loadParticipants(this.value);
});

if (courseSelect.value) {
    loadParticipants(courseSelect.value, initialParticipantId);
}
</script>
@endsection
