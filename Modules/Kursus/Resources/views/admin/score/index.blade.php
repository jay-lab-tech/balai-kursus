@extends('layouts.admin')

@section('title', 'Tes Penempatan')
@section('page-context', 'Peserta · Nilai')
@section('page-title', 'Tes penempatan')
@section('page-description', 'Antrian pendaftaran program beserta nilai tesnya. Menyimpan nilai langsung menempatkan peserta ke level dan kelas yang sesuai.')

@section('content')

@if (session('success'))
    <div class="bk-note bk-note--baik">
        <i class="bi bi-check-circle-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@php
    $cari = (string) request('q', '');
    $saringStatus = (string) request('status', '');
    $sudahDinilai = $pendaftarans->filter(fn ($item) => $item->placementScore)->count();
    $daftarStatus = [
        'menunggu_tes' => 'Menunggu tes',
        'menunggu_penempatan' => 'Menunggu penempatan',
        'menunggu_pembayaran' => 'Menunggu pembayaran',
        'aktif' => 'Aktif',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
    ];
@endphp

<div class="bk-stats bk-stats--3">
    <article class="bk-stat">
        <span class="bk-stat__icon"><i class="bi bi-clipboard-data" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Pendaftaran program</span>
        <p class="bk-stat__value">{{ $pendaftarans->total() }}</p>
        <p class="bk-stat__hint">Baris yang cocok dengan pencarian dan saringan saat ini.</p>
    </article>
    <article class="bk-stat bk-stat--amber">
        <span class="bk-stat__icon"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Belum dinilai di halaman ini</span>
        <p class="bk-stat__value">{{ $pendaftarans->count() - $sudahDinilai }}</p>
        <p class="bk-stat__hint">Peserta yang masih menunggu hasil tes diinput.</p>
    </article>
    <article class="bk-stat">
        <span class="bk-stat__icon"><i class="bi bi-check2-square" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Sudah dinilai di halaman ini</span>
        <p class="bk-stat__value">{{ $sudahDinilai }}</p>
        <p class="bk-stat__hint">Nilai tersimpan dan penempatan sudah dijalankan.</p>
    </article>
</div>

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Antrian tes penempatan</h2>
            <p class="bk-panel__subtitle">Halaman {{ $pendaftarans->currentPage() }} dari {{ max($pendaftarans->lastPage(), 1) }}.</p>
        </div>

        <div class="bk-row">
            <a href="{{ route('admin.score.export') }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-download" aria-hidden="true"></i> Unduh
            </a>
            <a href="{{ route('admin.score.create') }}" class="bk-btn bk-btn--pri bk-btn--sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Input hasil tes
            </a>
        </div>
    </div>

    {{-- Saringan diberi barisnya sendiri: dua kolom pencarian tidak muat
         berdampingan dengan tombol aksi di kepala panel. --}}
    <div class="bk-panel__foot" style="border-top:0;border-bottom:1px solid var(--bk-sand-100)">
        <form method="GET" action="{{ route('admin.score.index') }}" class="bk-row">
            <div class="bk-pillfield bk-pillfield--cari">
                <i class="bi bi-search" aria-hidden="true"></i>
                <label for="q" class="bk-sr">Cari pendaftaran</label>
                <input type="search" id="q" name="q" value="{{ $cari }}" placeholder="Nama, email, nomor, program">
            </div>
            <label for="status" class="bk-sr">Saring status</label>
            <select id="status" name="status" class="bk-pillselect" onchange="this.form.submit()">
                <option value="">Semua status</option>
                @foreach ($daftarStatus as $nilai => $label)
                    <option value="{{ $nilai }}" @selected($saringStatus === $nilai)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="bk-btn bk-btn--sm">
                <i class="bi bi-funnel" aria-hidden="true"></i> Terapkan
            </button>

            @if ($cari !== '' || $saringStatus !== '')
                <a href="{{ route('admin.score.index') }}" class="bk-chip">
                    Hapus saringan
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                </a>
            @endif
        </form>
    </div>

    @if ($pendaftarans->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-clipboard-data" aria-hidden="true"></i></span>
            <h3>{{ $cari !== '' || $saringStatus !== '' ? 'Tidak ada yang cocok' : 'Belum ada pendaftaran program' }}</h3>
            <p>
                {{ $cari !== '' || $saringStatus !== ''
                    ? 'Coba kata kunci atau status lain, atau hapus saringan untuk melihat seluruh antrian.'
                    : 'Antrian tes muncul setelah peserta mendaftar ke salah satu program.' }}
            </p>
            @if ($cari !== '' || $saringStatus !== '')
                <a href="{{ route('admin.score.index') }}" class="bk-btn bk-btn--pri">
                    <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> Tampilkan semua
                </a>
            @endif
        </div>
    @else
        <table class="bk-table">
            <thead>
                <tr>
                    <th>Peserta</th>
                    <th>Program</th>
                    <th class="r nw">Nilai tes</th>
                    <th>Penempatan</th>
                    <th>Status</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pendaftarans as $pendaftaran)
                    <tr>
                        <td>
                            <b>{{ $pendaftaran->peserta->user->name ?? '—' }}</b><br>
                            <span class="bk-muted">{{ $pendaftaran->nomor ?: ($pendaftaran->participant_email_snapshot ?? $pendaftaran->peserta->user->email ?? '—') }}</span>
                        </td>
                        <td>{{ $pendaftaran->program->nama ?? '—' }}</td>
                        <td class="r nw">
                            @if ($pendaftaran->placementScore)
                                <b>{{ $pendaftaran->placementScore->final_score }}</b>
                            @else
                                <span class="bk-muted">Belum diinput</span>
                            @endif
                        </td>
                        <td>
                            {{ $pendaftaran->level->nama ?? 'Belum ada level' }}<br>
                            <span class="bk-muted">{{ $pendaftaran->kursus->nama ?? 'Belum ditempatkan' }}</span>
                        </td>
                        <td>
                            @php
                                // Pil tanpa pengubah sudah berwarna sage — dipakai untuk keadaan beres.
                                $rupa = match ($pendaftaran->status_pendaftaran) {
                                    'aktif', 'selesai' => '',
                                    'menunggu_pembayaran' => 'bk-tag--perlu',
                                    'menunggu_tes', 'menunggu_penempatan' => 'bk-tag--jalan',
                                    default => 'bk-tag--diam',
                                };
                            @endphp
                            <span class="bk-tag {{ $rupa }}">{{ $daftarStatus[$pendaftaran->status_pendaftaran] ?? $pendaftaran->status_pendaftaran }}</span>
                        </td>
                        <td class="r nw">
                            @if ($pendaftaran->placementScore)
                                <a href="{{ route('admin.score.show', $pendaftaran->placementScore) }}" class="bk-iconbtn" title="Detail nilai">
                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                    <span class="bk-sr">Detail nilai</span>
                                </a>
                                <a href="{{ route('admin.score.edit', $pendaftaran->placementScore) }}" class="bk-iconbtn" title="Ubah nilai">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                    <span class="bk-sr">Ubah nilai</span>
                                </a>
                            @else
                                <a href="{{ route('admin.score.create', ['pendaftaran_id' => $pendaftaran->id]) }}" class="bk-btn bk-btn--sm">
                                    <i class="bi bi-pencil-square" aria-hidden="true"></i> Input tes
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($pendaftarans->hasPages())
            <div class="bk-panel__foot">{{ $pendaftarans->links() }}</div>
        @endif
    @endif
</section>
@endsection
