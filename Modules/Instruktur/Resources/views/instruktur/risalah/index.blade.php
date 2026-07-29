@extends('instruktur::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-wrap">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="bi bi-file-earmark me-2"></i>{{ $kursus->nama }}
            </h2>
            <small class="text-muted">Daftar Pertemuan & Risalah</small>
        </div>
        <a href="{{ route('instruktur.kursus.show', $kursus) }}" class="inline-flex items-center gap-2 rounded-xl border border-[#c8d9d6] bg-white px-4 py-2 font-semibold text-[#40627d] shadow-sm transition hover:border-[#0d9488] hover:text-[#0f766e]">
            <i class="bi bi-arrow-left"></i>
            Ringkasan Kursus
        </a>
        {{-- Admin membuat pertemuan; instruktur tidak dapat menambah pertemuan --}}
    </div>

    @if($risalahs && count($risalahs) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 w-full">
            @foreach($risalahs as $r)
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-2xl shadow-lg p-8 flex flex-col justify-between min-h-[180px] h-full hover:border-yellow-500/50 transition-colors duration-200 w-full">
                <div>
                    <h4 class="text-2xl font-bold text-white mb-2 flex items-center">
                        <i class="bi bi-calendar text-yellow-400 mr-2"></i>Pertemuan {{ $r->pertemuan_ke }}
                    </h4>
                    <div class="text-gray-400 mb-2 flex items-center text-base">
                        <i class="bi bi-calendar2 mr-2"></i>{{ $r->tgl_pertemuan ? \Carbon\Carbon::parse($r->tgl_pertemuan)->format('d/m/Y') : '-' }}
                    </div>
                    <div class="text-gray-400 flex items-center text-base mb-4">
                        <i class="bi bi-file-text mr-2"></i>{{ Str::limit($r->materi ?? '-', 40) }}
                    </div>
                    <div class="flex flex-row gap-6 mb-4">
                        <div class="flex-1 bg-gray-700 rounded-lg p-4 text-center">
                            <div class="text-xs text-gray-400">Peserta Hadir</div>
                            <div class="text-2xl font-bold text-yellow-400">{{ $r->absensis()->count() ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 mt-auto">
                    <a href="/instruktur/risalah/{{ $r->id }}/edit" class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-semibold transition-colors">
                        <i class="bi bi-file-earmark mr-1"></i>Risalah
                    </a>
                    <a href="/instruktur/risalah/{{ $r->id }}/absensi" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 text-white font-semibold rounded-lg transition-colors">
                        <i class="bi bi-clipboard-check mr-1"></i>Absensi
                    </a>
                    @if($r->dokumen)
                        <a href="{{ route('instruktur.risalah.download', $r->id) }}" class="inline-flex items-center px-4 py-2 bg-green-700 hover:bg-green-800 text-white rounded-lg font-semibold transition-colors" target="_blank">
                            <i class="bi bi-download mr-1"></i>Download Dokumen
                        </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Belum ada risalah.</strong> Hubungi admin untuk menambahkan pertemuan.
        </div>
    @endif
</div>
@endsection
