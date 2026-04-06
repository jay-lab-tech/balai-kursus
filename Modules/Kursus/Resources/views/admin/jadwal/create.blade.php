@extends('layouts.admin')

@section('title', 'Tambah Jadwal')

@section('page-title', 'Tambah Jadwal')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Tambah Jadwal</h2>
        <p class="text-sm text-gray-500">Buat jadwal pertemuan baru untuk kelas <span class="font-semibold text-gray-700">{{ $kursus->nama }}</span>.</p>
    </div>

    @if($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            <ul class="space-y-1 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-4xl rounded-2xl bg-white p-6 shadow">
        <form action="{{ route('admin.jadwal.store', $kursus) }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pertemuan Ke</label>
                    <input type="number" name="pertemuan_ke" value="{{ old('pertemuan_ke') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Pertemuan</label>
                    <input type="date" name="tgl_pertemuan" value="{{ old('tgl_pertemuan') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                    <select name="lokasi_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Pilih Lokasi</option>
                        @foreach($lokasis as $lokasi)
                            <option value="{{ $lokasi->id }}" @selected(old('lokasi_id') == $lokasi->id)>{{ $lokasi->nama }} - {{ $lokasi->kota }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kelas</label>
                    <select name="kela_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" @selected(old('kela_id') == $k->id)>{{ $k->nama }} ({{ $k->kapasitas ?? '-' }} kursi)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Hari</label>
                    <select name="hari_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Pilih Hari</option>
                        @foreach($haris as $hari)
                            <option value="{{ $hari->id }}" @selected(old('hari_id') == $hari->id)>{{ $hari->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">Simpan Jadwal</button>
                <a href="{{ route('admin.jadwal.index', $kursus) }}" class="rounded-xl bg-gray-200 px-5 py-3 font-semibold text-gray-800 hover:bg-gray-300">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
