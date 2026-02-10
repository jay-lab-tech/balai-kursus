@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Jadwal Kursus - {{ $kursus->nama }}</h2>
        <a href="/admin/kursus/{{ $kursus->id }}/jadwal/create" class="btn btn-primary">Tambah Jadwal</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Pertemuan</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Lokasi</th>
                        <th>Kelas</th>
                        <th>Hari</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwals as $jadwal)
                        <tr>
                            <td>{{ $jadwal->pertemuan_ke ?? '-' }}</td>
                            <td>{{ $jadwal->tgl_pertemuan->format('d M Y') }}</td>
                            <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                            <td>{{ $jadwal->lokasi->nama ?? '-' }}</td>
                            <td>{{ $jadwal->kela->nama ?? '-' }}</td>
                            <td>{{ $jadwal->hari->nama ?? '-' }}</td>
                            <td>
                                <a href="/admin/kursus/{{ $kursus->id }}/jadwal/{{ $jadwal->id }}/edit" class="btn btn-sm btn-secondary">Edit</a>
                                <form action="/admin/kursus/{{ $kursus->id }}/jadwal/{{ $jadwal->id }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus jadwal?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if($jadwals->isEmpty())
                        <tr><td colspan="7">Belum ada jadwal.</td></tr>
                    @endif
                </tbody>
            </table>
            @if($jadwals->hasPages())
                <nav>
                    {{ $jadwals->links('pagination::bootstrap-5') }}
                </nav>
            @endif
        </div>
    </div>
</div>
@endsection
