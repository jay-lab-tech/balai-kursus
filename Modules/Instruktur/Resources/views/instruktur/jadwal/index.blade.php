
@extends('instruktur::layouts.master')

@section('title', 'Jadwal - Instruktur')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-white flex items-center">
                <i class="bi bi-calendar-week text-yellow-400 mr-3"></i>Jadwal Mengajar
            </h1>
            <a href="{{ url('/instruktur/dashboard') }}" class="inline-flex items-center px-5 py-2 border border-gray-500 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors">
                <i class="bi bi-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        @if(count($jadwals) > 0)
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-900/60">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-yellow-400 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-yellow-400 uppercase tracking-wider">Hari</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-yellow-400 uppercase tracking-wider">Waktu</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-yellow-400 uppercase tracking-wider">Pert.</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-yellow-400 uppercase tracking-wider">Kursus</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-yellow-400 uppercase tracking-wider">Lokasi</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-yellow-400 uppercase tracking-wider">Ruang Kelas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @foreach($jadwals as $j)
                            <tr class="hover:bg-sky-600/10 transition-colors">
                                <td class="px-4 py-3 text-sm text-white whitespace-nowrap">
                                    {{ optional($j->tgl_pertemuan)->format('d M Y') ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-300 whitespace-nowrap">{{ $j->hari->nama ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-300 whitespace-nowrap">
                                    @if($j->jam_mulai && $j->jam_selesai)
                                        {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-300 whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 rounded-md bg-yellow-500/10 text-yellow-400 font-semibold">
                                        {{ $j->pertemuan_ke ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-white">
                                    <div class="font-semibold">{{ $j->kursus->nama_kursus ?? $j->kursus->nama ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-400">{{ $j->kursus->program->nama_program ?? $j->kursus->program->nama ?? '' }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-300">{{ $j->lokasi->nama ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-300">{{ $j->kela->nama ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center h-24 w-24 bg-gray-700/50 rounded-full mb-6">
                    <i class="bi bi-calendar-x text-4xl text-gray-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Belum Ada Jadwal</h2>
                <p class="text-gray-400">Jadwal mengajar akan muncul di sini setelah admin menetapkannya.</p>
            </div>
        @endif
    </div>
</div>
@endsection
