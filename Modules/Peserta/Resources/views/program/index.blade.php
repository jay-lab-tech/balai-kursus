@extends('peserta::layouts.student')

@section('title', 'Program Tersedia - Balai Kursus')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="text-4xl font-bold text-white">
                    <i class="bi bi-diagram-3 text-yellow-400 mr-3"></i>Daftar Program
                </h1>
                <p class="mt-2 text-gray-400">Peserta mendaftar ke program terlebih dahulu, lalu sistem akan menempatkan ke level dan kelas setelah hasil tes penempatan diinput admin.</p>
            </div>
            <a href="{{ route('peserta.pendaftaran.index') }}" class="inline-flex items-center px-5 py-3 rounded-xl bg-white/10 border border-yellow-400/30 text-white hover:bg-white/20 transition">
                <i class="bi bi-clipboard-check mr-2"></i>Lihat Pendaftaran Saya
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-green-500/30 bg-green-500/10 px-5 py-4 text-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl border border-yellow-500/30 bg-yellow-500/10 px-5 py-4 text-yellow-100">
                {{ session('error') }}
            </div>
        @endif

        @if($programs->isEmpty())
            <div class="rounded-3xl border border-white/10 bg-white/5 px-8 py-16 text-center text-gray-300">
                Belum ada program yang tersedia.
            </div>
        @else
            <div class="grid gap-6 lg:grid-cols-2">
                @foreach($programs as $program)
                    @php
                        $registration = $registrations->get($program->id);
                        $levels = $program->kursuses
                            ->filter(fn ($kursus) => $kursus->level)
                            ->groupBy(fn ($kursus) => $kursus->level->nama);
                    @endphp
                    <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-2xl backdrop-blur">
                        <div class="p-6" style="background: linear-gradient(135deg, {{ $program->warna ?? '#dc2626' }}, #111827);">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.25em] text-white/70">Program</p>
                                    <h2 class="mt-2 text-3xl font-bold text-white">{{ $program->nama }}</h2>
                                </div>
                                @if($registration)
                                    <span class="inline-flex items-center rounded-full border border-white/20 bg-black/20 px-4 py-2 text-sm font-semibold text-white">
                                        <i class="bi bi-check-circle mr-2"></i>{{ str_replace('_', ' ', ucfirst($registration->status_pendaftaran)) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-6 p-6 text-gray-200">
                            <div class="grid gap-4 sm:grid-cols-3">
                                <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                                    <p class="text-xs uppercase tracking-wider text-gray-400">Total Level</p>
                                    <p class="mt-2 text-2xl font-bold text-white">{{ $levels->count() }}</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                                    <p class="text-xs uppercase tracking-wider text-gray-400">Total Kelas</p>
                                    <p class="mt-2 text-2xl font-bold text-white">{{ $program->kursuses->count() }}</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                                    <p class="text-xs uppercase tracking-wider text-gray-400">Kelas Tersedia</p>
                                    <p class="mt-2 text-2xl font-bold text-white">
                                        {{ $program->kursuses->filter(fn ($kursus) => $kursus->pendaftarans_count < $kursus->kuota && in_array($kursus->status, ['buka', 'berjalan'], true))->count() }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-yellow-300">Level dan Kelas</h3>
                                @forelse($levels as $levelName => $kelas)
                                    <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <p class="font-semibold text-white">{{ $levelName }}</p>
                                                <p class="text-sm text-gray-400">{{ $kelas->count() }} kelas tersedia pada level ini</p>
                                            </div>
                                            <div class="text-sm text-gray-300">
                                                @php
                                                    $availableCount = $kelas->filter(fn ($kursus) => $kursus->pendaftarans_count < $kursus->kuota && in_array($kursus->status, ['buka', 'berjalan'], true))->count();
                                                @endphp
                                                {{ $availableCount }} kelas masih bisa diisi
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="rounded-2xl border border-dashed border-white/10 px-4 py-6 text-sm text-gray-400">
                                        Level dan kelas belum dikonfigurasi untuk program ini.
                                    </div>
                                @endforelse
                            </div>

                            @if($registration)
                                <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-4 text-sm text-emerald-100">
                                    Program ini sudah Anda daftar.
                                    @if($registration->level)
                                        Hasil sementara: level <strong>{{ $registration->level->nama }}</strong>
                                        @if($registration->kursus)
                                            , kelas <strong>{{ $registration->kursus->nama }}</strong>.
                                        @endif
                                    @else
                                        Pendaftaran sedang menunggu proses tes penempatan.
                                    @endif
                                </div>
                            @endif

                            <div class="flex flex-col gap-3 sm:flex-row">
                                <a href="{{ route('peserta.program.show', $program) }}" class="inline-flex flex-1 items-center justify-center rounded-xl bg-white/10 px-5 py-3 font-semibold text-white hover:bg-white/20 transition">
                                    <i class="bi bi-eye mr-2"></i>Lihat Detail
                                </a>
                                @if(!$registration)
                                    <form action="{{ route('peserta.program.daftar', $program) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-red-600 to-red-700 px-5 py-3 font-semibold text-white hover:from-red-500 hover:to-red-600 transition">
                                            <i class="bi bi-check2-circle mr-2"></i>Daftar Program
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('peserta.pendaftaran.index') }}" class="inline-flex flex-1 items-center justify-center rounded-xl bg-yellow-400 px-5 py-3 font-semibold text-gray-900 hover:bg-yellow-300 transition">
                                        <i class="bi bi-arrow-right-circle mr-2"></i>Lihat Status
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
