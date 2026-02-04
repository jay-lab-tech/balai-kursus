@extends('layouts.app-bootstrap')

@section('title', 'Detail Kursus')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <h3>{{ $kursus->nama }}</h3>
            <p><strong>Program:</strong> {{ $kursus->program->nama }}</p>
            <p><strong>Level:</strong> {{ $kursus->level->nama }}</p>
            <p><strong>Instruktur:</strong> {{ $kursus->instruktur->nama_instr }}</p>
            <p><strong>Harga:</strong> Rp {{ number_format($kursus->harga) }}</p>
            <p><strong>Kuota:</strong> {{ $kursus->kuota }}</p>

            <hr>
            <h5>Jadwal</h5>
            <ul class="list-group mb-3">
                @foreach($kursus->jadwals as $jadwal)
                    <li class="list-group-item">
                        Pertemuan {{ $jadwal->pertemuan_ke ?? '-' }} — {{ $jadwal->tgl_pertemuan->format('d M Y') }} @if($jadwal->jam_mulai) ({{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}) @endif
                    </li>
                @endforeach
                @if($kursus->jadwals->isEmpty())
                    <li class="list-group-item">Belum ada jadwal.</li>
                @endif
            </ul>

            @php
                $peserta = auth()->user()->peserta;
                $sudahDaftar = $peserta->pendaftarans()
                    ->where('kursus_id', $kursus->id)
                    ->exists();
            @endphp

            @if($sudahDaftar)
                <button class="btn btn-success" disabled>
                    <i class="bi bi-check-circle me-1"></i>Sudah Daftar
                </button>
            @else
                <form method="POST" action="{{ route('peserta.kursus.daftar', $kursus->id) }}" style="display: inline;">
                    @csrf
                    <button class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Daftar Kursus
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
