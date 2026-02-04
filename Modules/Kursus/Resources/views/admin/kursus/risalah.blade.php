@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <h2 class="fw-bold">Risalah - {{ $kursus->nama }}</h2>

    <div class="card mt-3">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pertemuan</th>
                        <th>Tanggal</th>
                        <th>Instruktur</th>
                        <th>Materi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($risalahs as $r)
                        <tr>
                            <td>{{ $r->pertemuan_ke }}</td>
                            <td>{{ $r->tgl_pertemuan->format('d M Y') }}</td>
                            <td>{{ $r->instruktur->nama_instr }}</td>
                            <td>{{ $r->materi }}</td>
                        </tr>
                    @endforeach
                    @if($risalahs->isEmpty())
                        <tr><td colspan="4">Belum ada risalah.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
