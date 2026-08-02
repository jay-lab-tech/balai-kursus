@extends('layouts.admin')

@section('title', 'Program')
@section('page-context', 'Akademik')
@section('page-title', 'Program')
@section('page-description', 'Payung besar yang menaungi level dan kelas. Satu program bisa punya banyak kelas di beberapa level.')

@section('content')
@php
    $totalKursus = $program->sum(fn ($item) => $item->kursuses->count());
    $tanpaKursus = $program->filter(fn ($item) => $item->kursuses->isEmpty())->count();
@endphp

@if (session('success'))
    <div class="bk-note bk-note--baik">
        <i class="bi bi-check-circle-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="bk-stats bk-stats--3">
    <article class="bk-stat">
        <span class="bk-stat__icon"><i class="bi bi-diagram-3" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Total program</span>
        <p class="bk-stat__value">{{ $program->count() }}</p>
        <p class="bk-stat__hint">Kategori utama yang tersedia di sistem.</p>
    </article>
    <article class="bk-stat bk-stat--terra">
        <span class="bk-stat__icon"><i class="bi bi-journal-bookmark" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Kelas terhubung</span>
        <p class="bk-stat__value">{{ $totalKursus }}</p>
        <p class="bk-stat__hint">Seluruh kelas yang sudah dikelompokkan ke program.</p>
    </article>
    <article class="bk-stat bk-stat--amber">
        <span class="bk-stat__icon"><i class="bi bi-exclamation-circle" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Belum berisi</span>
        <p class="bk-stat__value">{{ $tanpaKursus }}</p>
        <p class="bk-stat__hint">Program yang belum punya satu kelas pun.</p>
    </article>
</div>

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Daftar program</h2>
            <p class="bk-panel__subtitle">Warna dipakai sebagai penanda program di jadwal dan papan informasi.</p>
        </div>
        <a href="{{ route('admin.program.create') }}" class="bk-btn bk-btn--pri bk-btn--sm">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah program
        </a>
    </div>

    @if ($program->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-diagram-3" aria-hidden="true"></i></span>
            <h3>Belum ada program</h3>
            <p>Tambahkan program pertama untuk mulai menyusun level dan kelas di bawahnya.</p>
            <a href="{{ route('admin.program.create') }}" class="bk-btn bk-btn--pri">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah program
            </a>
        </div>
    @else
        <table class="bk-table">
            <thead>
                <tr>
                    <th>Program</th>
                    <th class="r">Level</th>
                    <th class="r">Kelas</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($program as $item)
                    @php
                        $jumlahLevel = $item->kursuses->pluck('level_id')->filter()->unique()->count();
                        $jumlahKursus = $item->kursuses->count();
                    @endphp
                    <tr>
                        <td>
                            <span class="bk-who">
                                <span class="bk-who__ini" style="background:{{ $item->warna }};color:#fff">
                                    {{ mb_strtoupper(mb_substr($item->nama, 0, 2)) }}
                                </span>
                                <span>
                                    <b>{{ $item->nama }}</b>
                                    <small class="bk-muted bk-nowrap">{{ $item->warna }}</small>
                                </span>
                            </span>
                        </td>
                        <td class="r">
                            <span class="bk-tag bk-tag--diam">{{ $jumlahLevel }} level</span>
                        </td>
                        <td class="r">
                            <span class="bk-tag {{ $jumlahKursus ? 'bk-tag--info' : 'bk-tag--perlu' }}">
                                {{ $jumlahKursus }} kelas
                            </span>
                        </td>
                        <td class="r nw">
                            <a href="{{ route('admin.program.edit', $item) }}" class="bk-iconbtn" title="Ubah {{ $item->nama }}">
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                <span class="bk-sr">Ubah</span>
                            </a>
                            <form method="POST" action="{{ route('admin.program.destroy', $item) }}" style="display:inline"
                                  onsubmit="return confirm('Hapus program {{ $item->nama }}? Kelas di bawahnya ikut terpengaruh.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bk-iconbtn bk-iconbtn--danger" title="Hapus {{ $item->nama }}">
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
