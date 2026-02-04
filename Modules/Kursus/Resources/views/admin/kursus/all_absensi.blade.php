@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <h2 class="fw-bold">Semua Absensi</h2>

    <div class="card mt-3">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Risalah</th>
                        <th>Peserta</th>
                        <th>Status</th>
                        <th>Jam</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($absensis as $a)
                        <tr>
                            <td>Pertemuan {{ $a->risalah->pertemuan_ke }} - {{ $a->risalah->tgl_pertemuan->format('d M Y') }}</td>
                            <td>{{ $a->pendaftaran->peserta->user->name ?? '-' }}</td>
                            <td>{{ $a->status }}</td>
                            <td>{{ $a->jam_datang }}</td>
                        </tr>
                    @endforeach
                    @if($absensis->isEmpty())
                        <tr><td colspan="4">Belum ada data absensi.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
