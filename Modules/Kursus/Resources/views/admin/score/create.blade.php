@extends('layouts.admin')

@section('title', 'Input Tes Penempatan')

@section('page-title', 'Input Tes Penempatan')

@section('content')
<div class="max-w-5xl space-y-6">
    <div class="rounded-2xl bg-white p-6 shadow">
        <p class="mb-4 text-sm text-gray-500">Setelah hasil tes disimpan, sistem akan langsung memilih level yang cocok dan mencoba menempatkan peserta ke kelas yang kuotanya masih tersedia.</p>

        <form method="POST" action="{{ route('admin.score.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="pendaftaran_id" class="block text-sm font-medium text-gray-700">Pendaftaran Program</label>
                <select id="pendaftaran_id" name="pendaftaran_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Pilih pendaftaran</option>
                    @foreach($pendaftarans as $pendaftaran)
                        <option value="{{ $pendaftaran->id }}" {{ (string) old('pendaftaran_id', $selectedPendaftaranId) === (string) $pendaftaran->id ? 'selected' : '' }}>
                            {{ $pendaftaran->nomor }} - {{ $pendaftaran->peserta->user->name ?? '-' }} - {{ $pendaftaran->program->nama ?? '-' }}
                        </option>
                    @endforeach
                </select>
                @error('pendaftaran_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                @foreach(['listening', 'speaking', 'reading', 'writing', 'assignment'] as $field)
                    <div>
                        <label for="{{ $field }}" class="block text-sm font-medium text-gray-700">{{ ucfirst($field) }}</label>
                        <input type="number" id="{{ $field }}" name="{{ $field }}" min="0" max="100" value="{{ old($field) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error($field)<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                @endforeach
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="final_score" class="block text-sm font-medium text-gray-700">Nilai Akhir</label>
                    <input type="number" step="0.01" id="final_score" name="final_score" min="0" max="100" value="{{ old('final_score') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('final_score')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status Hasil</label>
                    <select id="status" name="status" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="pass" {{ old('status') === 'pass' ? 'selected' : '' }}>Pass</option>
                        <option value="fail" {{ old('status') === 'fail' ? 'selected' : '' }}>Fail</option>
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="evaluated_by" class="block text-sm font-medium text-gray-700">Evaluator</label>
                    <select id="evaluated_by" name="evaluated_by" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Pilih evaluator</option>
                        @foreach($instrukturs as $instruktur)
                            <option value="{{ $instruktur->id }}" {{ (string) old('evaluated_by') === (string) $instruktur->id ? 'selected' : '' }}>
                                {{ $instruktur->nama_instr }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="evaluated_at" class="block text-sm font-medium text-gray-700">Tanggal Evaluasi</label>
                    <input type="date" id="evaluated_at" name="evaluated_at" value="{{ old('evaluated_at', now()->format('Y-m-d')) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700">Catatan</label>
                <textarea id="keterangan" name="keterangan" rows="4" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">Simpan Hasil Tes</button>
                <a href="{{ route('admin.score.index') }}" class="rounded-xl bg-gray-200 px-5 py-3 font-semibold text-gray-800 hover:bg-gray-300">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
