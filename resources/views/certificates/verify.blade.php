<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Sertifikat - Balai Kursus</title>
    @php
        $manifest = @json_decode(@file_get_contents(public_path('build/manifest.json')), true) ?: [];
        $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
    @endphp
    @if($cssFile)
        <link rel="stylesheet" href="{{ asset('build/' . $cssFile) }}">
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4">
        <div class="w-full max-w-2xl">
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-flex items-center text-2xl font-bold text-white hover:text-yellow-400 transition-colors">
                    <i class="bi bi-mortarboard mr-2 text-yellow-400"></i>Balai Kursus
                </a>
                <p class="text-gray-400 mt-2">Verifikasi Keaslian Sertifikat</p>
            </div>

            @if($valid)
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-green-500/40 rounded-2xl shadow-2xl overflow-hidden">
                    <div class="bg-green-500/10 border-b border-green-500/30 px-8 py-6 flex items-center">
                        <div class="flex items-center justify-center h-14 w-14 rounded-full bg-green-500/20 mr-4">
                            <i class="bi bi-patch-check-fill text-3xl text-green-400"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-green-400">Sertifikat Terverifikasi</h1>
                            <p class="text-gray-400 text-sm">Sertifikat ini asli dan terdaftar dalam sistem.</p>
                        </div>
                    </div>
                    <div class="px-8 py-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 py-2 border-b border-gray-700/60">
                            <span class="text-gray-400">Nomor Sertifikat</span>
                            <span class="sm:col-span-2 font-semibold text-white">{{ $certificate->certificate_number ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 py-2 border-b border-gray-700/60">
                            <span class="text-gray-400">Nomor Seri</span>
                            <span class="sm:col-span-2 font-semibold text-white">{{ $certificate->serial_number ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 py-2 border-b border-gray-700/60">
                            <span class="text-gray-400">Nama Peserta</span>
                            <span class="sm:col-span-2 font-semibold text-white">{{ $certificate->participant_name_snapshot ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 py-2 border-b border-gray-700/60">
                            <span class="text-gray-400">Program</span>
                            <span class="sm:col-span-2 font-semibold text-white">{{ $certificate->program_name_snapshot ?? optional($certificate->course->program)->nama_program ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 py-2 border-b border-gray-700/60">
                            <span class="text-gray-400">Kursus</span>
                            <span class="sm:col-span-2 font-semibold text-white">{{ $certificate->course_name_snapshot ?? optional($certificate->course)->nama ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 py-2">
                            <span class="text-gray-400">Tanggal Terbit</span>
                            <span class="sm:col-span-2 font-semibold text-white">{{ optional($certificate->issued_date)->format('d F Y') ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            @elseif($certificate && $certificate->status === \App\Models\Certificate::STATUS_REVOKED)
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-yellow-500/40 rounded-2xl shadow-2xl overflow-hidden">
                    <div class="bg-yellow-500/10 border-b border-yellow-500/30 px-8 py-6 flex items-center">
                        <div class="flex items-center justify-center h-14 w-14 rounded-full bg-yellow-500/20 mr-4">
                            <i class="bi bi-exclamation-triangle-fill text-3xl text-yellow-400"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-yellow-400">Sertifikat Dicabut</h1>
                            <p class="text-gray-400 text-sm">Sertifikat dengan kode <span class="text-white font-mono">{{ $code }}</span> telah dicabut dan tidak lagi berlaku.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-red-500/40 rounded-2xl shadow-2xl overflow-hidden">
                    <div class="bg-red-500/10 border-b border-red-500/30 px-8 py-6 flex items-center">
                        <div class="flex items-center justify-center h-14 w-14 rounded-full bg-red-500/20 mr-4">
                            <i class="bi bi-x-octagon-fill text-3xl text-red-400"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-red-400">Sertifikat Tidak Ditemukan</h1>
                            <p class="text-gray-400 text-sm">Tidak ada sertifikat yang cocok dengan kode <span class="text-white font-mono">{{ $code }}</span>.</p>
                        </div>
                    </div>
                    <div class="px-8 py-6 text-gray-400 text-sm">
                        Pastikan nomor seri atau nomor sertifikat yang Anda masukkan sudah benar. Jika Anda yakin sertifikat ini asli, silakan hubungi pihak Balai Kursus.
                    </div>
                </div>
            @endif

            <div class="text-center mt-8">
                <a href="{{ url('/') }}" class="inline-flex items-center px-5 py-2 border border-gray-500 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="bi bi-house mr-2"></i>Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>
