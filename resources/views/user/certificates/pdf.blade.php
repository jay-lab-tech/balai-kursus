<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 40px; }
        .certificate { border: 3px solid #gold; padding: 40px; max-width: 800px; margin: 0 auto; }
        h1 { color: #333; }
        .course { font-size: 18px; margin: 20px 0; }
        .date { font-size: 14px; color: #666; }
        .footer { margin-top: 40px; border-top: 1px solid #ddd; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="certificate">
        <img src="{{ public_path('storage/' . $certificate->certificate_image_path) }}" width="600">
    </div>
    <div style="page-break-before: always;"></div>
    <div class="certificate">
        <h2>Terima Kasih!</h2>
        <p>Selamat kepada <b>{{ $participant->user->name ?? '-' }}</b> atas pencapaian dan dedikasi dalam menyelesaikan kursus <b>{{ $course->nama }}</b>.</p>
        <p>Semoga ilmu yang didapatkan bermanfaat dan menjadi bekal untuk masa depan.</p>
        <div class="footer">
            <p>Salam sukses dari Balai Kursus</p>
        </div>
    </div>
</body>
</html>
