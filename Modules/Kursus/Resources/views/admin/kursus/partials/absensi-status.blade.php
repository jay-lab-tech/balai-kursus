@php
    // Hadir memakai pil dasar (sage), sisanya diwarnai sesuai tingkat perhatian.
    $rupaAbsensi = match ($absensi->status) {
        'H' => '',
        'I' => 'bk-tag--info',
        'S' => 'bk-tag--jalan',
        'A' => 'bk-tag--perlu',
        default => 'bk-tag--diam',
    };
@endphp
<span class="bk-tag {{ $rupaAbsensi }}">{{ $absensi->label_status }}</span>
