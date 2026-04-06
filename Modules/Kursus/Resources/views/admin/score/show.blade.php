@extends('layouts.admin')

@section('title', 'Detail Tes Penempatan')

@section('page-title', 'Detail Tes Penempatan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $score->pendaftaran->peserta->user->name ?? '-' }}</h2>
            <p class="text-sm text-gray-500">{{ $score->pendaftaran->nomor }} - {{ $score->pendaftaran->program->nama ?? '-' }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.score.edit', $score) }}" class="rounded-xl bg-yellow-500 px-4 py-2 text-sm font-semibold text-white hover:bg-yellow-600">Edit</a>
            <form action="{{ route('admin.score.destroy', $score) }}" method="POST" onsubmit="return confirm('Hapus hasil tes penempatan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Hapus</button>
            </form>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <div class="rounded-2xl bg-white p-6 shadow">
            <h3 class="text-lg font-bold text-gray-900">Komponen Nilai</h3>
            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach(['listening', 'speaking', 'reading', 'writing', 'assignment'] as $field)
                    <div class="rounded-2xl bg-gray-50 p-4">
                        <p class="text-xs uppercase tracking-wider text-gray-500">{{ ucfirst($field) }}</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900">{{ $score->{$field} }}</p>
                    </div>
                @endforeach
                <div class="rounded-2xl bg-blue-50 p-4">
                    <p class="text-xs uppercase tracking-wider text-blue-600">Nilai Akhir</p>
                    <p class="mt-2 text-2xl font-bold text-blue-900">{{ $score->final_score }}</p>
                </div>
            </div>

            @if($score->keterangan)
                <div class="mt-6 rounded-2xl border border-gray-200 p-4">
                    <p class="text-sm font-semibold text-gray-700">Catatan Evaluator</p>
                    <p class="mt-2 text-sm text-gray-600">{{ $score->keterangan }}</p>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl bg-white p-6 shadow">
                <h3 class="text-lg font-bold text-gray-900">Hasil Placement</h3>
                <div class="mt-4 space-y-3 text-sm text-gray-600">
                    <p><span class="font-semibold text-gray-900">Status:</span> {{ strtoupper($score->status) }}</p>
                    <p><span class="font-semibold text-gray-900">Level:</span> {{ $score->pendaftaran->level->nama ?? 'Belum terpetakan' }}</p>
                    <p><span class="font-semibold text-gray-900">Kelas:</span> {{ $score->pendaftaran->kursus->nama ?? 'Belum ditempatkan' }}</p>
                    <p><span class="font-semibold text-gray-900">Status Pendaftaran:</span> {{ str_replace('_', ' ', ucfirst($score->pendaftaran->status_pendaftaran)) }}</p>
                    <p><span class="font-semibold text-gray-900">Evaluator:</span> {{ $score->evaluator->nama_instr ?? '-' }}</p>
                    <p><span class="font-semibold text-gray-900">Tanggal Evaluasi:</span> {{ optional($score->evaluated_at)->format('d M Y') ?? '-' }}</p>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow">
                <a href="{{ route('admin.score.index') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-gray-800 px-4 py-3 text-sm font-semibold text-white hover:bg-gray-700">Kembali ke Daftar</a>
            </div>
        </div>
    </div>
</div>
@endsection
