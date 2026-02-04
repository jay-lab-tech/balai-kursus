@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <h2 class="fw-bold">Edit Jadwal - {{ $kursus->nama }}</h2>

    <div class="card mt-3">
        <div class="card-body">
            <form action="/admin/kursus/{{ $kursus->id }}/jadwal/{{ $jadwal->id }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Pertemuan Ke</label>
                    <input type="number" name="pertemuan_ke" value="{{ $jadwal->pertemuan_ke }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tgl_pertemuan" value="{{ $jadwal->tgl_pertemuan->format('Y-m-d') }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="{{ $jadwal->jam_mulai }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="{{ $jadwal->jam_selesai }}" class="form-control">
                </div>

                <button class="btn btn-primary">Perbarui</button>
                <a href="/admin/kursus/{{ $kursus->id }}/jadwal" class="btn btn-link">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
