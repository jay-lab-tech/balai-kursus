@extends('layouts.admin')

@section('title', 'Instruktur')
@section('page-context', 'Sumber Daya')
@section('page-title', 'Instruktur')
@section('page-description', 'Pengajar yang punya akun di sistem. Dari sini mereka bisa ditugaskan ke kelas, mengisi absensi, dan menulis risalah.')

@section('content')
@php
    $mengampu = $instrukturs->where('kursuses_count', '>', 0)->count();
@endphp

@if (session('success'))
    <div class="bk-note bk-note--baik">
        <i class="bi bi-check-circle-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="bk-stats bk-stats--3">
    <article class="bk-stat">
        <span class="bk-stat__icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Total instruktur</span>
        <p class="bk-stat__value">{{ $instrukturs->count() }}</p>
        <p class="bk-stat__hint">Seluruh pengajar yang punya akun.</p>
    </article>
    <article class="bk-stat bk-stat--terra">
        <span class="bk-stat__icon"><i class="bi bi-journal-bookmark" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Sedang mengampu</span>
        <p class="bk-stat__value">{{ $mengampu }}</p>
        <p class="bk-stat__hint">Instruktur yang memegang setidaknya satu kelas.</p>
    </article>
    <article class="bk-stat bk-stat--amber">
        <span class="bk-stat__icon"><i class="bi bi-hourglass" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Belum ditugaskan</span>
        <p class="bk-stat__value">{{ $instrukturs->count() - $mengampu }}</p>
        <p class="bk-stat__hint">Bisa dipakai saat menyusun kelas baru.</p>
    </article>
</div>

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Daftar instruktur</h2>
            <p class="bk-panel__subtitle">Menghapus instruktur juga menghapus akunnya beserta seluruh riwayat mengajarnya.</p>
        </div>
        <a href="{{ route('admin.instruktur.create') }}" class="bk-btn bk-btn--pri bk-btn--sm">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah instruktur
        </a>
    </div>

    @if ($instrukturs->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
            <h3>Belum ada instruktur</h3>
            <p>Tambahkan instruktur agar kelas program bisa diberi pengajar.</p>
            <a href="{{ route('admin.instruktur.create') }}" class="bk-btn bk-btn--pri">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah instruktur
            </a>
        </div>
    @else
        <table class="bk-table">
            <thead>
                <tr>
                    <th>Instruktur</th>
                    <th>Spesialisasi</th>
                    <th class="r">Kelas diampu</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($instrukturs as $i)
                    <tr>
                        <td>
                            <span class="bk-who">
                                <span class="bk-who__ini bk-who__ini--terra">
                                    {{ mb_strtoupper(mb_substr($i->nama_instr, 0, 2)) }}
                                </span>
                                <span>
                                    <b>{{ $i->nama_instr }}</b>
                                    <small class="bk-muted">{{ $i->user->email ?? 'Akun terhapus' }}</small>
                                </span>
                            </span>
                        </td>
                        <td>{{ $i->spesialisasi }}</td>
                        <td class="r">
                            <span class="bk-tag {{ $i->kursuses_count ? 'bk-tag--jalan' : 'bk-tag--diam' }}">
                                {{ $i->kursuses_count }} kelas
                            </span>
                        </td>
                        <td class="r nw">
                            <a href="{{ route('admin.instruktur.edit', $i->id) }}" class="bk-iconbtn" title="Ubah {{ $i->nama_instr }}">
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                <span class="bk-sr">Ubah</span>
                            </a>
                            <form method="POST" action="{{ route('admin.instruktur.destroy', $i->id) }}" style="display:inline"
                                  onsubmit="return confirm('Hapus {{ $i->nama_instr }}? Akun dan riwayat mengajarnya ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bk-iconbtn bk-iconbtn--danger" title="Hapus instruktur">
                                    <i class="bi bi-trash3" aria-hidden="true"></i>
                                    <span class="bk-sr">Hapus</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</section>
@endsection
