@extends('layouts.admin')

@section('title', 'Edit Instruktur')

@section('page-title', 'Edit Instruktur')

@section('page-description', 'Perbarui akun dan profil instruktur agar data pengajar tetap akurat dan siap digunakan.')

@section('content')
<div class="space-y-8 max-w-4xl">
    <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
            <i class="bi bi-pencil-square text-red-400"></i>
            Update Instruktur
        </div>
        <h1 class="mt-5 text-3xl font-bold text-white">Perbarui profil <span class="text-yellow-300">{{ $instruktur->nama_instr }}</span>.</h1>
        <p class="mt-3 max-w-3xl text-base leading-7 text-slate-300">Gunakan formulir ini untuk menyesuaikan data akun, email, dan spesialisasi instruktur yang sudah terdaftar.</p>
    </section>

    @if($errors->any())
        <div class="rounded-[1.5rem] border border-red-500/20 bg-red-600/10 px-5 py-4 text-red-100 shadow-lg">
            <div class="flex items-start gap-3">
                <i class="bi bi-exclamation-octagon-fill mt-0.5 text-lg text-red-300"></i>
                <div>
                    <p class="font-semibold">Perubahan belum bisa disimpan</p>
                    <ul class="mt-2 space-y-1 text-sm text-red-100/90">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.instruktur.update', $instruktur->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Akun Login</h2>
            <div class="mt-6 space-y-6">
                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-300">Nama User</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $instruktur->user->name) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-300">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $instruktur->user->email) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
            </div>
        </section>

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Profil Instruktur</h2>
            <div class="mt-6 space-y-6">
                <div>
                    <label for="nama_instr" class="mb-2 block text-sm font-medium text-slate-300">Nama Instruktur</label>
                    <input type="text" id="nama_instr" name="nama_instr" value="{{ old('nama_instr', $instruktur->nama_instr) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
                <div>
                    <label for="spesialisasi" class="mb-2 block text-sm font-medium text-slate-300">Spesialisasi</label>
                    <input type="text" id="spesialisasi" name="spesialisasi" value="{{ old('spesialisasi', $instruktur->spesialisasi) }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
            </div>
        </section>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-red-600 to-red-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-red-500 hover:to-red-600">
                <i class="bi bi-check-circle-fill"></i>
                Update Instruktur
            </button>
            <a href="{{ route('admin.instruktur.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection
