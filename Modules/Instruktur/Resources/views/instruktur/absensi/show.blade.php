@extends('instruktur::layouts.master')

@section('title', $kursus->nama . ' - Balai Kursus')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <a href="/instruktur/kursus" class="inline-flex items-center px-4 py-2 mb-8 text-yellow-400 hover:text-yellow-300 transition-colors duration-200 group">
            <i class="bi bi-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
            <span>Kembali ke Daftar Kursus</span>
        </a>

        <!-- Header Kursus -->
        <div class="rounded-2xl overflow-hidden shadow-2xl mb-8">
            <div class="h-64 bg-gradient-to-r from-sky-600 to-sky-700 flex items-center justify-center">
                <i class="bi bi-book text-white" style="font-size: 80px; opacity: 0.3;"></i>
            </div>
            <div class="bg-gradient-to-r from-gray-50 to-white p-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $kursus->nama }}</h1>
                <p class="text-gray-600">{{ $kursus->program->nama ?? '-' }} â€¢ {{ $kursus->level->nama ?? '-' }}</p>
            </div>
        </div>

        <!-- Info Program & Instruktur -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-6 hover:border-yellow-500/50 transition-colors duration-200">
                <div class="flex items-start justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Informasi Program</h3>
                    <i class="bi bi-diagram-3 text-yellow-400 text-2xl"></i>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Program</p>
                        <p class="text-white font-semibold">{{ $kursus->program->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Level</p>
                        <p class="text-white font-semibold">{{ $kursus->level->nama ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-6 hover:border-sky-500/50 transition-colors duration-200">
                <div class="flex items-start justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Detail Pelaksanaan</h3>
                    <i class="bi bi-person-circle text-sky-500 text-2xl"></i>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Instruktur</p>
                        <p class="text-white font-semibold">{{ $kursus->instruktur->nama_instr ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Total Peserta</p>
                        <span class="text-white font-semibold">{{ $kursus->pendaftarans()->count() }} peserta</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Pertemuan & Absensi Full Width -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-8 mb-8">
            <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                <i class="bi bi-calendar3 text-sky-500 mr-3"></i>
                Daftar Pertemuan & Absensi
            </h3>
            @forelse($risalah as $r)
                <div class="mb-8 border border-gray-700 rounded-xl bg-gradient-to-r from-gray-700/30 to-transparent hover:border-yellow-500/50 transition-colors duration-200 group w-full">
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-6">
                        <div>
                            <h4 class="text-xl font-bold text-white mb-1 flex items-center">
                                <i class="bi bi-calendar text-yellow-400 mr-2"></i>Pertemuan {{ $r->pertemuan_ke }}
                            </h4>
                            <div class="text-gray-400 mb-2 flex items-center text-base">
                                <i class="bi bi-calendar2 mr-2"></i>{{ $r->tgl_pertemuan ? \Carbon\Carbon::parse($r->tgl_pertemuan)->format('d/m/Y') : '-' }}
                            </div>
                            <div class="text-gray-400 flex items-center text-base mb-4">
                                <i class="bi bi-file-text mr-2"></i>{{ Str::limit($r->materi ?? '-', 40) }}
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-4 md:mt-0">
                            <a href="/instruktur/risalah/{{ $r->id }}/edit" class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-semibold transition-colors">
                                <i class="bi bi-file-earmark mr-1"></i>Edit Risalah
                            </a>
                            <a href="/instruktur/risalah/{{ $r->id }}/absensi" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 text-white font-semibold rounded-lg transition-colors">
                                <i class="bi bi-clipboard-check mr-1"></i>Isi Absensi
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto px-6 pb-6">
                        <table class="min-w-full divide-y divide-gray-700 bg-transparent">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Peserta</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Jam Datang</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                @forelse($r->absensis as $a)
                                <tr>
                                    <td class="px-4 py-2 text-white">{{ $a->pendaftaran->peserta->user->name }}</td>
                                    <td class="px-4 py-2">
                                        @php
                                            $status = strtoupper($a->status);
                                            $badgeClass = match($status) {
                                                'HADIR' => 'bg-green-600 text-white',
                                                'ABSEN' => 'bg-sky-600 text-white',
                                                'IZIN' => 'bg-yellow-400 text-gray-900',
                                                default => 'bg-gray-600 text-white'
                                            };
                                        @endphp
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">{{ $status }}</span>
                                    </td>
                                    <td class="px-4 py-2 text-gray-300">{{ $a->jam_datang ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-gray-400 py-3">
                                        <i class="bi bi-inbox"></i> Belum ada absensi
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400">
                    <i class="bi bi-inbox text-4xl mb-3 block opacity-50"></i>
                    <p>Belum ada pertemuan. Hubungi admin untuk menambahkan pertemuan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
