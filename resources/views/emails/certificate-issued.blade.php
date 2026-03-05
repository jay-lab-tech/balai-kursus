<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; margin: 10px 0; }
        .footer { text-align: center; font-size: 12px; color: #666; margin-top: 20px; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        .btn-secondary { background: #6c757d; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Selamat! 🎉</h1>
            <p>Sertifikat Anda Telah Diterbitkan</p>
        </div>

        <div class="content">
            <p>Halo {{ $peserta_name }},</p>

            <p>Sertifikat Anda untuk kursus <strong>{{ $course_name }}</strong> telah diterbitkan dan siap untuk diunduh.</p>

            <div style="background: white; padding: 15px; border-left: 4px solid #007bff; margin: 15px 0;">
                <p><strong>Nomor Sertifikat:</strong> {{ $certificate_no }}</p>
                <p><strong>Tanggal Terbit:</strong> {{ $issued_date }}</p>
            </div>

            <p>Anda dapat mendownload sertifikat Anda dengan klik tombol di bawah:</p>

            <div style="text-align: center;">
                <a href="{{ $download_url }}" class="btn">📥 Unduh Sertifikat</a>
            </div>

            <p style="margin-top: 20px;">Atau gunakan link verifikasi ini untuk membagikan sertifikat Anda:</p>
            <p style="word-break: break-all; background: white; padding: 10px; border: 1px solid #ddd;">
                <a href="{{ $verify_url }}">{{ $verify_url }}</a>
            </p>

            <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">

            <p>Jika Anda memiliki pertanyaan, silakan hubungi kami.</p>
        </div>

        <div class="footer">
            <p>&copy; {{ now()->year }} Balai Kursus. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html>
