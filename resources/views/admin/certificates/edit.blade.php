@extends('layouts.admin')

@section('title', 'Ubah Sertifikat')
@section('page-context', 'Peserta · Sertifikat')
@section('page-title', 'Ubah sertifikat ' . ($certificate->participant_name_snapshot ?: 'peserta'))
@section('page-description', ($certificate->certificate_number ?: 'Nomor belum terbentuk') . ' · ' . ($certificate->course_name_snapshot ?: 'Kelas belum tercatat'))

@section('content')

@php
    [$rupa, $labelStatus] = match ($certificate->status) {
        \App\Models\Certificate::STATUS_PUBLISHED => ['', 'Sudah terbit'],
        \App\Models\Certificate::STATUS_REVOKED => ['bk-tag--gagal', 'Dicabut'],
        default => ['bk-tag--jalan', 'Masih draft'],
    };
@endphp

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Keadaan dokumen</h2>
            <p class="bk-panel__subtitle">Nomor sertifikat terbentuk sekali saat draft dibuat dan hanya dihitung ulang kalau tanggal terbitnya berubah.</p>
        </div>
        <span class="bk-tag {{ $rupa }}">{{ $labelStatus }}</span>
    </div>

    <div class="bk-panel__body">
        <dl class="bk-kv">
            <div>
                <dt>Nomor sertifikat</dt>
                <dd>{{ $certificate->certificate_number ?: '—' }}</dd>
            </div>
            <div>
                <dt>Nomor urut</dt>
                <dd>{{ $certificate->serial_number ?: '—' }}</dd>
            </div>
            <div>
                <dt>Template tersimpan</dt>
                <dd style="font-size:.92rem">{{ $certificate->template->name ?? 'Terputus' }}</dd>
            </div>
        </dl>
    </div>
</section>

@include('admin.certificates.partials.form', [
    'certificate' => $certificate,
    'action' => route('admin.certificates.update', $certificate->id),
    'method' => 'PUT',
    'submitLabel' => 'Simpan perubahan',
])

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Hapus sertifikat</h2>
            <p class="bk-panel__subtitle">
                @if ($certificate->status === \App\Models\Certificate::STATUS_PUBLISHED)
                    Sertifikat ini sudah terbit dan nomornya sudah beredar. Sebaiknya dicabut saja, bukan dihapus.
                @else
                    Menghapus draft ini tidak bisa dibatalkan. Nomor urutnya tidak dipakai ulang.
                @endif
            </p>
        </div>
        <form method="POST" action="{{ route('admin.certificates.destroy', $certificate->id) }}"
              onsubmit="return confirm('Hapus sertifikat ini secara permanen?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bk-btn bk-btn--sm bk-btn--danger">
                <i class="bi bi-trash3" aria-hidden="true"></i> Hapus sertifikat
            </button>
        </form>
    </div>
</section>
@endsection

@section('scripts')
@include('admin.certificates.partials.peserta-loader')
@endsection
