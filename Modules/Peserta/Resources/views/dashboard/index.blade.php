@extends('peserta::layouts.student')

@section('title', 'Dashboard Peserta - Balai Kursus')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="text-4xl font-bold text-white">
                    <i class="bi bi-speedometer2 text-yellow-400 mr-3"></i>Dashboard Peserta
                </h1>
                <p class="mt-2 text-gray-400">Pantau status pendaftaran program, hasil tes penempatan, dan kelas yang sudah ditentukan.</p>
            </div>
            <a href="{{ route('peserta.program.index') }}" class="inline-flex items-center rounded-xl bg-gradient-to-r from-red-600 to-red-700 px-5 py-3 font-semibold text-white hover:from-red-500 hover:to-red-600 transition">
                <i class="bi bi-plus-circle mr-2"></i>Daftar Program Baru
            </a>
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl bg-gradient-to-br from-yellow-400 to-yellow-500 p-6 text-gray-900 shadow-xl">
                <p class="text-sm font-semibold uppercase tracking-wider opacity-80">Total Pendaftaran</p>
                <p class="mt-3 text-4xl font-bold">{{ $pendaftarans->count() }}</p>
            </div>
            <div class="rounded-3xl bg-gradient-to-br from-blue-500 to-blue-700 p-6 text-white shadow-xl">
                <p class="text-sm font-semibold uppercase tracking-wider opacity-80">Menunggu Tes</p>
                <p class="mt-3 text-4xl font-bold">{{ $pendaftarans->where('status_pendaftaran', \App\Models\Pendaftaran::STATUS_MENUNGGU_TES)->count() }}</p>
            </div>
            <div class="rounded-3xl bg-gradient-to-br from-emerald-500 to-emerald-700 p-6 text-white shadow-xl">
                <p class="text-sm font-semibold uppercase tracking-wider opacity-80">Sudah Dapat Kelas</p>
                <p class="mt-3 text-4xl font-bold">{{ $pendaftarans->whereNotNull('kursus_id')->count() }}</p>
            </div>
            <div class="rounded-3xl bg-gradient-to-br from-gray-900 to-black p-6 text-white shadow-xl border border-yellow-400/20">
                <p class="text-sm font-semibold uppercase tracking-wider text-yellow-300">Kelas Aktif</p>
                <p class="mt-3 text-4xl font-bold">{{ $pendaftarans->where('status_pendaftaran', \App\Models\Pendaftaran::STATUS_AKTIF)->count() }}</p>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-white">Status Pendaftaran Terbaru</h2>
                    <a href="{{ route('peserta.pendaftaran.index') }}" class="text-sm font-semibold text-yellow-300 hover:text-yellow-200">Lihat semua</a>
                </div>

                @if($pendaftarans->isEmpty())
                    <div class="mt-8 rounded-2xl border border-dashed border-white/10 px-6 py-12 text-center text-gray-400">
                        Belum ada pendaftaran program.
                    </div>
                @else
                    <div class="mt-6 space-y-4">
                        @foreach($pendaftarans->take(5) as $pendaftaran)
                            <div class="rounded-2xl border border-white/10 bg-black/20 p-5">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                    <div>
                                        <p class="text-xs uppercase tracking-wider text-gray-400">{{ $pendaftaran->nomor }}</p>
                                        <h3 class="mt-2 text-xl font-bold text-white">{{ $pendaftaran->program->nama ?? 'Program belum tersedia' }}</h3>
                                        <div class="mt-3 space-y-1 text-sm text-gray-300">
                                            <p><span class="text-gray-400">Status:</span> {{ str_replace('_', ' ', ucfirst($pendaftaran->status_pendaftaran)) }}</p>
                                            <p><span class="text-gray-400">Hasil tes:</span> {{ $pendaftaran->placementScore?->final_score ?? 'Belum diinput admin' }}</p>
                                            <p><span class="text-gray-400">Level:</span> {{ $pendaftaran->level->nama ?? 'Belum ditentukan' }}</p>
                                            <p><span class="text-gray-400">Kelas:</span> {{ $pendaftaran->kursus->nama ?? 'Belum ditempatkan' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-3 lg:items-end">
                                        <span class="rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-white">
                                            {{ strtoupper($pendaftaran->status_pembayaran) }}
                                        </span>
                                        @if($pendaftaran->canBePaid())
                                            <a href="{{ route('peserta.pendaftaran.index') }}" class="rounded-xl bg-gradient-to-r from-red-600 to-red-700 px-4 py-2 text-sm font-semibold text-white hover:from-red-500 hover:to-red-600 transition">
                                                Buka Halaman Bayar
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl">
                    <h2 class="text-xl font-bold text-white">Akses Cepat</h2>
                    <div class="mt-5 space-y-3">
                        <a href="{{ route('peserta.program.index') }}" class="flex items-center rounded-2xl bg-black/20 px-4 py-3 text-white hover:bg-black/30">
                            <i class="bi bi-diagram-3 mr-3 text-yellow-300"></i>Daftar Program
                        </a>
                        <a href="{{ route('peserta.pendaftaran.index') }}" class="flex items-center rounded-2xl bg-black/20 px-4 py-3 text-white hover:bg-black/30">
                            <i class="bi bi-clipboard-check mr-3 text-yellow-300"></i>Status Pendaftaran
                        </a>
                        <a href="{{ route('peserta.kursus.saya') }}" class="flex items-center rounded-2xl bg-black/20 px-4 py-3 text-white hover:bg-black/30">
                            <i class="bi bi-door-open mr-3 text-yellow-300"></i>Kelas Saya
                        </a>
                        <a href="{{ route('peserta.riwayat.index') }}" class="flex items-center rounded-2xl bg-black/20 px-4 py-3 text-white hover:bg-black/30">
                            <i class="bi bi-receipt mr-3 text-yellow-300"></i>Riwayat Pembayaran
                        </a>
                    </div>
                </div>

                <div class="rounded-3xl bg-gradient-to-br from-red-600 to-red-800 p-6 text-white shadow-2xl">
                    <h2 class="text-xl font-bold">Alur Baru Pendaftaran</h2>
                    <div class="mt-4 space-y-3 text-sm text-white/90">
                        <p>1. Pilih dan daftar program.</p>
                        <p>2. Ikuti tes penempatan di luar website.</p>
                        <p>3. Admin input hasil tes.</p>
                        <p>4. Sistem menempatkan Anda ke level dan kelas yang sesuai.</p>
                        <p>5. Pembayaran dibuka setelah kelas berhasil ditentukan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
