@extends('peserta::layouts.student')

@section('title', 'Jelajahi Kursus - Balai Kursus')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-white mb-2">
                <i class="bi bi-book text-yellow-400 mr-3"></i>Jelajahi Kursus Kami
            </h1>
            <p class="text-gray-400 text-lg">Pilih kursus yang sesuai dengan kebutuhan pembelajaran Anda</p>
        </div>

        <!-- Alerts -->
        @if($errors->any())
        <div class="mb-6 bg-sky-500/20 border-l-4 border-sky-500 rounded-lg p-4 text-white">
            <div class="flex items-start">
                <i class="bi bi-exclamation-circle text-sky-400 mr-3 mt-0.5"></i>
                <div>
                    <h3 class="font-bold mb-2">Ada Kesalahan</h3>
                    <ul class="space-y-1 text-sm">
                        @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        @if(session('success'))
        <div class="mb-6 bg-green-500/20 border-l-4 border-green-500 rounded-lg p-4 text-white">
            <div class="flex items-start">
                <i class="bi bi-check-circle text-green-400 mr-3 mt-0.5"></i>
                <div>
                    <h3 class="font-bold">Berhasil</h3>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-yellow-500/20 border-l-4 border-yellow-500 rounded-lg p-4 text-white">
            <div class="flex items-start">
                <i class="bi bi-exclamation-triangle text-yellow-400 mr-3 mt-0.5"></i>
                <div>
                    <h3 class="font-bold">Peringatan</h3>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Search -->
        <div class="mb-12">
            <div class="relative">
                <i class="bi bi-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                <input 
                    type="text" 
                    id="filterInputPesertaKursus" 
                    placeholder="Cari nama kursus, program, level, instruktur..." 
                    class="w-full pl-12 pr-6 py-4 bg-white/10 border-2 border-yellow-400/30 hover:border-yellow-400/60 focus:border-yellow-400 focus:bg-white/20 text-white placeholder-gray-400 rounded-xl transition-all duration-300"
                />
            </div>
        </div>

        <!-- Courses Grid -->
        @if($kursus->isEmpty())
            <div class="text-center py-20">
                <div class="text-6xl text-gray-600 mb-6">
                    <i class="bi bi-inbox"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Tidak Ada Kursus</h2>
                <p class="text-gray-400 mb-8">Belum ada kursus yang tersedia saat ini. Silakan cek kembali nanti.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($kursus as $k)
                <div class="peserta-kursus-item group">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 h-full flex flex-col">
                        <!-- Card Header with Gradient -->
                        <div class="h-32 bg-gradient-to-r from-sky-600 to-sky-700 relative overflow-hidden">
                            <div class="absolute inset-0 opacity-30">
                                <i class="bi bi-book-fill text-white text-6xl absolute -right-4 -top-4"></i>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-yellow-400 to-sky-400"></div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex-grow flex flex-col">
                            <!-- Title -->
                            <h3 class="text-xl font-bold text-gray-900 mb-4 group-hover:text-sky-600 transition-colors line-clamp-2">
                                {{ $k->nama }}
                            </h3>

                            <!-- Info Grid -->
                            <div class="space-y-3 mb-6 flex-grow">
                                <div class="flex items-start">
                                    <i class="bi bi-diagram-3 text-sky-500 text-lg mr-3 mt-1 flex-shrink-0"></i>
                                    <div>
                                        <p class="text-xs text-gray-600">Program</p>
                                        <p class="font-semibold text-gray-900">{{ $k->program->nama }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <i class="bi bi-bar-chart text-yellow-500 text-lg mr-3 mt-1 flex-shrink-0"></i>
                                    <div>
                                        <p class="text-xs text-gray-600">Level</p>
                                        <p class="font-semibold text-gray-900">{{ $k->level->nama }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <i class="bi bi-person-badge text-gray-700 text-lg mr-3 mt-1 flex-shrink-0"></i>
                                    <div>
                                        <p class="text-xs text-gray-600">Instruktur</p>
                                        <p class="font-semibold text-gray-900">{{ $k->instruktur->nama_instr }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Price Box -->
                            <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border-2 border-yellow-200 rounded-xl p-4 mb-6 text-center">
                                <p class="text-xs text-gray-600 font-semibold uppercase">Harga Kursus</p>
                                <p class="text-2xl font-bold text-sky-600">Rp {{ number_format($k->harga, 0, ',', '.') }}</p>
                               @if($k->harga_upi)
                                    <p class="text-xs text-gray-600 mt-1">UPI: Rp {{ number_format($k->harga_upi, 0, ',', '.') }}</p>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="grid grid-cols-2 gap-3">
                                <a href="/peserta/kursus/{{ $k->id }}" class="inline-flex items-center justify-center px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 font-bold rounded-lg transition-all duration-200 transform hover:scale-105">
                                    <i class="bi bi-eye mr-2"></i>
                                    <span>Detail</span>
                                </a>
                                <form action="{{ route('peserta.kursus.daftar', $k->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-600 hover:to-sky-700 text-white font-bold rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                        <i class="bi bi-check-circle mr-2"></i>
                                        <span>Daftar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse
            </div>
        @endif
    </div>
</div>

<style>
    /* Line clamp utility */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Smooth transitions */
    * {
        transition: all 0.3s ease;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #1f2937;
    }

    ::-webkit-scrollbar-thumb {
        background: #991b1b;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #dc2626;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterInput = document.getElementById('filterInputPesertaKursus');
        filterInput.addEventListener('input', function() {
            const filter = filterInput.value.toLowerCase();
            const items = document.querySelectorAll('.peserta-kursus-item');
            items.forEach(item => {
                const text = item.innerText.toLowerCase();
                item.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    });
</script>
@endsection
