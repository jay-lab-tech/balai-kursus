@extends('layouts.admin')

@section('title', 'Tambah Instruktur')

@section('page-title', 'Tambah Instruktur')

@section('page-description', 'Buat akun instruktur baru lengkap dengan kredensial login dan profil spesialisasi.')

@section('content')
<div class="space-y-8 max-w-4xl">
    <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
        <div class="inline-flex items-center gap-2 rounded-full border border-sky-500/30 bg-sky-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
            <i class="bi bi-person-plus-fill text-sky-400"></i>
            Instruktur Baru
        </div>
        <h1 class="mt-5 text-3xl font-bold text-white">Tambahkan instruktur baru ke dalam sistem.</h1>
        <p class="mt-3 max-w-3xl text-base leading-7 text-slate-300">Lengkapi data akun login dan profil pengajar agar instruktur siap digunakan dalam penugasan kelas dan pencatatan aktivitas belajar.</p>
    </section>

    @if($errors->any())
        <div class="rounded-[1.5rem] border border-sky-500/20 bg-sky-600/10 px-5 py-4 text-sky-100 shadow-lg">
            <div class="flex items-start gap-3">
                <i class="bi bi-exclamation-octagon-fill mt-0.5 text-lg text-sky-300"></i>
                <div>
                    <p class="font-semibold">Form belum bisa disimpan</p>
                    <ul class="mt-2 space-y-1 text-sm text-sky-100/90">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.instruktur.store') }}" class="space-y-6">
        @csrf

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Akun Login</h2>
            <div class="mt-6 space-y-6">
                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-300">Nama User</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-300">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-slate-300">Password</label>
                    <input type="password" id="password" name="password" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
            </div>
        </section>

        <section class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <h2 class="text-xl font-bold text-white">Profil Instruktur</h2>
            <div class="mt-6 space-y-6">
                <div>
                    <label for="nama_instr" class="mb-2 block text-sm font-medium text-slate-300">Nama Instruktur</label>
                    <input type="text" id="nama_instr" name="nama_instr" value="{{ old('nama_instr') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
                <div>
                    <label for="spesialisasi" class="mb-2 block text-sm font-medium text-slate-300">Spesialisasi</label>
                    <input type="text" id="spesialisasi" name="spesialisasi" value="{{ old('spesialisasi') }}" class="w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-white focus:border-yellow-400/40 focus:outline-none focus:ring-2 focus:ring-yellow-400/10" required>
                </div>
            </div>
        </section>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-600 to-sky-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-sky-500 hover:to-sky-600">
                <i class="bi bi-check-circle-fill"></i>
                Simpan Instruktur
            </button>
            <a href="{{ route('admin.instruktur.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection
