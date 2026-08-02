@extends('peserta::layouts.student')

@section('title', 'Sertifikat saya')
@section('page-context', 'Peserta · Sertifikat')
@section('page-description', 'Sertifikat yang sudah diterbitkan admin untuk kelas yang Anda selesaikan.')

@section('content')

<div class="bk-panel__head" style="border:0;padding-left:0;padding-right:0">
    <div>
        <h1 class="bk-panel__title">Sertifikat saya</h1>
        <p class="bk-panel__subtitle">Hanya sertifikat berstatus terbit yang muncul di sini; yang masih draf belum bisa diunduh.</p>
    </div>
    <a href="{{ route('peserta.kursus.saya') }}" class="bk-btn bk-btn--sm">
        <i class="bi bi-journal-bookmark" aria-hidden="true"></i> Kelas saya
    </a>
</div>

@if ($certificates->isEmpty())
    <section class="bk-panel">
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-patch-check" aria-hidden="true"></i></span>
            <h3>Belum ada sertifikat</h3>
            <p>Sertifikat terbit setelah kelas Anda dinyatakan selesai dan admin menerbitkannya.</p>
            <a href="{{ route('peserta.kursus.saya') }}" class="bk-btn bk-btn--pri bk-btn--sm">
                <i class="bi bi-arrow-right" aria-hidden="true"></i> Lihat kelas saya
            </a>
        </div>
    </section>
@else
    <section class="bk-panel">
        <div class="bk-panel__head">
            <div>
                <h2 class="bk-panel__title">{{ $certificates->count() }} sertifikat terbit</h2>
                <p class="bk-panel__subtitle">Nomor seri di bawah bisa dipakai siapa pun untuk memeriksa keaslian lewat halaman verifikasi.</p>
            </div>
        </div>

        <table class="bk-table">
            <thead>
                <tr>
                    <th>Sertifikat</th>
                    <th>Kelas</th>
                    <th class="nw">Terbit</th>
                    <th class="r nw">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($certificates as $certificate)
                    <tr>
                        <td>
                            <b>{{ $certificate->certificate_name }}</b>
                            @if ($certificate->serial_number)
                                <br><span class="bk-code">{{ $certificate->serial_number }}</span>
                            @endif
                        </td>
                        <td>
                            {{ $certificate->course_name_snapshot ?? $certificate->course->nama ?? 'Kelas sudah dihapus' }}
                            @if ($nama = $certificate->program_name_snapshot ?? $certificate->course->program->nama ?? null)
                                <br><span class="bk-muted">{{ $nama }}</span>
                            @endif
                        </td>
                        <td class="nw">
                            {{-- issued_date diisi saat penerbitan; created_at hanya cadangan untuk data lama. --}}
                            {{ ($certificate->issued_date ?? $certificate->created_at)?->translatedFormat('j M Y') ?? '-' }}
                        </td>
                        <td class="r nw">
                            <a href="{{ route('profile.certificate.detail', $certificate->id) }}" class="bk-btn bk-btn--sm">
                                <i class="bi bi-eye" aria-hidden="true"></i> Rincian
                            </a>
                            <a href="{{ route('profile.certificate.download', $certificate->id) }}" class="bk-btn bk-btn--pri bk-btn--sm">
                                <i class="bi bi-download" aria-hidden="true"></i> PDF
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endif
@endsection
