@extends('layouts.admin')

@section('title', 'Jadwal Kursus')

@section('page-title', 'Jadwal Kursus - ' . $kursus->nama)

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Jadwal Kursus - {{ $kursus->nama }}</h2>
        <a href="/admin/kursus/{{ $kursus->id }}/jadwal/create" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">Tambah Jadwal</a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertemuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($jadwals as $jadwal)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $jadwal->pertemuan_ke ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $jadwal->tgl_pertemuan->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $jadwal->lokasi->nama ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $jadwal->kela->nama ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $jadwal->hari->nama ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2 flex">
                                <a href="/admin/kursus/{{ $kursus->id }}/jadwal/{{ $jadwal->id }}/edit" class="text-gray-600 hover:text-gray-900">Edit</a>
                                <form action="/admin/kursus/{{ $kursus->id }}/jadwal/{{ $jadwal->id }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:text-red-900" onclick="return confirm('Hapus jadwal?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if($jadwals->isEmpty())
                        <tr><td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada jadwal.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
        @if($jadwals->hasPages())
        <div class="bg-white border-t border-gray-200 px-4 py-5 sm:px-6">
            {{ $jadwals->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
