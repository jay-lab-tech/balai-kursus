@extends('peserta::layouts.student')

@section('title', 'Ruang belajar')
@section('page-context', 'Peserta · Beranda')
@section('page-description', 'Posisi setiap pendaftaran Anda dan langkah yang perlu dikerjakan berikutnya.')

@section('content')

@php
    $terbaru = $pendaftarans->first();
    $perluBayar = $pendaftarans->filter(fn ($p) => $p->canBePaid() && $p->sisa() > 0);
@endphp

<div class="bk-hello">
    <p class="bk-hello__kicker">Ruang belajar</p>
    <h2 class="bk-hello__title">Halo, {{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }}.</h2>
    <p class="bk-hello__lede">
        @if ($pendaftarans->isEmpty())
            Anda belum mendaftar program apa pun. Mulai dari daftar program untuk melihat pilihan yang tersedia.
        @else
            {{ $terbaru->petunjuk }}
        @endif
    </p>
    <div class="bk-hello__actions">
        <a href="{{ route('peserta.program.index') }}" class="bk-btn bk-btn--pri bk-btn--sm">
            <i class="bi bi-compass" aria-hidden="true"></i> Lihat program
        </a>
        @if ($pendaftarans->isNotEmpty())
            <a href="{{ route('peserta.pendaftaran.index') }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-clipboard-check" aria-hidden="true"></i> Status pendaftaran
            </a>
        @endif
    </div>
</div>

<div class="bk-stats">
    <article class="bk-stat">
        <span class="bk-stat__icon"><i class="bi bi-clipboard-check" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Pendaftaran</span>
        <p class="bk-stat__value">{{ $pendaftarans->count() }}</p>
        <p class="bk-stat__hint">total pengajuan program</p>
    </article>
    <article class="bk-stat bk-stat--amber">
        <span class="bk-stat__icon"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Menunggu tes</span>
        <p class="bk-stat__value">{{ $pendaftarans->where('status_pendaftaran', \App\Models\Pendaftaran::STATUS_MENUNGGU_TES)->count() }}</p>
        <p class="bk-stat__hint">hasil placement belum masuk</p>
    </article>
    <article class="bk-stat">
        <span class="bk-stat__icon"><i class="bi bi-door-open" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Sudah ditempatkan</span>
        <p class="bk-stat__value">{{ $pendaftarans->whereNotNull('kursus_id')->count() }}</p>
        <p class="bk-stat__hint">pendaftaran yang sudah dapat kelas</p>
    </article>
    <article class="bk-stat {{ $perluBayar->isNotEmpty() ? 'bk-stat--terra' : '' }}">
        <span class="bk-stat__icon"><i class="bi bi-receipt" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Perlu dibayar</span>
        <p class="bk-stat__value">{{ $perluBayar->count() }}</p>
        <p class="bk-stat__hint">
            @if ($perluBayar->isEmpty())
                tidak ada tagihan terbuka
            @else
                Rp {{ number_format($perluBayar->sum(fn ($p) => $p->sisa()), 0, ',', '.') }} belum lunas
            @endif
        </p>
    </article>
</div>

<div class="bk-duo">
    <section class="bk-panel">
        <div class="bk-panel__head">
            <div>
                <h2 class="bk-panel__title">Pendaftaran Anda</h2>
                <p class="bk-panel__subtitle">Lima pengajuan terbaru, diurutkan dari yang paling akhir dibuat.</p>
            </div>
            @if ($pendaftarans->count() > 5)
                <a href="{{ route('peserta.pendaftaran.index') }}" class="bk-linkbtn">
                    Lihat semua <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            @endif
        </div>

        @if ($pendaftarans->isEmpty())
            <div class="bk-empty">
                <span class="bk-empty__icon"><i class="bi bi-compass" aria-hidden="true"></i></span>
                <h3>Belum ada pendaftaran</h3>
                <p>Pilih satu program untuk memulai. Kelas ditentukan belakangan, setelah tes penempatan.</p>
                <a href="{{ route('peserta.program.index') }}" class="bk-btn bk-btn--pri bk-btn--sm">
                    <i class="bi bi-compass" aria-hidden="true"></i> Telusuri program
                </a>
            </div>
        @else
            <table class="bk-table is-padat">
                <thead>
                    <tr>
                        <th>Program</th>
                        <th>Level &amp; kelas</th>
                        <th class="nw">Status</th>
                        <th class="r nw">Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendaftarans->take(5) as $p)
                        <tr>
                            <td>
                                <b>{{ $p->program->nama ?? 'Program sudah dihapus' }}</b><br>
                                <span class="bk-muted bk-code">{{ $p->nomor }}</span>
                            </td>
                            <td>
                                {{ $p->level->nama ?? 'Menunggu hasil tes' }}<br>
                                <span class="bk-muted">{{ $p->kursus->nama ?? 'Kelas belum ditentukan' }}</span>
                            </td>
                            <td class="nw">
                                @php
                                    $rupa = match ($p->status_pendaftaran) {
                                        \App\Models\Pendaftaran::STATUS_AKTIF => '',
                                        \App\Models\Pendaftaran::STATUS_SELESAI => 'bk-tag--info',
                                        \App\Models\Pendaftaran::STATUS_DIBATALKAN => 'bk-tag--gagal',
                                        \App\Models\Pendaftaran::STATUS_MENUNGGU_PEMBAYARAN => 'bk-tag--perlu',
                                        default => 'bk-tag--diam',
                                    };
                                @endphp
                                <span class="bk-tag {{ $rupa }}">{{ $p->label_status }}</span>
                            </td>
                            <td class="r nw">
                                @if ($p->canBePaid() && $p->sisa() > 0)
                                    <span class="bk-num">Rp {{ number_format($p->sisa(), 0, ',', '.') }}</span><br>
                                    <a href="{{ route('peserta.pendaftaran.index') }}" class="bk-linkbtn">Bayar sekarang</a>
                                @elseif ($p->isLunas())
                                    <span class="bk-tag">Lunas</span>
                                @else
                                    <span class="bk-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>

    <div>
        @if ($terbaru)
            <section class="bk-panel">
                <div class="bk-panel__head">
                    <div>
                        <h2 class="bk-panel__title">Posisi Anda sekarang</h2>
                        <p class="bk-panel__subtitle">{{ $terbaru->program->nama ?? 'Pendaftaran terbaru' }}</p>
                    </div>
                </div>
                <div class="bk-panel__body">
                    @include('peserta::partials.alur', ['pendaftaran' => $terbaru])
                </div>
            </section>
        @endif

        <section class="bk-panel">
            <div class="bk-panel__head">
                <div>
                    <h2 class="bk-panel__title">Pintasan</h2>
                </div>
            </div>
            <a href="{{ route('peserta.kursus.saya') }}" class="bk-row">
                <i class="bi bi-journal-bookmark" aria-hidden="true"></i>
                <span class="bk-row__sp">Kelas saya</span>
                <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
            <a href="{{ route('peserta.riwayat.index') }}" class="bk-row">
                <i class="bi bi-receipt" aria-hidden="true"></i>
                <span class="bk-row__sp">Riwayat pembayaran</span>
                <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
            <a href="{{ route('profile.certificates') }}" class="bk-row">
                <i class="bi bi-patch-check" aria-hidden="true"></i>
                <span class="bk-row__sp">Sertifikat</span>
                <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
        </section>
    </div>
</div>
@endsection
