@php
    $backgroundPath = $template?->background_image_path ? public_path($template->background_image_path) : null;
    $logoPath = $template?->header_logo_path ? public_path($template->header_logo_path) : null;
    $signaturePath = $template?->signature_image_path ? public_path($template->signature_image_path) : null;
    $stampPath = $template?->stamp_image_path ? public_path($template->stamp_image_path) : null;
    $participantName = $certificate->participant_name_snapshot ?: ($participant->user->name ?? '-');
    $programName = $certificate->program_name_snapshot ?: ($course->program->nama ?? $course->nama ?? '-');
    $hours = $certificate->hours_snapshot ?: ($course->jam_pelajaran ?? null);
    $startDate = $certificate->start_date_snapshot ?: ($course->tanggal_mulai ?? null);
    $endDate = $certificate->end_date_snapshot ?: ($course->tanggal_selesai ?? null);
    $issuedDate = $certificate->issued_date;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $certificate->certificate_name }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
        }

        .sheet {
            position: relative;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
            background: #fff;
        }

        .background {
            position: absolute;
            inset: 0;
            width: 297mm;
            height: 210mm;
        }

        .number-block {
            position: absolute;
            top: 15mm;
            right: 19mm;
            text-align: right;
            font-size: 4.8mm;
            line-height: 1.22;
            z-index: 2;
            color: #111827;
        }

        .number-block strong {
            font-weight: 700;
        }

        .name-block {
            position: absolute;
            text-align: center;
            left: 34mm;
            right: 34mm;
            top: 87mm;
            z-index: 2;
        }

        .recipient {
            margin: 0;
            font-family: DejaVu Serif, serif;
            font-size: 13.4mm;
            font-weight: 700;
            line-height: 1.08;
            color: #050505;
        }

        .description-block {
            position: absolute;
            top: 110mm;
            left: 28mm;
            right: 28mm;
            text-align: center;
            z-index: 2;
        }

        .description {
            margin: 0 auto;
            max-width: 200mm;
            font-family: DejaVu Serif, serif;
            font-size: 5.75mm;
            line-height: 1.28;
            color: #111111;
        }
    </style>
</head>
<body>
    <div class="sheet">
        @if($backgroundPath && file_exists($backgroundPath))
            <img src="{{ $backgroundPath }}" alt="Background Sertifikat" class="background">
        @endif

        <div class="number-block">
            <div>Sertifikat: {{ $certificate->certificate_number ?? '-' }}</div>
            <div>Nomor: <strong>{{ $certificate->serial_number ?? '-' }}</strong></div>
        </div>

        <div class="name-block">
            <p class="recipient">{{ $participantName }}</p>
        </div>

        <div class="description-block">
            <p class="description">
                yang telah menyelesaikan Program {{ $programName }}{{ $hours ? ' ' . $hours . ' Jam Pelajaran' : '' }} yang
                diselenggarakan di Balai Bahasa UPI pada
                {{ $startDate ? \Illuminate\Support\Carbon::parse($startDate)->translatedFormat('d F Y') : '-' }}
                sampai dengan
                {{ $endDate ? \Illuminate\Support\Carbon::parse($endDate)->translatedFormat('d F Y') : '-' }}.
            </p>
        </div>
    </div>
</body>
</html>
