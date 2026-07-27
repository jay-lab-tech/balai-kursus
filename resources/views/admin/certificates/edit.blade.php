@extends('layouts.admin')

@section('title', 'Edit Sertifikat')

@section('page-title', 'Edit Sertifikat')

@section('page-description', 'Perbarui draft sertifikat resmi, data peserta, dan tanggal terbit sebelum dokumen dipublish.')

@section('content')
<div class="space-y-8 max-w-5xl">
    <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-sky-500/30 bg-sky-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
            <i class="bi bi-pencil-square text-sky-400"></i>
            Edit Draft
        </div>
        <h1 class="mt-5 text-3xl font-bold text-white">Perbarui sertifikat <span class="text-yellow-300">{{ $certificate->certificate_name }}</span>.</h1>
        <p class="mt-3 max-w-3xl text-base leading-7 text-slate-300">Gunakan formulir ini untuk memperbaiki relasi kursus, peserta, tanggal terbit, atau metadata sertifikat sebelum dokumen diterbitkan ke peserta.</p>
    </section>

    @if($errors->any())
        <div class="rounded-[1.5rem] border border-sky-500/20 bg-sky-600/10 px-5 py-4 text-sky-100 shadow-lg">
            <div class="flex items-start gap-3">
                <i class="bi bi-exclamation-octagon-fill mt-0.5 text-lg text-sky-300"></i>
                <div>
                    <p class="font-semibold">Perubahan belum bisa disimpan</p>
                    <ul class="mt-2 space-y-1 text-sm text-sky-100/90">
                        @foreach($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.certificates.update', $certificate->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="grid gap-6 xl:grid-cols-[1.3fr_0.9fr]">
            <div class="admin-panel rounded-[2rem] p-6 sm:p-8">
                <h2 class="text-xl font-bold text-white">Data Sertifikat</h2>
                <div class="mt-6 space-y-6">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-300">Nama Sertifikat</label>
                        <input type="text" name="certificate_name" value="{{ old('certificate_name', $certificate->certificate_name) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Kursus</label>
                            <select name="course_id" class="admin-native-select w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required id="course-select">
                                <option value="">Pilih Kursus</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id', $certificate->course_id) == $course->id ? 'selected' : '' }}>
                                        {{ $course->nama }}{{ $course->program ? ' - ' . $course->program->nama : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Tanggal Terbit</label>
                            <input type="date" name="issued_date" value="{{ old('issued_date', optional($certificate->issued_date)->toDateString()) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-300">Peserta</label>
                        <select name="participant_id" class="admin-native-select w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required id="participant-select">
                            <option value="">Pilih Peserta</option>
                        </select>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
                    <h2 class="text-xl font-bold text-white">Metadata Dokumen</h2>
                    <dl class="mt-5 space-y-4 text-sm">
                        <div class="flex items-start justify-between gap-3">
                            <dt class="text-slate-400">Status</dt>
                            <dd>
                                @if($certificate->status === \App\Models\Certificate::STATUS_DRAFT)
                                    <span class="rounded-full border border-yellow-400/20 bg-yellow-400/10 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-yellow-300">Draft</span>
                                @elseif($certificate->status === \App\Models\Certificate::STATUS_REVOKED)
                                    <span class="rounded-full border border-sky-400/20 bg-sky-500/10 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Revoked</span>
                                @else
                                    <span class="rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-300">Published</span>
                                @endif
                            </dd>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <dt class="text-slate-400">Nomor Sertifikat</dt>
                            <dd class="text-right text-white">{{ $certificate->certificate_number ?? '-' }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <dt class="text-slate-400">Nomor Urut</dt>
                            <dd class="text-right text-white">{{ $certificate->serial_number ?? '-' }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <dt class="text-slate-400">Template</dt>
                            <dd class="text-right text-white">{{ $certificate->template->name ?? ($activeTemplate->name ?? '-') }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
                    <h2 class="text-xl font-bold text-white">Template Aktif</h2>
                    @if($activeTemplate)
                        <p class="mt-4 text-sm text-slate-300">{{ $activeTemplate->institution_name }}</p>
                        <p class="text-sm text-slate-400">{{ $activeTemplate->unit_name }}</p>
                        <p class="mt-4 text-sm text-white">{{ $activeTemplate->signer_name }}</p>
                        <p class="text-sm text-slate-400">{{ $activeTemplate->signer_title }}</p>
                        <p class="mt-1 text-xs text-slate-500">NIP {{ $activeTemplate->signer_nip }}</p>
                    @endif
                </section>
            </aside>
        </section>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.certificates.preview', $certificate->id) }}" target="_blank" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                <i class="bi bi-eye"></i>
                Preview PDF
            </a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-600 to-sky-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-sky-500 hover:to-sky-600">
                <i class="bi bi-check-circle-fill"></i>
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.certificates.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
    </form>

    <form action="{{ route('admin.certificates.destroy', $certificate->id) }}" method="POST" class="admin-panel rounded-[2rem] p-6" onsubmit="return confirm('Yakin hapus sertifikat ini?')">
        @csrf
        @method('DELETE')
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-white">Hapus Sertifikat</h2>
                <p class="mt-2 text-sm text-slate-400">Tindakan ini permanen dan akan menghapus draft atau dokumen sertifikat dari daftar admin.</p>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl border border-sky-500/20 bg-sky-600/10 px-5 py-3 text-sm font-semibold text-sky-200 transition hover:bg-sky-600/20">
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

loadParticipants(courseSelect.value, {{ old('participant_id', $certificate->participant_id) }});
</script>
@endsection
