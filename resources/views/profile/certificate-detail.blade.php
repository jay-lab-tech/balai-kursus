@extends('peserta::layouts.student')

@section('title', 'Rincian sertifikat')
@section('page-context', 'Peserta · Sertifikat')
@section('page-description', 'Data yang tercetak pada sertifikat dan tautan unduhannya.')

@section('content')

<a href="{{ route('profile.certificates') }}" class="bk-linkbtn">
    <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke daftar sertifikat
</a>

<div class="bk-duo">
    <section class="bk-panel">
        <div class="bk-panel__head">
            <div>
                <p class="bk-eyebrow">Sertifikat</p>
                <h1 class="bk-panel__title">{{ $certificate->certificate_name }}</h1>
            </div>
            <span class="bk-tag">Terbit</span>
        </div>

        <dl class="bk-facts">
            <div>
                <dt>Nomor sertifikat</dt>
                <dd><span class="bk-code">{{ $certificate->certificate_number ?: '-' }}</span></dd>
            </div>
            <div>
                <dt>Nomor seri</dt>
                <dd><span class="bk-code">{{ $certificate->serial_number ?: '-' }}</span></dd>
            </div>
            <div>
                <dt>Nama peserta</dt>
                <dd>{{ $certificate->participant_name_snapshot ?: auth()->user()->name }}</dd>
            </div>
            <div>
                <dt>Program</dt>
                <dd>{{ $certificate->program_name_snapshot ?? $certificate->course->program->nama ?? '-' }}</dd>
            </div>
            <div>
                <dt>Kelas</dt>
                <dd>{{ $certificate->course_name_snapshot ?? $certificate->course->nama ?? '-' }}</dd>
            </div>
            <div>
                <dt>Tanggal terbit</dt>
                <dd>{{ ($certificate->issued_date ?? $certificate->created_at)?->translatedFormat('j F Y') ?? '-' }}</dd>
            </div>
            @if ($certificate->hours_snapshot)
                <div><dt>Jumlah jam</dt><dd class="bk-num">{{ $certificate->hours_snapshot }} jam</dd></div>
            @endif
        </dl>

        <div class="bk-panel__foot">
            <span class="bk-muted">Berkas PDF dibuat ulang tiap kali diunduh.</span>
            <a href="{{ route('profile.certificate.download', $certificate->id) }}" class="bk-btn bk-btn--pri bk-btn--sm">
                <i class="bi bi-download" aria-hidden="true"></i> Unduh PDF
            </a>
        </div>
    </section>

    <div>
        <section class="bk-panel">
            <div class="bk-panel__head">
                <div>
                    <h2 class="bk-panel__title">Pratinjau</h2>
                    <p class="bk-panel__subtitle">Gambar hasil penerbitan, bila admin menyimpannya.</p>
                </div>
            </div>
            {{--
                Dulu <img> selalu dirender walau certificate_image_path kosong,
                sehingga src menjadi "/storage/" dan yang tampil ikon gambar rusak.
            --}}
            @if ($certificate->certificate_image_path)
                <div class="bk-panel__body">
                    <img src="{{ asset('storage/'.$certificate->certificate_image_path) }}"
                         alt="Pratinjau sertifikat {{ $certificate->certificate_name }}"
                         style="width:100%;border-radius:var(--bk-r-sm);border:1px solid var(--bk-sand-100)">
                </div>
            @else
                <div class="bk-empty">
                    <span class="bk-empty__icon"><i class="bi bi-image" aria-hidden="true"></i></span>
                    <h3>Tanpa gambar pratinjau</h3>
                    <p>Isi lengkapnya tetap bisa dilihat dengan mengunduh berkas PDF.</p>
                </div>
            @endif
        </section>

        @if ($certificate->serial_number)
            <section class="bk-panel">
                <div class="bk-panel__head">
                    <div>
                        <h2 class="bk-panel__title">Verifikasi</h2>
                        <p class="bk-panel__subtitle">Tautan ini bisa dibuka tanpa masuk akun.</p>
                    </div>
                </div>
                <div class="bk-panel__body">
                    <span class="bk-code">{{ route('certificate.verify', $certificate->serial_number) }}</span>
                    <p class="bk-hint">Bagikan kepada pihak yang ingin memastikan sertifikat Anda asli.</p>
                </div>
            </section>
        @endif
    </div>
</div>
@endsection
