@extends('layouts.admin')

@section('title', 'Level')
@section('page-context', 'Akademik')
@section('page-title', 'Level')
@section('page-description', 'Jenjang dan rentang nilai yang dipakai untuk memetakan hasil tes penempatan.')

@section('content')

@if (session('success'))
    <div class="bk-note bk-note--baik">
        <i class="bi bi-check-circle-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="bk-stats bk-stats--3">
    <article class="bk-stat">
        <span class="bk-stat__icon"><i class="bi bi-bar-chart-steps" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Total level</span>
        <p class="bk-stat__value">{{ $level->count() }}</p>
        <p class="bk-stat__hint">Jenjang yang tersedia untuk seluruh program.</p>
    </article>
    <article class="bk-stat bk-stat--amber">
        <span class="bk-stat__icon"><i class="bi bi-arrow-down-short" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Nilai terendah</span>
        <p class="bk-stat__value">{{ $level->min('nilai_min') ?? '-' }}</p>
        <p class="bk-stat__hint">Batas bawah terkecil dari seluruh rentang.</p>
    </article>
    <article class="bk-stat bk-stat--terra">
        <span class="bk-stat__icon"><i class="bi bi-arrow-up-short" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Nilai tertinggi</span>
        <p class="bk-stat__value">{{ $level->max('nilai_max') ?? '-' }}</p>
        <p class="bk-stat__hint">Batas atas terbesar dari seluruh rentang.</p>
    </article>
</div>

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Daftar level</h2>
            <p class="bk-panel__subtitle">Urutan menentukan tampilan jenjang; rentang nilai sebaiknya tidak tumpang tindih.</p>
        </div>
        <a href="{{ route('admin.level.create') }}" class="bk-btn bk-btn--pri bk-btn--sm">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah level
        </a>
    </div>

    @if ($level->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-bar-chart-steps" aria-hidden="true"></i></span>
            <h3>Belum ada level</h3>
            <p>Tambahkan level agar hasil tes penempatan bisa dipetakan ke jenjang yang tepat.</p>
            <a href="{{ route('admin.level.create') }}" class="bk-btn bk-btn--pri">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah level
            </a>
        </div>
    @else
        <table class="bk-table">
            <thead>
                <tr>
                    <th class="r">Urutan</th>
                    <th>Level</th>
                    <th class="r">Rentang nilai</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($level as $item)
                    <tr>
                        <td class="r">{{ $item->urutan }}</td>
                        <td>
                            <b>{{ $item->nama }}</b>
                            <small class="bk-muted" style="display:block">{{ $item->deskripsi ?: 'Belum ada deskripsi.' }}</small>
                        </td>
                        <td class="r"><span class="bk-tag bk-tag--info">{{ $item->rentang_nilai }}</span></td>
                        <td class="r nw">
                            <a href="{{ route('admin.level.edit', $item) }}" class="bk-iconbtn" title="Ubah {{ $item->nama }}">
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                <span class="bk-sr">Ubah</span>
                            </a>
                            <form method="POST" action="{{ route('admin.level.destroy', $item) }}" style="display:inline"
                                  onsubmit="return confirm('Hapus level {{ $item->nama }}?')">
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
