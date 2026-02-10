@extends('kursus::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <h2 class="fw-bold">Tambah Jadwal - {{ $kursus->nama }}</h2>

    <div class="card mt-3">
        <div class="card-body">
            <form action="/admin/kursus/{{ $kursus->id }}/jadwal" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Pertemuan Ke</label>
                    <input type="number" name="pertemuan_ke" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tgl_pertemuan" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lokasi</label>
                    <select name="lokasi_id" class="form-control" required>
                        <option value="">Pilih Lokasi</option>
                        @foreach($lokasis as $lokasi)
                            <option value="{{ $lokasi->id }}">{{ $lokasi->nama }} - {{ $lokasi->kota }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kelas</label>
                    <select name="kela_id" class="form-control" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }} (Kapasitas: {{ $k->kapasitas }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hari</label>
                    <select name="hari_id" class="form-control" required>
                        <option value="">Pilih Hari</option>
                        @foreach($haris as $hari)
                            <option value="{{ $hari->id }}">{{ $hari->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control">
                </div>

                <button class="btn btn-primary">Simpan</button>
                <a href="/admin/kursus/{{ $kursus->id }}/jadwal" class="btn btn-link">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
