@extends('layouts.admin')

@section('title', 'Edit Peserta')

@section('page-title', 'Edit Peserta')

@section('page-description', 'Perbarui identitas peserta, nomor peserta, dan informasi kontak yang tersimpan.')

@section('content')
<div class="space-y-8 max-w-4xl">
    <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-sky-500/30 bg-sky-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
            <i class="bi bi-pencil-square text-sky-400"></i>
            Update Peserta
        </div>
        <h1 class="mt-5 text-3xl font-bold text-white">Perbarui data peserta <span class="text-yellow-300">{{ $peserta->user->name ?? 'Peserta' }}</span>.</h1>
        <p class="mt-3 max-w-3xl text-base leading-7 text-slate-300">Gunakan formulir ini untuk menjaga identitas peserta tetap valid sebelum proses pendaftaran dan pembayaran berjalan.</p>
    </section>

    @if($errors->any())
        <div class="rounded-[1.5rem] border border-sky-500/20 bg-sky-600/10 px-5 py-4 text-sky-100 shadow-lg">
            <div class="flex items-start gap-3">
                <i class="bi bi-exclamation-octagon-fill mt-0.5 text-lg text-sky-300"></i>
                <div>
                    <p class="font-semibold">Perubahan belum bisa disimpan</p>
                    <ul class="mt-2 space-y-1 text-sm text-sky-100/90">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.peserta.update', $peserta->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Akun Peserta</h2>
            <div class="mt-6 space-y-6">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Nama</label>
                    <input type="text" name="nama" value="{{ old('nama', $peserta->user->name ?? '') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Email</label>
                    <input type="email" name="email" value="{{ old('email', $peserta->user->email ?? '') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
            </div>
        </section>

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Profil Peserta</h2>
            <div class="mt-6 space-y-6">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Nomor Peserta</label>
                    <input type="text" name="nomor_peserta" value="{{ old('nomor_peserta', $peserta->nomor_peserta) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">No HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $peserta->no_hp) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Instansi</label>
                    <input type="text" name="instansi" value="{{ old('instansi', $peserta->instansi) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10">
                </div>
            </div>
        </section>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-600 to-sky-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-sky-500 hover:to-sky-600">
                <i class="bi bi-check-circle-fill"></i>
                Update Peserta
            </button>
            <a href="{{ route('admin.peserta.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection
