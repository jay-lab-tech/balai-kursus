@extends('layouts.admin')

@section('title', 'Generate Batch Sertifikat')

@section('page-title', 'Generate Batch Sertifikat')

@section('page-description', 'Buat draft sertifikat sekaligus untuk seluruh peserta eligible dalam satu kursus.')

@section('content')
<div class="space-y-8 max-w-5xl">
    <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
            <i class="bi bi-collection-fill text-red-400"></i>
            Generate Batch
        </div>
        <h1 class="mt-5 text-3xl font-bold text-white">Bangun draft sertifikat per kursus sekaligus.</h1>
        <p class="mt-3 max-w-3xl text-base leading-7 text-slate-300">Sistem akan mengambil semua peserta eligible dari kursus terpilih, lalu membuat draft sertifikat resmi untuk mereka. Sertifikat yang sudah published tidak akan ditimpa.</p>
    </section>

    @if($errors->any())
        <div class="rounded-[1.5rem] border border-red-500/20 bg-red-600/10 px-5 py-4 text-red-100 shadow-lg">
            <div class="flex items-start gap-3">
                <i class="bi bi-exclamation-octagon-fill mt-0.5 text-lg text-red-300"></i>
                <div>
                    <p class="font-semibold">Batch belum bisa diproses</p>
                    <ul class="mt-2 space-y-1 text-sm text-red-100/90">
                        @foreach($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.certificates.batch.store') }}" method="POST" class="space-y-6">
        @csrf

        <section class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
            <div class="admin-panel rounded-[2rem] p-6 sm:p-8">
                <h2 class="text-xl font-bold text-white">Parameter Batch</h2>
                <div class="mt-6 space-y-6">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-300">Nama Sertifikat</label>
                        <input type="text" name="certificate_name" value="{{ old('certificate_name') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white" placeholder="Kosongkan untuk memakai format otomatis">
                        <small class="mt-2 block text-slate-400">Kalau dikosongkan, sistem memakai format <span class="font-mono text-slate-300">Sertifikat {nama kursus}</span>.</small>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-300">Kursus</label>
                        <select name="course_id" id="course_id" class="admin-native-select w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white" required>
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
                        <input type="date" name="issued_date" value="{{ old('issued_date', now()->toDateString()) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white" required>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Status Pendaftaran</label>
                            <select name="registration_status" class="admin-native-select w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white">
                                <option value="selesai" {{ old('registration_status', 'selesai') === 'selesai' ? 'selected' : '' }}>Selesai saja</option>
                                <option value="aktif" {{ old('registration_status') === 'aktif' ? 'selected' : '' }}>Aktif saja</option>
                                <option value="aktif_selesai" {{ old('registration_status') === 'aktif_selesai' ? 'selected' : '' }}>Aktif + selesai</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Status Pembayaran</label>
                            <select name="payment_status" class="admin-native-select w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white">
                                <option value="lunas" {{ old('payment_status', 'lunas') === 'lunas' ? 'selected' : '' }}>Lunas saja</option>
                                <option value="all" {{ old('payment_status') === 'all' ? 'selected' : '' }}>Semua status pembayaran</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-300">Minimal Kehadiran (%)</label>
                        <input type="number" min="0" max="100" step="1" name="min_attendance_percent" value="{{ old('min_attendance_percent') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white" placeholder="Contoh: 75">
                        <small class="mt-2 block text-slate-400">Kosongkan kalau belum ingin memfilter berdasarkan absensi.</small>
                    </div>
                </div>
            </div>

            <aside class="admin-panel rounded-[2rem] p-6 sm:p-8">
                <h2 class="text-xl font-bold text-white">Aturan Batch</h2>
                <ul class="mt-5 space-y-3 text-sm text-slate-300">
                    <li class="flex items-start gap-3"><i class="bi bi-check-circle-fill mt-0.5 text-emerald-300"></i><span>Admin bisa membatasi batch berdasarkan status pendaftaran dan pembayaran.</span></li>
                    <li class="flex items-start gap-3"><i class="bi bi-check-circle-fill mt-0.5 text-emerald-300"></i><span>Draft lama boleh diperbarui, termasuk nomor dan tanggal jika diperlukan.</span></li>
                    <li class="flex items-start gap-3"><i class="bi bi-check-circle-fill mt-0.5 text-emerald-300"></i><span>Kalau minimal kehadiran diisi, hanya peserta yang memenuhi persentase itu yang akan masuk batch.</span></li>
                    <li class="flex items-start gap-3"><i class="bi bi-exclamation-triangle-fill mt-0.5 text-yellow-300"></i><span>Sertifikat yang sudah published tidak akan ditimpa otomatis.</span></li>
                    <li class="flex items-start gap-3"><i class="bi bi-image-fill mt-0.5 text-yellow-300"></i><span>Template aktif saat ini: <strong class="text-white">{{ $activeTemplate?->name ?? 'Belum ada template aktif' }}</strong></span></li>
                </ul>
            </aside>
        </section>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-red-600 to-red-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-red-500 hover:to-red-600" {{ !$activeTemplate ? 'disabled' : '' }}>
                <i class="bi bi-magic"></i>
                Generate Draft Batch
            </button>
            <button type="submit" formaction="{{ route('admin.certificates.batch.publish') }}" class="inline-flex items-center gap-2 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-5 py-3 text-sm font-semibold text-emerald-200 transition hover:bg-emerald-500/20">
                <i class="bi bi-check2-all"></i>
                Publish Draft per Kursus
            </button>
            <a href="{{ route('admin.certificates.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection
