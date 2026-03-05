<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat {{ $certificate->no_sertifikat }}</title>
    <style>
        body {font-family: sans-serif; margin: 0; padding: 0;}
        .container {width: 100%; height: 100%; padding: 2rem; box-sizing: border-box;}
        h1 {text-align: center; font-size: 48px;}
        .details {margin-top: 3rem; text-align: center;}
        .footer {position: absolute; bottom: 2rem; width: 100%; text-align: center; font-size: 12px;}
        .qr {position: absolute; bottom: 2rem; right: 2rem;}
    </style>
</head>
<body>
    <div class="container">
        <h1>SERTIFIKAT</h1>
        <div class="details">
            <p>Diberikan kepada:</p>
            <h2>{{ $peserta->nama ?? 'Nama Peserta' }}</h2>
            <p>Untuk partisipasi dalam kursus:</p>
            <h3>{{ $kursus->judul ?? 'Judul Kursus' }}</h3>
            <p>Tanggal: {{ optional($certificate->issued_at)->format('d M Y') ?? now()->format('d M Y') }}</p>
            <p>No. {{ $certificate->no_sertifikat }}</p>
        </div>
        <div class="footer">
            <p>Balai Kursus &copy; {{ now()->year }}</p>
        </div>
        <div class="qr">
            <?php
                $url = route('certificate.verify', ['code' => $certificate->verification_code]);
                $qrImage = '';
                if (class_exists(\Endroid\QrCode\Writer\PngWriter::class)) {
                    $qrCode = \Endroid\QrCode\QrCode::create($url);
                    $writer = new \Endroid\QrCode\Writer\PngWriter();
                    $result = $writer->write($qrCode);
                    $qr = base64_encode($result->getString());
                    $qrImage = '<img src="data:image/png;base64,' . $qr . '" width="100" />';
                }
            ?>
            {!! $qrImage !!}
        </div>
    </div>
</body>
</html>
