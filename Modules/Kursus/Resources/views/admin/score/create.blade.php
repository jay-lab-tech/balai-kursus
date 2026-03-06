@extends('layouts.admin')

@section('title', 'Tambah Nilai Peserta')

@section('page-title', 'Tambah Nilai Peserta')

@section('content')
<div class="space-y-6">
    <div class="max-w-4xl">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form method="POST" action="{{ route('admin.score.store') }}">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label for="pendaftaran_id" class="block text-sm font-medium text-gray-700">Peserta & Kursus</label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('pendaftaran_id') border-red-300 @enderror" id="pendaftaran_id" name="pendaftaran_id" required>
                                <option value="">-- Pilih Peserta --</option>
                                @foreach($pendaftarans as $p)
                                    <option value="{{ $p->id }}">
                                        {{ $p->peserta->user->name }} - {{ $p->kursus->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pendaftaran_id')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="listening" class="block text-sm font-medium text-gray-700">Listening</label>
                                <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('listening') border-red-300 @enderror" id="listening" name="listening" min="0" max="100" required value="{{ old('listening') }}">
                                @error('listening')
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="speaking" class="block text-sm font-medium text-gray-700">Speaking</label>
                                <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('speaking') border-red-300 @enderror" id="speaking" name="speaking" min="0" max="100" required value="{{ old('speaking') }}">
                                @error('speaking')
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="reading" class="block text-sm font-medium text-gray-700">Reading</label>
                                <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('reading') border-red-300 @enderror" id="reading" name="reading" min="0" max="100" required value="{{ old('reading') }}">
                                @error('reading')
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="writing" class="block text-sm font-medium text-gray-700">Writing</label>
                                <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('writing') border-red-300 @enderror" id="writing" name="writing" min="0" max="100" required value="{{ old('writing') }}">
                                @error('writing')
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="assignment" class="block text-sm font-medium text-gray-700">Assignment</label>
                            <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('assignment') border-red-300 @enderror" id="assignment" name="assignment" min="0" max="100" required value="{{ old('assignment') }}">
                            @error('assignment')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Field Tambahan (Legacy)</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="uktp" class="block text-sm font-medium text-gray-700">UKTP</label>
                                    <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('uktp') border-red-300 @enderror" id="uktp" name="uktp" min="0" max="100" value="{{ old('uktp') }}">
                                    @error('uktp')
                                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="ukap" class="block text-sm font-medium text-gray-700">UKAP</label>
                                    <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('ukap') border-red-300 @enderror" id="ukap" name="ukap" min="0" max="100" value="{{ old('ukap') }}">
                                    @error('ukap')
                                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                                <div>
                                    <label for="var1" class="block text-sm font-medium text-gray-700">Var 1</label>
                                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('var1') border-red-300 @enderror" id="var1" name="var1" value="{{ old('var1') }}">
                                    @error('var1')
                                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="var2" class="block text-sm font-medium text-gray-700">Var 2</label>
                                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('var2') border-red-300 @enderror" id="var2" name="var2" value="{{ old('var2') }}">
                                    @error('var2')
                                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="var3" class="block text-sm font-medium text-gray-700">Var 3</label>
                                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('var3') border-red-300 @enderror" id="var3" name="var3" value="{{ old('var3') }}">
                                    @error('var3')
                                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="var4" class="block text-sm font-medium text-gray-700">Var 4</label>
                                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('var4') border-red-300 @enderror" id="var4" name="var4" value="{{ old('var4') }}">
                                    @error('var4')
                                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Evaluasi</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="final_score" class="block text-sm font-medium text-gray-700">Nilai Akhir</label>
                                    <input type="number" step="0.1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('final_score') border-red-300 @enderror" id="final_score" name="final_score" min="0" max="100" required value="{{ old('final_score') }}">
                                    @error('final_score')
                                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('status') border-red-300 @enderror" id="status" name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="pass" {{ old('status') == 'pass' ? 'selected' : '' }}>Lulus</option>
                                        <option value="fail" {{ old('status') == 'fail' ? 'selected' : '' }}>Gagal</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    </select>
                                    @error('status')
                                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="evaluated_by" class="block text-sm font-medium text-gray-700">Dievaluasi oleh</label>
                                <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('evaluated_by') border-red-300 @enderror" id="evaluated_by" name="evaluated_by" required>
                                    <option value="">-- Pilih Instruktur --</option>
                                    @foreach($instrukturs as $i)
                                        <option value="{{ $i->id }}" {{ old('evaluated_by') == $i->id ? 'selected' : '' }}>
                                            {{ $i->nama_instr }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('evaluated_by')
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-6">
                                <label for="evaluated_at" class="block text-sm font-medium text-gray-700">Tanggal Evaluasi</label>
                                <input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('evaluated_at') border-red-300 @enderror" id="evaluated_at" name="evaluated_at" required value="{{ old('evaluated_at', now()->format('Y-m-d')) }}">
                                @error('evaluated_at')
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-6">
                                <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                                <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('keterangan') border-red-300 @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                <i class="bi bi-check-circle me-2"></i>Simpan
                            </button>
                            <a href="{{ route('admin.score.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
