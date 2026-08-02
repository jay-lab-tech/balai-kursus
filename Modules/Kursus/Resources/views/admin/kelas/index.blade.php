@extends('layouts.admin')

@section('title', 'Ruang Kelas')
@section('page-context', 'Akademik · Lokasi & Ruang')
@section('page-title', 'Ruang kelas')
@section('page-description', 'Daftar ruang beserta kapasitas dan fasilitasnya, dipakai sebagai pilihan saat menyusun jadwal.')

@section('content')

@if (session('success'))
    <div class="bk-note bk-note--baik">
        <i class="bi bi-check-circle-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="bk-stats bk-stats--3">
    <article class="bk-stat">
        <span class="bk-stat__icon"><i class="bi bi-door-open" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Ruang tercatat</span>
        <p class="bk-stat__value">{{ $kelas->total() }}</p>
        <p class="bk-stat__hint">Seluruh ruang yang bisa dipilih di form jadwal.</p>
    </article>
    <article class="bk-stat bk-stat--amber">
        <span class="bk-stat__icon"><i class="bi bi-people" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Kapasitas di halaman ini</span>
        <p class="bk-stat__value">{{ $kelas->sum('kapasitas') }}</p>
        <p class="bk-stat__hint">Jumlah kursi dari ruang yang sedang ditampilkan.</p>
    </article>
    <article class="bk-stat bk-stat--terra">
        <span class="bk-stat__icon"><i class="bi bi-rulers" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Rata-rata kapasitas</span>
        <p class="bk-stat__value">{{ $kelas->count() ? round($kelas->avg('kapasitas')) : 0 }}</p>
        <p class="bk-stat__hint">Patokan kasar saat menaksir ukuran rombongan belajar.</p>
    </article>
</div>

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Daftar ruang</h2>
            <p class="bk-panel__subtitle">Kapasitas dan fasilitas setiap ruang belajar.</p>
        </div>
        <a href="{{ route('admin.kelas.create') }}" class="bk-btn bk-btn--pri bk-btn--sm">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah kelas
        </a>
    </div>

    @if ($kelas->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-door-open" aria-hidden="true"></i></span>
            <h3>Belum ada ruang kelas</h3>
            <p>Tambahkan ruang pertama agar penempatan peserta bisa diatur dengan jelas.</p>
            <a href="{{ route('admin.kelas.create') }}" class="bk-btn bk-btn--pri">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah kelas
            </a>
        </div>
    @else
        <table class="bk-table">
            <thead>
                <tr>
                    <th class="r">No</th>
                    <th>Nama ruang</th>
                    <th class="r nw">Kapasitas</th>
                    <th>Fasilitas</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kelas as $key => $k)
                    <tr>
                        <td class="r">{{ $kelas->firstItem() + $key }}</td>
                        <td>
                            <b>{{ $k->nama }}</b><br>
                            <span class="bk-muted">{{ $k->keterangan ?: 'Tanpa keterangan tambahan' }}</span>
                        </td>
                        <td class="r nw">{{ $k->kapasitas }} orang</td>
                        <td>{{ $k->fasilitas }}</td>
                        <td class="r nw">
                            <a href="{{ route('admin.kelas.show', $k->id) }}" class="bk-iconbtn" title="Detail {{ $k->nama }}">
                                <i class="bi bi-eye" aria-hidden="true"></i>
                                <span class="bk-sr">Detail</span>
                            </a>
                            <a href="{{ route('admin.kelas.edit', $k->id) }}" class="bk-iconbtn" title="Ubah {{ $k->nama }}">
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                <span class="bk-sr">Ubah</span>
                            </a>
                            <form method="POST" action="{{ route('admin.kelas.destroy', $k->id) }}" style="display:inline"
                                  onsubmit="return confirm('Hapus ruang {{ $k->nama }}? Jadwal yang memakainya bisa ikut terdampak.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bk-iconbtn bk-iconbtn--danger" title="Hapus {{ $k->nama }}">
                                    <i class="bi bi-trash3" aria-hidden="true"></i>
                                    <span class="bk-sr">Hapus</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($kelas->hasPages())
            <div class="bk-panel__foot">{{ $kelas->links() }}</div>
        @endif
    @endif
</section>
@endsection
