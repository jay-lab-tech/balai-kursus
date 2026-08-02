@extends('layouts.admin')

@section('title', 'Sertifikat')
@section('page-context', 'Peserta · Sertifikat')
@section('page-title', 'Sertifikat peserta')
@section('page-description', 'Sertifikat dibuat sebagai draft dulu, diperiksa, lalu diterbitkan. Setelah terbit, peserta bisa mengunduhnya sendiri dari akunnya.')

@section('content')

@if (session('success'))
    <div class="bk-note bk-note--baik">
        <i class="bi bi-check-circle-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="bk-note bk-note--buruk">
        <i class="bi bi-exclamation-octagon-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

@php
    $jumlahDraft = $jumlahStatus[\App\Models\Certificate::STATUS_DRAFT] ?? 0;
    $jumlahTerbit = $jumlahStatus[\App\Models\Certificate::STATUS_PUBLISHED] ?? 0;
    $jumlahDicabut = $jumlahStatus[\App\Models\Certificate::STATUS_REVOKED] ?? 0;
@endphp

@unless ($activeTemplate)
    <div class="bk-note bk-note--perlu">
        <i class="bi bi-exclamation-triangle-fill bk-note__icon" aria-hidden="true"></i>
        <div>
            <b>Belum ada template aktif</b>
            <p style="margin:.2rem 0 0">Sertifikat baru tidak bisa dibuat sampai satu template ditandai aktif.
                <a href="{{ route('admin.templates.index') }}" class="bk-linkbtn">Atur template sertifikat</a>
            </p>
        </div>
    </div>
@endunless

{{-- Kartu angka hanya untuk yang memang angka. Nama template aktif ikut
     disebut di anak judul panel di bawah, bukan dipaksa jadi kartu keempat
     yang isinya kalimat. --}}
<div class="bk-stats bk-stats--3">
    <article class="bk-stat">
        <span class="bk-stat__icon"><i class="bi bi-patch-check" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Sudah terbit</span>
        <p class="bk-stat__value">{{ $jumlahTerbit }}</p>
        <p class="bk-stat__hint">Bisa diunduh peserta dari akunnya.</p>
    </article>

    <article class="bk-stat bk-stat--amber">
        <span class="bk-stat__icon"><i class="bi bi-file-earmark-text" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Masih draft</span>
        <p class="bk-stat__value">{{ $jumlahDraft }}</p>
        <p class="bk-stat__hint">Menunggu diperiksa lalu diterbitkan.</p>
    </article>

    <article class="bk-stat bk-stat--terra">
        <span class="bk-stat__icon"><i class="bi bi-x-octagon" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Dicabut</span>
        <p class="bk-stat__value">{{ $jumlahDicabut }}</p>
        <p class="bk-stat__hint">Ditarik kembali dan tidak bisa diunduh.</p>
    </article>

</div>

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Daftar sertifikat</h2>
            <p class="bk-panel__subtitle">
                {{ $certificates->total() }} sertifikat tercatat, halaman {{ $certificates->currentPage() }} dari {{ max($certificates->lastPage(), 1) }}.
                Template yang dipakai sekarang: <b>{{ $activeTemplate?->name ?? 'belum ada' }}</b>.
            </p>
        </div>

        <div class="bk-row">
            <a href="{{ route('admin.templates.index') }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-card-image" aria-hidden="true"></i> Template
            </a>
            <a href="{{ route('admin.certificates.batch.create') }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-collection" aria-hidden="true"></i> Buat sekelas
            </a>
            <a href="{{ route('admin.certificates.create') }}" class="bk-btn bk-btn--pri bk-btn--sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Draft baru
            </a>
        </div>
    </div>

    @if ($certificates->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-patch-check" aria-hidden="true"></i></span>
            <h3>Belum ada sertifikat</h3>
            <p>Buat draft untuk satu peserta, atau sekaligus untuk seluruh peserta di satu kelas yang sudah selesai.</p>
            <a href="{{ route('admin.certificates.batch.create') }}" class="bk-btn bk-btn--pri">
                <i class="bi bi-collection" aria-hidden="true"></i> Buat sekelas
            </a>
        </div>
    @else
        <table class="bk-table is-padat">
            <thead>
                <tr>
                    <th>Peserta</th>
                    <th>Kelas</th>
                    <th>Nomor</th>
                    <th class="nw">Terbit</th>
                    <th>Status</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($certificates as $certificate)
                    @php
                        // Pil tanpa pengubah sudah sage — dipakai untuk keadaan beres.
                        [$rupa, $label] = match ($certificate->status) {
                            \App\Models\Certificate::STATUS_PUBLISHED => ['', 'Terbit'],
                            \App\Models\Certificate::STATUS_REVOKED => ['bk-tag--gagal', 'Dicabut'],
                            default => ['bk-tag--jalan', 'Draft'],
                        };
                    @endphp
                    <tr>
                        <td>
                            <b>{{ $certificate->participant_name_snapshot ?: ($certificate->participant->user->name ?? '—') }}</b><br>
                            <span class="bk-muted">{{ $certificate->participant->nomor_peserta ?? $certificate->participant->user->email ?? '—' }}</span>
                        </td>
                        <td>
                            {{ $certificate->course_name_snapshot ?: ($certificate->course->nama ?? '—') }}<br>
                            <span class="bk-muted">
                                {{ $certificate->program_name_snapshot ?: '—' }}
                                @if ($certificate->hours_snapshot)
                                    · {{ $certificate->hours_snapshot }} jam pelajaran
                                @endif
                            </span>
                        </td>
                        <td>
                            <span class="bk-code">{{ $certificate->certificate_number ?: '—' }}</span><br>
                            <span class="bk-muted">Template: {{ $certificate->template->name ?? 'Terputus' }}</span>
                        </td>
                        <td class="nw">{{ $certificate->issued_date?->translatedFormat('j M Y') ?? '—' }}</td>
                        <td><span class="bk-tag {{ $rupa }}">{{ $label }}</span></td>
                        <td class="r nw">
                            <a href="{{ route('admin.certificates.preview', $certificate->id) }}" target="_blank" rel="noopener"
                               class="bk-iconbtn" title="Pratinjau PDF">
                                <i class="bi bi-file-earmark-pdf" aria-hidden="true"></i>
                                <span class="bk-sr">Pratinjau PDF sertifikat</span>
                            </a>
                            <a href="{{ route('admin.certificates.edit', $certificate->id) }}" class="bk-iconbtn" title="Ubah">
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                <span class="bk-sr">Ubah sertifikat</span>
                            </a>

                            @if ($certificate->status === \App\Models\Certificate::STATUS_DRAFT)
                                <form method="POST" action="{{ route('admin.certificates.publish', $certificate->id) }}" style="display:inline">
                                    @csrf
                                    <button type="submit" class="bk-btn bk-btn--sm bk-btn--pri">
                                        <i class="bi bi-send" aria-hidden="true"></i> Terbitkan
                                    </button>
                                </form>
                            @elseif ($certificate->status === \App\Models\Certificate::STATUS_PUBLISHED)
                                <form method="POST" action="{{ route('admin.certificates.revoke', $certificate->id) }}" style="display:inline"
                                      onsubmit="return confirm('Cabut sertifikat ini? Peserta tidak bisa lagi mengunduhnya.')">
                                    @csrf
                                    <button type="submit" class="bk-btn bk-btn--sm">
                                        <i class="bi bi-x-octagon" aria-hidden="true"></i> Cabut
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.certificates.restore-draft', $certificate->id) }}" style="display:inline">
                                    @csrf
                                    <button type="submit" class="bk-btn bk-btn--sm">
                                        <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> Jadikan draft
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($certificates->hasPages())
            <div class="bk-panel__foot">{{ $certificates->links() }}</div>
        @endif
    @endif
</section>
@endsection
