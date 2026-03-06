@extends('layouts.admin')

@section('title', 'Semua Risalah')

@section('page-title', 'Semua Risalah')

@section('content')
<div class="space-y-6">
    <div class="max-w-7xl">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kursus</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertemuan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instruktur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($risalahs as $r)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $r->kursus->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $r->pertemuan_ke }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $r->tgl_pertemuan->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $r->instruktur->nama_instr }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $r->materi }}</td>
                            </tr>
                            @endforeach
                            @if($risalahs->isEmpty())
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada risalah.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
