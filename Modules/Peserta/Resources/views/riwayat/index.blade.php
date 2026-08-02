@extends('peserta::layouts.student')

@section('title', 'Riwayat pembayaran')
@section('page-context', 'Peserta · Keuangan')
@section('page-description', 'Semua transaksi pembayaran kelas yang tercatat di akun Anda.')

@section('content')

@php
    $berhasil = $payments->where('status', 'success');
@endphp

<div class="bk-panel__head" style="border:0;padding-left:0;padding-right:0">
    <div>
        <h1 class="bk-panel__title">Riwayat pembayaran</h1>
        <p class="bk-panel__subtitle">Tercatat sejak akun Anda dibuat, terbaru di atas.</p>
    </div>
    <a href="{{ route('peserta.pendaftaran.index') }}" class="bk-btn bk-btn--sm">
        <i class="bi bi-clipboard-check" aria-hidden="true"></i> Pendaftaran saya
    </a>
</div>

@if ($payments->isEmpty())
    <section class="bk-panel">
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-receipt" aria-hidden="true"></i></span>
            <h3>Belum ada transaksi</h3>
            <p>Transaksi muncul di sini setelah Anda memulai pembayaran kelas.</p>
            <a href="{{ route('peserta.pendaftaran.index') }}" class="bk-btn bk-btn--pri bk-btn--sm">
                <i class="bi bi-credit-card" aria-hidden="true"></i> Lihat tagihan
            </a>
        </div>
    </section>
@else
    <div class="bk-stats bk-stats--3">
        <article class="bk-stat">
            <span class="bk-stat__icon"><i class="bi bi-list-ul" aria-hidden="true"></i></span>
            <span class="bk-stat__label">Transaksi</span>
            <p class="bk-stat__value">{{ $payments->count() }}</p>
            <p class="bk-stat__hint">seluruh percobaan pembayaran</p>
        </article>
        <article class="bk-stat">
            <span class="bk-stat__icon"><i class="bi bi-check2-circle" aria-hidden="true"></i></span>
            <span class="bk-stat__label">Berhasil</span>
            <p class="bk-stat__value">{{ $berhasil->count() }}</p>
            <p class="bk-stat__hint">sudah terkonfirmasi Midtrans</p>
        </article>
        <article class="bk-stat bk-stat--amber">
            <span class="bk-stat__icon"><i class="bi bi-cash-stack" aria-hidden="true"></i></span>
            <span class="bk-stat__label">Nominal berhasil</span>
            <p class="bk-stat__value">Rp {{ number_format($berhasil->sum('amount'), 0, ',', '.') }}</p>
            <p class="bk-stat__hint">total yang sudah masuk</p>
        </article>
    </div>

    <section class="bk-panel">
        <div class="bk-panel__head">
            <div>
                <h2 class="bk-panel__title">Buku transaksi</h2>
                <p class="bk-panel__subtitle">Order ID dipakai bila Anda perlu menanyakan satu transaksi ke admin.</p>
            </div>
        </div>

        <table class="bk-table">
            <thead>
                <tr>
                    <th class="nw">Tanggal</th>
                    <th>Program &amp; kelas</th>
                    <th class="r nw">Nominal</th>
                    <th class="nw">Status</th>
                    <th class="nw">Order ID</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr>
                        <td class="nw">{{ optional($payment->created_at)->translatedFormat('j M Y, H:i') ?? '—' }}</td>
                        <td>
                            <b>{{ $payment->pendaftaran->program->nama ?? 'Program sudah dihapus' }}</b><br>
                            <span class="bk-muted">
                                {{ $payment->pendaftaran->kursus->nama ?? 'Belum ada kelas saat transaksi dibuat' }}
                            </span>
                        </td>
                        <td class="r nw bk-num">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td class="nw">
                            <span class="bk-tag {{ match ($payment->status) {
                                'success' => '',
                                'pending' => 'bk-tag--jalan',
                                'failed' => 'bk-tag--gagal',
                                default => 'bk-tag--diam',
                            } }}">{{ $payment->label_status }}</span>
                        </td>
                        <td class="nw"><span class="bk-code">{{ $payment->order_id }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endif
@endsection
