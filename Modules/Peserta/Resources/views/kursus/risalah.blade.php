@extends('peserta::layouts.student')

@section('title', 'Risalah ' . $kursus->nama . ' - Balai Kursus')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-12">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">
                    <i class="bi bi-file-earmark text-yellow-400 mr-3"></i>{{ $kursus->nama }}
                </h1>
                <p class="text-gray-400">Daftar Risalah Pertemuan</p>
            </div>
            <a href="/peserta/kursus" class="inline-flex items-center px-4 py-2 text-yellow-400 hover:text-yellow-300 transition-colors">
                <i class="bi bi-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>

        <!-- Search Box -->
        <div class="mb-8">
            <form method="GET" action="" class="flex gap-3">
                <div class="flex-grow relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari materi, catatan..." 
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-yellow-400 transition-colors duration-200"
                    />
                    <i class="bi bi-search absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-sky-600 to-sky-700 hover:from-sky-700 hover:to-sky-800 text-white font-semibold rounded-lg transform hover:scale-105 transition-all duration-200">
                    <i class="bi bi-search mr-2"></i>Cari
                </button>
            </form>
        </div>

        @if($risalahs && count($risalahs) > 0)
            <!-- Meetings List -->
            <div class="space-y-4">
                @foreach($risalahs as $r)
                <div class="group bg-gradient-to-r from-gray-800 to-gray-900 border border-gray-700 hover:border-yellow-500/50 rounded-xl p-6 transition-all duration-200 cursor-pointer" onclick="showRisalah({{ $r->id }})">
                    <div class="flex items-start justify-between">
                        <div class="flex-grow">
                            <!-- Meeting Header -->
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-xl font-bold text-white">Pertemuan {{ $r->pertemuan_ke }}</h3>
                                @if($r->materi)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-500/20 text-green-400 border border-green-500/30">
                                        <i class="bi bi-check-circle mr-1"></i>Ada Materi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-500/20 text-gray-400 border border-gray-500/30">
                                        <i class="bi bi-clock mr-1"></i>Belum Ada
                                    </span>
                                @endif
                            </div>

                            <!-- Meeting Details -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-400">
                                <div class="flex items-center">
                                    <i class="bi bi-calendar2 text-yellow-400 mr-2 flex-shrink-0"></i>
                                    <span>{{ $r->tgl_pertemuan ? \Carbon\Carbon::parse($r->tgl_pertemuan)->translatedFormat('d F Y') : 'Belum ditentukan' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="bi bi-book text-blue-400 mr-2 flex-shrink-0"></i>
                                    <span class="line-clamp-1">{{ $r->materi ? Str::limit($r->materi, 50, '...') : 'Belum ada materi' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="bi bi-people-fill text-sky-400 mr-2 flex-shrink-0"></i>
                                    <span>{{ $r->absensis()->count() ?? 0 }} peserta hadir</span>
                                </div>
                            </div>

                            <!-- Catatan Preview -->
                            @if($r->catatan)
                            <div class="mt-3 pt-3 border-t border-gray-700">
                                <p class="text-xs text-gray-500 mb-1 font-semibold">Catatan</p>
                                <p class="text-gray-300 text-sm line-clamp-1">{{ $r->catatan }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex-shrink-0 ml-4">
                            <button onclick="event.stopPropagation(); showRisalah({{ $r->id }})" class="p-2 text-gray-400 hover:text-yellow-400 transition-colors group-hover:scale-110">
                                <i class="bi bi-eye text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Download Link -->
                    @if($r->dokumen)
                    <div class="mt-4 pt-4 border-t border-gray-700">
                        <a href="{{ route('instruktur.risalah.download', $r->id) }}" class="inline-flex items-center text-yellow-400 hover:text-yellow-300 transition-colors text-sm font-semibold" onclick="event.stopPropagation();" target="_blank">
                            <i class="bi bi-download mr-2"></i>
                            Download Dokumen
                        </a>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center h-24 w-24 bg-gray-700/50 rounded-full mb-6">
                    <i class="bi bi-inbox text-4xl text-gray-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Belum Ada Risalah</h2>
                <p class="text-gray-400">Instruktur akan menambahkan risalah pertemuan setelah setiap sesi pembelajaran.</p>
            </div>
        @endif
    </div>
</div>

<!-- MODAL CONTAINER -->
@if($risalahs && count($risalahs) > 0)
@foreach($risalahs as $r)
<div id="risalahModal{{ $r->id }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" onclick="if(event.target === this) closeRisalah({{ $r->id }})">
    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl max-w-2xl w-full border border-gray-700 animate-fade-in-up">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-700">
            <h3 class="text-2xl font-bold text-white">
                <i class="bi bi-file-earmark mr-2 text-yellow-400"></i>Risalah Pertemuan {{ $r->pertemuan_ke }}
            </h3>
            <button onclick="closeRisalah({{ $r->id }})" class="text-gray-400 hover:text-white text-2xl transition-colors">
                <i class="bi bi-x"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 max-h-96 overflow-y-auto space-y-6">
            <!-- Kursus -->
            <div class="pb-4 border-b border-gray-700">
                <p class="text-gray-400 text-sm mb-1 font-semibold">Kursus</p>
                <p class="text-white text-lg font-semibold">{{ $kursus->nama }}</p>
            </div>

            <!-- Pertemuan Ke -->
            <div class="pb-4 border-b border-gray-700">
                <p class="text-gray-400 text-sm mb-1 font-semibold">Pertemuan Ke-</p>
                <p class="text-white text-lg font-semibold">{{ $r->pertemuan_ke }}</p>
            </div>

            <!-- Tanggal -->
            <div class="pb-4 border-b border-gray-700">
                <p class="text-yellow-400 text-sm mb-2 font-semibold">Tanggal Pertemuan</p>
                <p class="text-white text-lg">
                    {{ $r->tgl_pertemuan ? \Carbon\Carbon::parse($r->tgl_pertemuan)->translatedFormat('d F Y') : 'Belum ditentukan' }}
                </p>
            </div>

            <!-- Materi -->
            <div class="pb-4 border-b border-gray-700">
                <p class="text-blue-400 text-sm mb-2 font-semibold">Materi Pembelajaran</p>
                @if($r->materi)
                    <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4 text-gray-300 text-sm">
                        {{ $r->materi }}
                    </div>
                @else
                    <p class="text-gray-400 italic">Belum ada materi</p>
                @endif
            </div>

            <!-- Catatan -->
            <div class="pb-4 border-b border-gray-700">
                <p class="text-green-400 text-sm mb-2 font-semibold">Catatan</p>
                @if($r->catatan)
                    <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-4 text-gray-300 text-sm">
                        {{ $r->catatan }}
                    </div>
                @else
                    <p class="text-gray-400 italic">Tidak ada catatan</p>
                @endif
            </div>

            <!-- Peserta Hadir -->
            <div>
                <p class="text-indigo-400 text-sm mb-2 font-semibold">Jumlah Peserta Hadir</p>
                <div class="inline-flex items-center px-4 py-2 bg-indigo-500/20 text-indigo-400 rounded-lg border border-indigo-500/30 font-semibold">
                    <i class="bi bi-people-fill mr-2"></i>
                    <span>{{ $r->absensis()->count() ?? 0 }} peserta</span>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-between p-6 border-t border-gray-700 bg-gray-900/50">
            @if($r->dokumen)
                <a href="{{ route('instruktur.risalah.download', $r->id) }}" class="flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-lg transform hover:scale-105 transition-all duration-200" target="_blank">
                    <i class="bi bi-download mr-2"></i>Download Dokumen
                </a>
            @else
                <div></div>
            @endif
            <button onclick="closeRisalah({{ $r->id }})" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors duration-200">
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
