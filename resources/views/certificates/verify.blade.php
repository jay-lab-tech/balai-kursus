<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Sertifikat · Balai Kursus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite(['resources/css/app.css'])
</head>
<body class="bk-page">

<header class="bk-page__bar">
    <a href="{{ url('/') }}" class="bk-page__brand">
        <img src="{{ asset('images/logo.png') }}" alt="">
        <span>
            <b>Balai Kursus</b>
            <small>Verifikasi keaslian sertifikat</small>
        </span>
    </a>

    <a href="{{ url('/') }}" class="bk-btn bk-btn--sm">
        <i class="bi bi-house" aria-hidden="true"></i>
        <span class="bk-only-lebar">Beranda</span>
    </a>
</header>

<main class="bk-page__body bk-page__body--sempit">
    @if ($valid)
        <section class="bk-panel">
            <div class="bk-verdict bk-verdict--sah">
                <span class="bk-verdict__icon"><i class="bi bi-patch-check-fill" aria-hidden="true"></i></span>
                <div>
                    <h1>Sertifikat terverifikasi</h1>
                    <p>Sertifikat ini asli dan tercatat dalam sistem Balai Kursus.</p>
                </div>
            </div>

            <dl class="bk-facts">
                <div>
                    <dt>Nomor sertifikat</dt>
                    <dd class="bk-code">{{ $certificate->certificate_number ?? '-' }}</dd>
                </div>
                <div>
                    <dt>Nomor seri</dt>
                    <dd class="bk-code">{{ $certificate->serial_number ?? '-' }}</dd>
                </div>
                <div>
                    <dt>Nama peserta</dt>
                    <dd>{{ $certificate->participant_name_snapshot ?? '-' }}</dd>
                </div>
                <div>
                    <dt>Program</dt>
                    <dd>{{ $certificate->program_name_snapshot ?? optional($certificate->course->program)->nama_program ?? '-' }}</dd>
                </div>
                <div>
                    <dt>Kursus</dt>
                    <dd>{{ $certificate->course_name_snapshot ?? optional($certificate->course)->nama ?? '-' }}</dd>
                </div>
                <div>
                    <dt>Tanggal terbit</dt>
                    <dd>{{ optional($certificate->issued_date)->translatedFormat('j F Y') ?? '-' }}</dd>
                </div>
            </dl>
        </section>
    @elseif ($certificate && $certificate->status === \App\Models\Certificate::STATUS_REVOKED)
        <section class="bk-panel">
            <div class="bk-verdict bk-verdict--tarik">
                <span class="bk-verdict__icon"><i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i></span>
                <div>
                    <h1>Sertifikat dicabut</h1>
                    <p>Sertifikat dengan kode <span class="bk-code">{{ $code }}</span> sudah dicabut dan tidak lagi berlaku.</p>
                </div>
            </div>
            <div class="bk-panel__body bk-muted">
                Bila Anda memerlukan keterangan lebih lanjut mengenai pencabutan ini, hubungi bagian administrasi Balai Kursus.
            </div>
        </section>
    @else
        <section class="bk-panel">
            <div class="bk-verdict bk-verdict--nihil">
                <span class="bk-verdict__icon"><i class="bi bi-x-octagon-fill" aria-hidden="true"></i></span>
                <div>
                    <h1>Sertifikat tidak ditemukan</h1>
                    <p>Tidak ada sertifikat yang cocok dengan kode <span class="bk-code">{{ $code }}</span>.</p>
                </div>
            </div>
            <div class="bk-panel__body bk-muted">
                Pastikan nomor seri atau nomor sertifikat yang Anda masukkan sudah benar. Jika Anda yakin sertifikat ini asli, silakan hubungi pihak Balai Kursus.
            </div>
        </section>
    @endif
</main>

</body>
</html>
