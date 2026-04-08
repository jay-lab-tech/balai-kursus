@extends('layouts.admin')

@section('title', 'Edit Tes Penempatan')

@section('page-title', 'Edit Tes Penempatan')

@section('page-description', 'Perbarui hasil placement test agar level, kelas, dan evaluasi peserta tetap akurat.')

@section('content')
<div class="space-y-8 max-w-6xl">
    <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
            <i class="bi bi-pencil-square text-red-400"></i>
            Update Placement
        </div>
        <h1 class="mt-5 text-3xl font-bold text-white">Perbarui hasil tes penempatan peserta.</h1>
        <p class="mt-3 max-w-3xl text-base leading-7 text-slate-300">Gunakan formulir ini untuk memperbaiki skor, status, evaluator, dan catatan sehingga hasil placement tetap sinkron dengan kondisi peserta saat ini.</p>
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

    <form method="POST" action="{{ route('admin.score.update', $score) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Data Pendaftaran</h2>
            <div class="mt-6">
                <label for="pendaftaran_id" class="mb-2 block text-sm font-medium text-slate-300">Pendaftaran Program</label>
                <select id="pendaftaran_id" name="pendaftaran_id" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                    @foreach($pendaftarans as $pendaftaran)
                        <option value="{{ $pendaftaran->id }}" {{ (string) old('pendaftaran_id', $score->pendaftaran_id) === (string) $pendaftaran->id ? 'selected' : '' }}>
                            {{ $pendaftaran->nomor }} - {{ $pendaftaran->peserta->user->name ?? '-' }} - {{ $pendaftaran->program->nama ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </section>

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Komponen Nilai</h2>
            <div class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach(['listening', 'speaking', 'reading', 'writing', 'assignment'] as $field)
                    <div>
                        <label for="{{ $field }}" class="mb-2 block text-sm font-medium text-slate-300">{{ ucfirst($field) }}</label>
                        <input type="number" id="{{ $field }}" name="{{ $field }}" min="0" max="100" value="{{ old($field, $score->{$field}) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Hasil Evaluasi</h2>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <label for="final_score" class="mb-2 block text-sm font-medium text-slate-300">Nilai Akhir</label>
                    <input type="number" step="0.01" id="final_score" name="final_score" min="0" max="100" value="{{ old('final_score', $score->final_score) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
                <div>
                    <label for="status" class="mb-2 block text-sm font-medium text-slate-300">Status Hasil</label>
                    <select id="status" name="status" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                        <option value="pass" {{ old('status', $score->status) === 'pass' ? 'selected' : '' }}>Pass</option>
                        <option value="fail" {{ old('status', $score->status) === 'fail' ? 'selected' : '' }}>Fail</option>
                        <option value="pending" {{ old('status', $score->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <label for="evaluated_by" class="mb-2 block text-sm font-medium text-slate-300">Evaluator</label>
                    <select id="evaluated_by" name="evaluated_by" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                        @foreach($instrukturs as $instruktur)
                            <option value="{{ $instruktur->id }}" {{ (string) old('evaluated_by', $score->evaluated_by) === (string) $instruktur->id ? 'selected' : '' }}>{{ $instruktur->nama_instr }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="evaluated_at" class="mb-2 block text-sm font-medium text-slate-300">Tanggal Evaluasi</label>
                    <input type="date" id="evaluated_at" name="evaluated_at" value="{{ old('evaluated_at', optional($score->evaluated_at)->format('Y-m-d')) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
            </div>
            <div class="mt-6">
                <label for="keterangan" class="mb-2 block text-sm font-medium text-slate-300">Catatan</label>
                <textarea id="keterangan" name="keterangan" rows="4" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10">{{ old('keterangan', $score->keterangan) }}</textarea>
            </div>
        </section>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-red-600 to-red-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-red-500 hover:to-red-600">
                <i class="bi bi-check-circle-fill"></i>
                Update Hasil Tes
            </button>
            <a href="{{ route('admin.score.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection
