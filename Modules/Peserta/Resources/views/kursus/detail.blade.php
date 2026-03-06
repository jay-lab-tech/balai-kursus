@extends('peserta::layouts.student')

@section('title', 'Detail ' . $kursus->nama . ' - Balai Kursus')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Back Button -->
        <a href="/peserta/kursus/saya" class="inline-flex items-center px-4 py-2 mb-8 text-yellow-400 hover:text-yellow-300 transition-colors duration-200 group">
            <i class="bi bi-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
            <span>Kembali ke Kursus Saya</span>
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Course Header -->
                <div class="bg-gradient-to-r from-gray-50 to-white rounded-2xl p-8 mb-8 shadow-lg">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $kursus->nama }}</h1>
                    <p class="text-gray-600">Pertemuan dan Materi Pembelajaran</p>
                </div>

                <!-- Course Info Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="bi bi-info-circle text-yellow-400 mr-2"></i>
                            Informasi Kursus
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Program</p>
                                <p class="text-white font-semibold">{{ $kursus->program->nama }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Level</p>
                                <p class="text-white font-semibold">{{ $kursus->level->nama }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Instruktur</p>
                                <p class="text-white font-semibold">{{ $kursus->instruktur->nama_instr ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Periode</p>
                                <p class="text-white font-semibold">{{ $kursus->periode }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Meetings List -->
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-8">
                    <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <i class="bi bi-calendar3 text-red-500 mr-3"></i>
                        Daftar Pertemuan
                    </h3>

                    @if($risalahs && count($risalahs) > 0)
                        <div class="space-y-4">
                            @foreach($risalahs as $risalah)
                            <div class="group bg-gradient-to-r from-gray-700/30 to-transparent hover:from-gray-700/50 border border-gray-700 hover:border-red-500/50 rounded-lg p-6 transition-all duration-200 cursor-pointer" onclick="showRisalah({{ $risalah->id }})">
                                <div class="flex items-start justify-between">
                                    <div class="flex-grow">
                                        <h4 class="text-white font-semibold text-lg mb-2">Pertemuan {{ $risalah->pertemuan_ke }}</h4>
                                        <div class="flex items-center space-x-4 text-gray-400 text-sm">
                                            <span class="flex items-center">
                                                <i class="bi bi-calendar2 mr-2 text-yellow-400"></i>
                                                {{ $risalah->tgl_pertemuan ? \Carbon\Carbon::parse($risalah->tgl_pertemuan)->translatedFormat('d F Y') : 'Belum ditentukan' }}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="bi bi-book mr-2 text-blue-400"></i>
                                                {{ $risalah->materi ? 'Ada Materi' : 'Belum Ada Materi' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @if($risalah->materi)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                                                <i class="bi bi-check-circle mr-1"></i>Tersedia
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-500/20 text-gray-400 border border-gray-500/30">
                                                <i class="bi bi-clock mr-1"></i>Belum
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Document Download -->
                                @if($risalah->dokumen)
                                <div class="mt-4 pt-4 border-t border-gray-600">
                                    <a href="{{ route('instruktur.risalah.download', $risalah->id) }}" class="inline-flex items-center text-yellow-400 hover:text-yellow-300 transition-colors" target="_blank" onclick="event.stopPropagation();">
                                        <i class="bi bi-download mr-2"></i>
                                        <span>Download Dokumen</span>
                                    </a>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-400">
                            <i class="bi bi-inbox text-5xl mb-4 block opacity-30"></i>
                            <p class="text-lg">Belum ada pertemuan untuk kursus ini</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar - Payment Status -->
            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    <!-- Payment Status Card -->
                    <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-6 mb-6">
                        <h4 class="text-white font-semibold mb-6 flex items-center">
                            <i class="bi bi-credit-card text-yellow-400 mr-2"></i>
                            Status Pembayaran
                        </h4>

                        <!-- Status Badge -->
                        <div class="mb-6 pb-6 border-b border-gray-700">
                            @if($pendaftaran->status_pembayaran === 'selesai')
                                <span class="inline-flex items-center px-4 py-2 rounded-lg bg-green-500/20 text-green-400 border border-green-500/30 font-semibold">
                                    <i class="bi bi-check-circle mr-2"></i>
                                    Pembayaran Selesai
                                </span>
                            @elseif($pendaftaran->status_pembayaran === 'dp')
                                <span class="inline-flex items-center px-4 py-2 rounded-lg bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 font-semibold">
                                    <i class="bi bi-hourglass-split mr-2"></i>
                                    DP (Cicilan)
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-lg bg-red-500/20 text-red-400 border border-red-500/30 font-semibold">
                                    <i class="bi bi-x-circle mr-2"></i>
                                    {{ ucfirst($pendaftaran->status_pembayaran) }}
                                </span>
                            @endif
                        </div>

                        <!-- Payment Details -->
                        <div class="space-y-4 mb-6 pb-6 border-b border-gray-700">
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Total Biaya</p>
                                <p class="text-2xl font-bold text-yellow-400">Rp {{ number_format($pendaftaran->total_bayar, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Sudah Terbayar</p>
                                <p class="text-xl font-semibold text-white">Rp {{ number_format($pendaftaran->terbayar, 0, ',', '.') }}</p>
                            </div>
                            @php
                                $progress = $pendaftaran->total_bayar > 0 ? ($pendaftaran->terbayar / $pendaftaran->total_bayar) * 100 : 0;
                            @endphp
                            <div>
                                <p class="text-gray-400 text-sm mb-2">Progress Pembayaran</p>
                                <div class="w-full bg-gray-700 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-yellow-500 to-red-500 h-full transition-all duration-500" style="width: {{ min($progress, 100) }}%"></div>
                                </div>
                                <p class="text-sm text-gray-400 mt-2">{{ number_format($progress, 0) }}% selesai</p>
                            </div>
                        </div>

                        <!-- Remaining Amount -->
                        @php
                            $sisa = $pendaftaran->total_bayar - $pendaftaran->terbayar;
                        @endphp
                        @if($sisa > 0)
                        <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4">
                            <p class="text-gray-400 text-sm mb-1">Sisa Pembayaran</p>
                            <p class="text-lg font-bold text-red-400">Rp {{ number_format($sisa, 0, ',', '.') }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Attendance Info -->
                    @if($risalahs && count($risalahs) > 0)
                    <div class="bg-gradient-to-br from-blue-900/20 to-indigo-900/20 border border-blue-500/30 rounded-xl p-6">
                        <h4 class="text-white font-semibold mb-4 flex items-center">
                            <i class="bi bi-people-fill text-blue-400 mr-2"></i>
                            Kehadiran
                        </h4>
                        <div class="text-center">
                            <p class="text-4xl font-bold text-blue-400">{{ $risalahs->sum(fn($r) => $r->absensis()->count()) ?? 0 }}</p>
                            <p class="text-gray-400 text-sm">pertemuan dihadiri</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CONTAINER -->
@if($risalahs && count($risalahs) > 0)
@foreach($risalahs as $risalah)
<div id="risalahModal{{ $risalah->id }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" onclick="if(event.target === this) closeRisalah({{ $risalah->id }})">
    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl max-w-2xl w-full border border-gray-700 animate-fade-in-up">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-700">
            <h3 class="text-2xl font-bold text-white">Risalah Pertemuan {{ $risalah->pertemuan_ke }}</h3>
            <button onclick="closeRisalah({{ $risalah->id }})" class="text-gray-400 hover:text-white text-2xl transition-colors">
                <i class="bi bi-x"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 max-h-96 overflow-y-auto">
            <!-- Tanggal -->
            <div class="mb-6 pb-6 border-b border-gray-700">
                <p class="text-yellow-400 text-sm font-semibold mb-2">Tanggal Pertemuan</p>
                <p class="text-white text-lg">
                    {{ $risalah->tgl_pertemuan ? \Carbon\Carbon::parse($risalah->tgl_pertemuan)->translatedFormat('d F Y') : 'Belum ditentukan' }}
                </p>
            </div>

            <!-- Materi -->
            <div class="mb-6 pb-6 border-b border-gray-700">
                <p class="text-blue-400 text-sm font-semibold mb-2">Materi Pembelajaran</p>
                @if($risalah->materi)
                    <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4 text-gray-300 text-sm">
                        {{ $risalah->materi }}
                    </div>
                @else
                    <p class="text-gray-400 italic">Materi belum ditambahkan oleh instruktur</p>
                @endif
            </div>

            <!-- Catatan -->
            <div class="mb-6 pb-6 border-b border-gray-700">
                <p class="text-green-400 text-sm font-semibold mb-2">Catatan Tambahan</p>
                @if($risalah->catatan)
                    <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-4 text-gray-300 text-sm">
                        {{ $risalah->catatan }}
                    </div>
                @else
                    <p class="text-gray-400 italic">Tidak ada catatan tambahan</p>
                @endif
            </div>

            <!-- Peserta Hadir -->
            <div>
                <p class="text-indigo-400 text-sm font-semibold mb-2">Peserta Hadir</p>
                <div class="inline-flex items-center px-4 py-2 bg-indigo-500/20 text-indigo-400 rounded-lg border border-indigo-500/30">
                    <i class="bi bi-people-fill mr-2"></i>
                    <span class="font-semibold">{{ $risalah->absensis()->count() ?? 0 }} peserta</span>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-between p-6 border-t border-gray-700 bg-gray-900/50">
            @if($risalah->dokumen)
                <a href="{{ route('instruktur.risalah.download', $risalah->id) }}" class="flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-lg transform hover:scale-105 transition-all duration-200" target="_blank">
                    <i class="bi bi-download mr-2"></i>Download Dokumen
                </a>
            @else
                <div></div>
            @endif
            <button onclick="closeRisalah({{ $risalah->id }})" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors duration-200">
                Tutup
            </button>
        </div>
    </div>
</div>
@endforeach
@endif

<script>
function showRisalah(id) {
    const modal = document.getElementById('risalahModal' + id);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeRisalah(id) {
    const modal = document.getElementById('risalahModal' + id);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Close modal on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id^="risalahModal"]').forEach(modal => {
            modal.classList.add('hidden');
        });
        document.body.style.overflow = 'auto';
    }
});
</script>

<style>
    [id^="risalahModal"] {
        display: flex !important;
    }
</style>
@endsection
