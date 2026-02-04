@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Semua Jadwal</h2>
        <a href="/admin/kursus" class="btn btn-secondary">Kembali ke Kursus</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kursus</th>
                        <th>Pertemuan</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwals as $jadwal)
                        <tr>
                            <td>{{ $jadwal->kursus->nama }}</td>
                            <td>{{ $jadwal->pertemuan_ke ?? '-' }}</td>
                            <td>{{ $jadwal->tgl_pertemuan->format('d M Y') }}</td>
                            <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                            <td>
                                <a href="/admin/kursus/{{ $jadwal->kursus->id }}/jadwal/{{ $jadwal->id }}/edit" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    @if($jadwals->isEmpty())
                        <tr><td colspan="5">Belum ada jadwal.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
