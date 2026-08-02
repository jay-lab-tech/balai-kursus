@extends('layouts.admin')

@section('title', 'Hari')
@section('page-context', 'Akademik · Jadwal')
@section('page-title', 'Hari')
@section('page-description', 'Daftar hari beserta urutannya, dipakai sebagai pilihan saat menyusun jadwal pertemuan.')

@section('content')

@if (session('success'))
    <div class="bk-note bk-note--baik">
        <i class="bi bi-check-circle-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Daftar hari</h2>
            <p class="bk-panel__subtitle">{{ $haris->total() }} hari tercatat. Urutan yang rapi membuat pilihan di form jadwal lebih mudah dibaca.</p>
        </div>
        <a href="{{ route('admin.hari.create') }}" class="bk-btn bk-btn--pri bk-btn--sm">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah hari
        </a>
    </div>

    @if ($haris->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-calendar-week" aria-hidden="true"></i></span>
            <h3>Belum ada hari</h3>
            <p>Tambahkan hari agar modul penjadwalan punya pilihan untuk dipakai.</p>
            <a href="{{ route('admin.hari.create') }}" class="bk-btn bk-btn--pri">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah hari
            </a>
        </div>
    @else
        <table class="bk-table is-padat">
            <thead>
                <tr>
                    <th class="r">Urutan</th>
                    <th>Nama hari</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($haris as $hari)
                    <tr>
                        <td class="r">{{ $hari->urutan }}</td>
                        <td><b>{{ $hari->nama }}</b></td>
                        <td class="r nw">
                            <a href="{{ route('admin.hari.edit', $hari->id) }}" class="bk-iconbtn" title="Ubah {{ $hari->nama }}">
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                <span class="bk-sr">Ubah</span>
                            </a>
                            <form method="POST" action="{{ route('admin.hari.destroy', $hari->id) }}" style="display:inline"
                                  onsubmit="return confirm('Hapus {{ $hari->nama }} dari daftar hari?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bk-iconbtn bk-iconbtn--danger" title="Hapus {{ $hari->nama }}">
                                    <i class="bi bi-trash3" aria-hidden="true"></i>
                                    <span class="bk-sr">Hapus</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($haris->hasPages())
            <div class="bk-panel__foot">{{ $haris->links() }}</div>
        @endif
    @endif
</section>
@endsection
