@extends('layouts.admin')

@section('title', 'Detail Tes Penempatan')

@section('page-title', 'Detail Tes Penempatan')

@section('page-description', 'Lihat detail komponen nilai, hasil placement, evaluator, dan status pendaftaran peserta.')

@section('content')
<div class="space-y-8">
    <section class="admin-panel overflow-hidden rounded-[2rem] p-6 sm:p-8">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-sky-500/30 bg-sky-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
                    <i class="bi bi-file-earmark-bar-graph-fill text-sky-400"></i>
                    Detail Placement
                </div>
                <h1 class="mt-5 text-3xl font-bold text-white sm:text-4xl">{{ $score->pendaftaran->peserta->user->name ?? '-' }}</h1>
                <p class="mt-3 text-base text-slate-300">{{ $score->pendaftaran->nomor }} • {{ $score->pendaftaran->program->nama ?? '-' }}</p>
                <p class="mt-4 max-w-3xl text-base leading-7 text-slate-300">Lihat ringkasan lengkap hasil tes, keputusan placement, evaluator yang menilai, dan status pendaftaran peserta setelah proses evaluasi.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.score.edit', $score) }}" class="inline-flex items-center gap-2 rounded-2xl border border-yellow-400/20 bg-yellow-400/10 px-4 py-3 text-sm font-semibold text-yellow-300 transition hover:bg-yellow-400/15">
                    <i class="bi bi-pencil-square"></i>
                    Edit
                </a>
                <form action="{{ route('admin.score.destroy', $score) }}" method="POST" onsubmit="return confirm('Hapus hasil tes penempatan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl border border-sky-500/20 bg-sky-600/10 px-4 py-3 text-sm font-semibold text-sky-200 transition hover:bg-sky-600/20">
                        <i class="bi bi-trash"></i>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
        <div class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-2xl font-bold text-white">
                <i class="bi bi-grid-3x3-gap-fill mr-3 text-yellow-300"></i>Komponen Nilai
            </h2>
            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach(['listening', 'speaking', 'reading', 'writing', 'assignment'] as $field)
                    <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ ucfirst($field) }}</p>
                        <p class="mt-3 text-3xl font-bold text-white">{{ $score->{$field} }}</p>
                    </div>
                @endforeach
                <div class="rounded-[1.5rem] bg-gradient-to-br from-yellow-400 to-yellow-500 p-4 text-slate-900 shadow-xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] opacity-75">Nilai Akhir</p>
                    <p class="mt-3 text-3xl font-bold">{{ $score->final_score }}</p>
                </div>
            </div>

            @if($score->keterangan)
                <div class="mt-6 rounded-[1.5rem] border border-white/10 bg-slate-950/50 p-5">
                    <p class="text-sm font-semibold text-white">Catatan Evaluator</p>
                    <p class="mt-2 text-sm leading-6 text-slate-300">{{ $score->keterangan }}</p>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="admin-panel rounded-[2rem] p-6">
                <h2 class="text-xl font-bold text-white">
                    <i class="bi bi-check2-square mr-3 text-yellow-300"></i>Hasil Placement
                </h2>
                <div class="mt-5 space-y-4 text-sm text-slate-300">
                    <p><span class="font-semibold text-white">Status Hasil:</span> {{ strtoupper($score->status) }}</p>
                    <p><span class="font-semibold text-white">Level:</span> {{ $score->pendaftaran->level->nama ?? 'Belum terpetakan' }}</p>
                    <p><span class="font-semibold text-white">Kelas:</span> {{ $score->pendaftaran->kursus->nama ?? 'Belum ditempatkan' }}</p>
                    <p><span class="font-semibold text-white">Status Pendaftaran:</span> {{ str_replace('_', ' ', ucfirst($score->pendaftaran->status_pendaftaran)) }}</p>
                    <p><span class="font-semibold text-white">Evaluator:</span> {{ $score->evaluator->nama_instr ?? '-' }}</p>
                    <p><span class="font-semibold text-white">Tanggal Evaluasi:</span> {{ optional($score->evaluated_at)->format('d M Y') ?? '-' }}</p>
                </div>
            </div>

            <div class="rounded-[2rem] bg-gradient-to-br from-sky-600 to-sky-700 p-6 text-white shadow-2xl">
                <h2 class="text-xl font-bold">Tindakan Lanjut</h2>
                <p class="mt-3 text-sm leading-6 text-sky-100/90">Jika hasil placement berubah, sistem akan memperbarui data level dan mencoba menempatkan ulang peserta sesuai kelas yang masih tersedia.</p>
                <a href="{{ route('admin.score.index') }}" class="mt-5 inline-flex w-full items-center justify-center rounded-2xl bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/20">Kembali ke Daftar</a>
            </div>
        </div>
    </section>
</div>
@endsection
