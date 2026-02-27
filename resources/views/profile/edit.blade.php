@extends('layouts.app-bootstrap')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit Profil Saya</h4>
                </div>
                <div class="card-body">
                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Profil berhasil diperbarui.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('patch')

                        {{-- Informasi Akun Utama --}}
                        <h5 class="mb-3 text-muted">Informasi Akun</h5>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Informasi Tambahan Peserta --}}
                        @if($user->role === 'peserta')
                            <hr class="my-4">
                            <h5 class="mb-3 text-muted">Data Peserta</h5>

                            <div class="mb-3">
                                <label for="nomor_peserta" class="form-label">Nomor Peserta</label>
                                <input type="text" class="form-control bg-light" id="nomor_peserta" value="{{ $peserta->nomor_peserta ?? '-' }}" readonly disabled>
                                <small class="text-muted">Nomor peserta digenerate otomatis oleh sistem.</small>
                            </div>

                            <div class="mb-3">
                                <label for="no_hp" class="form-label">Nomor HP / WhatsApp</label>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp', $peserta->no_hp ?? '') }}" placeholder="Contoh: 08123456789">
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="instansi" class="form-label">Asal Instansi / Sekolah</label>
                                <input type="text" class="form-control @error('instansi') is-invalid @enderror" id="instansi" name="instansi" value="{{ old('instansi', $peserta->instansi ?? '') }}" placeholder="Contoh: Universitas Pendidikan Indonesia">
                                @error('instansi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection