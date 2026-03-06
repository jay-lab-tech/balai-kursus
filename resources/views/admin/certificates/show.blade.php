@extends('layouts.admin')

@section('title', 'Detail Sertifikat')

@section('page-title', 'Detail Sertifikat')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">{{ $certificate->no_sertifikat }}</h1>
        <a href="{{ route('admin.certificates.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Status Messages -->
    @if ($message = Session::get('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ $message }}</span>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ $message }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Info Card -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if ($certificate->status === 'generated')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Di-Generate
                                    </span>
                                @elseif ($certificate->status === 'applied')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Diterbitkan
                                    </span>
                                @elseif ($certificate->status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Ditolak
                                    </span>
                                @elseif ($certificate->status === 'revoked')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Dicabut
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $certificate->status }}
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Peserta</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="font-medium">{{ $certificate->peserta->nama ?? '-' }}</div>
                                <div class="text-gray-500">{{ $certificate->peserta->nomor_peserta ?? '-' }}</div>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Program / Kursus</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ optional($certificate->kursus->program)->nama ? $certificate->kursus->program->nama . ' - ' : '' }}{{ $certificate->kursus->nama ?? $certificate->kursus->judul ?? '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Terbit</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ optional($certificate->issued_at)->format('d M Y H:i') ?? '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Di-Generate</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ optional($certificate->generated_at)->format('d M Y H:i') ?? '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Kode Verifikasi</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $certificate->verification_code }}</code>
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Masa Berlaku</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if ($certificate->expires_at)
                                    <span class="font-medium">Sampai {{ $certificate->expires_at->format('d M Y') }}</span>
                                    @if ($certificate->getExpiryStatus() === 'expired')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            Kadaluarsa
                                        </span>
                                    @elseif ($certificate->getExpiryStatus() === 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                            Aktif
                                        </span>
                                        @if ($certificate->daysUntilExpiry() <= 7)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">
                                                Akan Berakhir
                                            </span>
                                        @endif
                                    @endif
                                @else
                                    <span class="text-gray-500">Selamanya</span>
                                @endif
                            </dd>
                        </div>
                    </dl>

                    <!-- Revocation Info -->
                    @if ($certificate->status === 'revoked')
                        <div class="mt-6 bg-red-50 border border-red-200 rounded-md p-4">
                            <h4 class="text-sm font-medium text-red-800 mb-3">Informasi Pencabutan</h4>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-3">
                                <div>
                                    <dt class="text-sm font-medium text-red-700">Dicabut pada</dt>
                                    <dd class="mt-1 text-sm text-red-900">{{ optional($certificate->revoked_at)->format('d M Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-red-700">Dicabut oleh</dt>
                                    <dd class="mt-1 text-sm text-red-900">{{ optional($certificate->revokedBy)->name ?? '-' }}</dd>
                                </div>
                                <div class="sm:col-span-3">
                                    <dt class="text-sm font-medium text-red-700">Alasan</dt>
                                    <dd class="mt-1 text-sm text-red-900">{{ $certificate->revoked_reason }}</dd>
                                </div>
                            </dl>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Actions -->
        <div class="space-y-6">
            <!-- Actions Card -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Aksi</h3>
                    <div class="space-y-3">
                        @if ($certificate->status === 'generated' && $certificate->file_path)
                            <form method="post" action="{{ route('admin.certificates.apply', $certificate) }}">
                                @csrf
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('Terbitkan sertifikat ini dan kirim email ke peserta?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Terbitkan & Kirim Email
                                </button>
                            </form>
                        @endif

                        @if ($certificate->status === 'generated')
                            <button class="w-full inline-flex justify-center items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150" data-bs-toggle="collapse" data-bs-target="#rejectForm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Tolak
                            </button>
                            <div class="collapse mt-2" id="rejectForm">
                                <form method="post" action="{{ route('admin.certificates.reject', $certificate) }}">
                                    @csrf
                                    <div class="mb-2">
                                        <textarea name="reject_reason" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" placeholder="Alasan penolakan..." required></textarea>
                                    </div>
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        @endif

                        @if ($certificate->status === 'rejected' && $certificate->file_path)
                            <form method="post" action="{{ route('admin.certificates.reapply', $certificate) }}">
                                @csrf
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('Terbitkan kembali dan kirim email ke peserta?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Terbitkan Kembali
                                </button>
                            </form>
                        @endif

                        @if ($certificate->file_path)
                            <a href="{{ route('certificate.download', $certificate->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150" target="_blank">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Unduh PDF
                            </a>
                        @endif

                        <a href="{{ route('certificate.verify', $certificate->verification_code) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150" target="_blank">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Buka Verifikasi Publik
                        </a>

                        @if ($certificate->status !== 'applied' || !$certificate->file_path)
                            <form method="post" action="{{ route('admin.certificates.regenerate', $certificate) }}">
                                @csrf
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('Regenerate sertifikat ini?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Regenerate
                                </button>
                            </form>
                        @endif

                        @if ($certificate->status !== 'revoked')
                            <a href="{{ route('admin.certificates.editRevoke', $certificate) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                Cabut Sertifikat
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- QR Code Preview -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">QR Code</h3>
                    <div class="text-center">
                        @php
                            $verifyUrl = route('certificate.verify', $certificate->verification_code);
                        @endphp
                        @if (class_exists(\Endroid\QrCode\Writer\PngWriter::class))
                            @php
                                $qrCode = \Endroid\QrCode\QrCode::create($verifyUrl);
                                $writer = new \Endroid\QrCode\Writer\PngWriter();
                                $result = $writer->write($qrCode);
                                $qr = base64_encode($result->getString());
                            @endphp
                            <img src="data:image/png;base64,{{ $qr }}" class="mx-auto rounded border max-w-xs" alt="QR Code">
                            <p class="mt-3 text-sm text-gray-500">{{ substr($verifyUrl, 0, 25) }}...</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
