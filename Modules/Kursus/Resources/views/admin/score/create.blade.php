@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Nilai Peserta</h2>
    </div>

    <div class="row">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.score.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="pendaftaran_id" class="form-label fw-500">Peserta & Kursus</label>
                            <select class="form-select @error('pendaftaran_id') is-invalid @enderror" id="pendaftaran_id" name="pendaftaran_id" required>
                                <option value="">-- Pilih Peserta --</option>
                                @foreach($pendaftarans as $p)
                                    <option value="{{ $p->id }}">
                                        {{ $p->peserta->user->name }} - {{ $p->kursus->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pendaftaran_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="listening" class="form-label fw-500">Listening</label>
                                    <input type="number" class="form-control @error('listening') is-invalid @enderror" id="listening" name="listening" min="0" max="100" required value="{{ old('listening') }}">
                                    @error('listening')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="speaking" class="form-label fw-500">Speaking</label>
                                    <input type="number" class="form-control @error('speaking') is-invalid @enderror" id="speaking" name="speaking" min="0" max="100" required value="{{ old('speaking') }}">
                                    @error('speaking')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reading" class="form-label fw-500">Reading</label>
                                    <input type="number" class="form-control @error('reading') is-invalid @enderror" id="reading" name="reading" min="0" max="100" required value="{{ old('reading') }}">
                                    @error('reading')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="writing" class="form-label fw-500">Writing</label>
                                    <input type="number" class="form-control @error('writing') is-invalid @enderror" id="writing" name="writing" min="0" max="100" required value="{{ old('writing') }}">
                                    @error('writing')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="assignment" class="form-label fw-500">Assignment</label>
                            <input type="number" class="form-control @error('assignment') is-invalid @enderror" id="assignment" name="assignment" min="0" max="100" required value="{{ old('assignment') }}">
                            @error('assignment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h5 class="fw-bold mt-4 mb-3">Field Tambahan (Legacy)</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="uktp" class="form-label fw-500">UKTP</label>
                                    <input type="number" class="form-control @error('uktp') is-invalid @enderror" id="uktp" name="uktp" min="0" max="100" value="{{ old('uktp') }}">
                                    @error('uktp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ukap" class="form-label fw-500">UKAP</label>
                                    <input type="number" class="form-control @error('ukap') is-invalid @enderror" id="ukap" name="ukap" min="0" max="100" value="{{ old('ukap') }}">
                                    @error('ukap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="var1" class="form-label fw-500">Var 1</label>
                                    <input type="text" class="form-control @error('var1') is-invalid @enderror" id="var1" name="var1" value="{{ old('var1') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="var2" class="form-label fw-500">Var 2</label>
                                    <input type="text" class="form-control @error('var2') is-invalid @enderror" id="var2" name="var2" value="{{ old('var2') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="var3" class="form-label fw-500">Var 3</label>
                                    <input type="text" class="form-control @error('var3') is-invalid @enderror" id="var3" name="var3" value="{{ old('var3') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="var4" class="form-label fw-500">Var 4</label>
                                    <input type="text" class="form-control @error('var4') is-invalid @enderror" id="var4" name="var4" value="{{ old('var4') }}">
                                </div>
                            </div>
                        </div>

                        <h5 class="fw-bold mt-4 mb-3">Evaluasi</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="final_score" class="form-label fw-500">Nilai Akhir</label>
                                    <input type="number" step="0.1" class="form-control @error('final_score') is-invalid @enderror" id="final_score" name="final_score" min="0" max="100" required value="{{ old('final_score') }}">
                                    @error('final_score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label fw-500">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="pass" {{ old('status') == 'pass' ? 'selected' : '' }}>Lulus</option>
                                        <option value="fail" {{ old('status') == 'fail' ? 'selected' : '' }}>Gagal</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="evaluated_by" class="form-label fw-500">Dievaluasi oleh</label>
                            <select class="form-select @error('evaluated_by') is-invalid @enderror" id="evaluated_by" name="evaluated_by" required>
                                <option value="">-- Pilih Instruktur --</option>
                                @foreach($instrukturs as $i)
                                    <option value="{{ $i->id }}" {{ old('evaluated_by') == $i->id ? 'selected' : '' }}>
                                        {{ $i->nama_instr }}
                                    </option>
                                @endforeach
                            </select>
                            @error('evaluated_by')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="evaluated_at" class="form-label fw-500">Tanggal Evaluasi</label>
                            <input type="date" class="form-control @error('evaluated_at') is-invalid @enderror" id="evaluated_at" name="evaluated_at" required value="{{ old('evaluated_at', now()->format('Y-m-d')) }}">
                            @error('evaluated_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label fw-500">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle me-2"></i>Simpan</button>
                            <a href="{{ route('admin.score.index') }}" class="btn btn-secondary btn-lg"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
