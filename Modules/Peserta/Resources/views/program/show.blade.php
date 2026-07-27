@extends('peserta::layouts.student')

@section('title', $program->nama . ' - Balai Kursus')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-8">
        <a href="{{ route('peserta.program.index') }}" class="inline-flex items-center text-sm text-yellow-300 hover:text-yellow-200">
            <i class="bi bi-arrow-left mr-2"></i>Kembali ke daftar program
        </a>

        <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-2xl">
            <div class="p-8" style="background: linear-gradient(135deg, {{ $program->warna ?? '#dc2626' }}, #111827);">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Program</p>
                        <h1 class="mt-2 text-4xl font-bold text-white">{{ $program->nama }}</h1>
                        <p class="mt-3 max-w-3xl text-white/80">Alur pendaftaran program ini: daftar program, ikut tes penempatan di luar website, admin input hasil tes, lalu sistem menempatkan Anda ke level dan kelas yang kuotanya masih tersedia.</p>
                    </div>
                    @if($registration)
                        <div class="rounded-2xl border border-white/20 bg-black/20 p-5 text-white">
                            <p class="text-xs uppercase tracking-wider text-white/70">Status Anda</p>
                            <p class="mt-2 text-2xl font-bold">{{ str_replace('_', ' ', ucfirst($registration->status_pendaftaran)) }}</p>
                            <p class="mt-2 text-sm text-white/80">
                                @if($registration->level)
                                    Level: {{ $registration->level->nama }}
                                @else
                                    Menunggu hasil tes penempatan
                                @endif
                                @if($registration->kursus)
                                    <br>Kelas: {{ $registration->kursus->nama }}
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid gap-8 p-8 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="space-y-5">
                    <h2 class="text-lg font-semibold uppercase tracking-[0.2em] text-yellow-300">Struktur Level dan Kelas</h2>
                    @forelse($program->kursuses->groupBy(fn ($kursus) => $kursus->level?->nama ?? 'Tanpa Level') as $levelName => $kelasList)
                        <div class="rounded-2xl border border-white/10 bg-black/20 p-5">
                            <div class="flex flex-col gap-2 border-b border-white/10 pb-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-white">{{ $levelName }}</h3>
                                    @php $firstClass = $kelasList->first(); @endphp
                                    @if($firstClass?->level?->rentang_nilai)
                                        <p class="text-sm text-gray-400">Rentang rekomendasi skor: {{ $firstClass->level->rentang_nilai }}</p>
                                    @endif
                                </div>
                                <span class="text-sm text-gray-300">{{ $kelasList->count() }} kelas</span>
                            </div>

                            <div class="mt-4 space-y-3">
                                @foreach($kelasList as $kelas)
                                    @php
                                        $sisaKuota = max(0, $kelas->kuota - $kelas->pendaftarans_count);
                                    @endphp
                                    <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-4">
                                        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                                            <div>
                                                <p class="font-semibold text-white">{{ $kelas->nama }}</p>
                                                <p class="text-sm text-gray-400">
                                                    Periode {{ $kelas->periode ?: 'belum diatur' }}
                                                    @if($kelas->tanggal_mulai)
                                                        | Mulai {{ $kelas->tanggal_mulai->format('d M Y') }}
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="text-sm text-gray-300">
                                                Kuota tersisa {{ $sisaKuota }} / {{ $kelas->kuota }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-white/10 px-5 py-8 text-gray-400">
                            Belum ada kelas yang dikaitkan dengan program ini.
                        </div>
                    @endforelse
                </div>

                <div class="space-y-5">
                    <div class="rounded-2xl border border-white/10 bg-black/20 p-6">
                        <h2 class="text-lg font-semibold text-white">Yang Akan Anda Dapatkan</h2>
                        <div class="mt-4 space-y-3 text-sm text-gray-300">
                            <p>1. Pendaftaran program tanpa pilih kelas dulu.</p>
                            <p>2. Hasil tes diproses admin ke level yang cocok.</p>
                            <p>3. Sistem menempatkan Anda ke kelas dengan level sama dan kuota masih tersedia.</p>
                            <p>4. Pembayaran baru dibuka setelah kelas berhasil ditentukan.</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-black/20 p-6">
                        <h2 class="text-lg font-semibold text-white">Aksi</h2>
                        <div class="mt-5 space-y-3">
                            @if(!$registration)
                                <form action="{{ route('peserta.program.daftar', $program) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-sky-600 to-sky-700 px-5 py-3 font-semibold text-white hover:from-sky-500 hover:to-sky-600 transition">
                                        <i class="bi bi-check2-circle mr-2"></i>Daftar Program Ini
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('peserta.pendaftaran.index') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-yellow-400 px-5 py-3 font-semibold text-gray-900 hover:bg-yellow-300 transition">
                                    <i class="bi bi-clipboard-check mr-2"></i>Lihat Status Pendaftaran
                                </a>
                            @endif
                            <a href="{{ route('peserta.kursus.saya') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-white/10 px-5 py-3 font-semibold text-white hover:bg-white/20 transition">
                                <i class="bi bi-door-open mr-2"></i>Lihat Kelas Saya
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
