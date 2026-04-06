@extends('layouts.admin')

@section('title', 'Edit Tes Penempatan')

@section('page-title', 'Edit Tes Penempatan')

@section('content')
<div class="max-w-5xl space-y-6">
    <div class="rounded-2xl bg-white p-6 shadow">
        <form method="POST" action="{{ route('admin.score.update', $score) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="pendaftaran_id" class="block text-sm font-medium text-gray-700">Pendaftaran Program</label>
                <select id="pendaftaran_id" name="pendaftaran_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @foreach($pendaftarans as $pendaftaran)
                        <option value="{{ $pendaftaran->id }}" {{ (string) old('pendaftaran_id', $score->pendaftaran_id) === (string) $pendaftaran->id ? 'selected' : '' }}>
                            {{ $pendaftaran->nomor }} - {{ $pendaftaran->peserta->user->name ?? '-' }} - {{ $pendaftaran->program->nama ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                @foreach(['listening', 'speaking', 'reading', 'writing', 'assignment'] as $field)
                    <div>
                        <label for="{{ $field }}" class="block text-sm font-medium text-gray-700">{{ ucfirst($field) }}</label>
                        <input type="number" id="{{ $field }}" name="{{ $field }}" min="0" max="100" value="{{ old($field, $score->{$field}) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                @endforeach
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="final_score" class="block text-sm font-medium text-gray-700">Nilai Akhir</label>
                    <input type="number" step="0.01" id="final_score" name="final_score" min="0" max="100" value="{{ old('final_score', $score->final_score) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status Hasil</label>
                    <select id="status" name="status" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="pass" {{ old('status', $score->status) === 'pass' ? 'selected' : '' }}>Pass</option>
                        <option value="fail" {{ old('status', $score->status) === 'fail' ? 'selected' : '' }}>Fail</option>
                        <option value="pending" {{ old('status', $score->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="evaluated_by" class="block text-sm font-medium text-gray-700">Evaluator</label>
                    <select id="evaluated_by" name="evaluated_by" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @foreach($instrukturs as $instruktur)
                            <option value="{{ $instruktur->id }}" {{ (string) old('evaluated_by', $score->evaluated_by) === (string) $instruktur->id ? 'selected' : '' }}>
                                {{ $instruktur->nama_instr }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="evaluated_at" class="block text-sm font-medium text-gray-700">Tanggal Evaluasi</label>
                    <input type="date" id="evaluated_at" name="evaluated_at" value="{{ old('evaluated_at', optional($score->evaluated_at)->format('Y-m-d')) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700">Catatan</label>
                <textarea id="keterangan" name="keterangan" rows="4" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('keterangan', $score->keterangan) }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">Update Hasil Tes</button>
                <a href="{{ route('admin.score.index') }}" class="rounded-xl bg-gray-200 px-5 py-3 font-semibold text-gray-800 hover:bg-gray-300">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
