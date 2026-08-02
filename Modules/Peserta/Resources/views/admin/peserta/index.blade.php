@extends('layouts.admin')

@section('title', 'Peserta')
@section('page-context', 'Peserta')
@section('page-title', 'Peserta')
@section('page-description', 'Direktori seluruh peserta yang punya akun di sistem, lengkap dengan kontak dan asal instansi.')

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
            <h2 class="bk-panel__title">Daftar peserta</h2>
            <p class="bk-panel__subtitle">
                {{ $pesertas->total() }} peserta
                @if ($search !== '')
                    cocok dengan pencarian
                @else
                    terdaftar
                @endif
                · halaman {{ $pesertas->currentPage() }} dari {{ max($pesertas->lastPage(), 1) }}
            </p>
        </div>

        <div class="bk-row">
            <form method="GET" action="{{ route('admin.peserta.index') }}" class="bk-pillfield bk-pillfield--cari">
                <i class="bi bi-search" aria-hidden="true"></i>
                <label for="search" class="bk-sr">Cari peserta</label>
                <input type="search" id="search" name="search" value="{{ $search }}"
                       placeholder="Nama, email, nomor, instansi">
                <button type="submit" class="bk-sr">Cari</button>
            </form>
            <a href="{{ route('admin.peserta.export') }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-download" aria-hidden="true"></i> Unduh
            </a>
            <a href="{{ route('admin.peserta.create') }}" class="bk-btn bk-btn--pri bk-btn--sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah peserta
            </a>
        </div>
    </div>

    @if ($search !== '')
        <div class="bk-panel__foot" style="border-top:0;border-bottom:1px solid var(--bk-sand-100)">
            <span class="bk-chip">
                Pencarian: {{ $search }}
                <a href="{{ route('admin.peserta.index') }}" title="Hapus pencarian">
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                    <span class="bk-sr">Hapus pencarian</span>
                </a>
            </span>
        </div>
    @endif

    @if ($pesertas->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-people" aria-hidden="true"></i></span>
            <h3>{{ $search !== '' ? 'Tidak ada yang cocok' : 'Belum ada peserta' }}</h3>
            <p>
                {{ $search !== ''
                    ? 'Coba kata kunci lain, atau hapus pencarian untuk melihat seluruh peserta.'
                    : 'Peserta akan muncul di sini setelah mendaftar sendiri atau ditambahkan dari halaman ini.' }}
            </p>
            <a href="{{ $search !== '' ? route('admin.peserta.index') : route('admin.peserta.create') }}" class="bk-btn bk-btn--pri">
                {{ $search !== '' ? 'Tampilkan semua' : 'Tambah peserta' }}
            </a>
        </div>
    @else
        <table class="bk-table">
            <thead>
                <tr>
                    <th>Peserta</th>
                    <th>Nomor peserta</th>
                    <th>Kontak</th>
                    <th>Instansi</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesertas as $p)
                    <tr>
                        <td>
                            <span class="bk-who">
                                <span class="bk-who__ini">
                                    {{ mb_strtoupper(mb_substr($p->user->name ?? '?', 0, 2)) }}
                                </span>
                                <span>
                                    <b>{{ $p->user->name ?? 'Akun terhapus' }}</b>
                                    <small class="bk-muted">{{ $p->user->email ?? '-' }}</small>
                                </span>
                            </span>
                        </td>
                        <td><span class="bk-code">{{ $p->nomor_peserta }}</span></td>
                        <td class="nw">{{ $p->no_hp }}</td>
                        <td>
                            @if ($p->instansi)
                                {{ $p->instansi }}
                            @else
                                <span class="bk-muted">Belum diisi</span>
                            @endif
                        </td>
                        <td class="r nw">
                            <a href="{{ route('admin.peserta.edit', $p->id) }}" class="bk-iconbtn" title="Ubah {{ $p->user->name ?? 'peserta' }}">
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                <span class="bk-sr">Ubah</span>
                            </a>
                            <form method="POST" action="{{ route('admin.peserta.destroy', $p->id) }}" style="display:inline"
                                  onsubmit="return confirm('Hapus {{ $p->user->name ?? 'peserta ini' }}? Akun dan seluruh riwayatnya ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bk-iconbtn bk-iconbtn--danger" title="Hapus peserta">
                                    <i class="bi bi-trash3" aria-hidden="true"></i>
                                    <span class="bk-sr">Hapus</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($pesertas->hasPages())
            <div class="bk-panel__foot">{{ $pesertas->links() }}</div>
        @endif
    @endif
</section>
@endsection
