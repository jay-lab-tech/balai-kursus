{{--
    Penunjuk posisi satu pendaftaran di dalam alur program. Dulu tiap halaman
    menjelaskan tahapan ini dengan caranya sendiri — rantai @if di halaman
    pendaftaran, daftar bernomor tetap "01..04" di beranda yang tidak pernah
    menandai peserta ada di mana — sehingga keduanya bisa bercerita beda.
--}}
@php
    $tahap = [
        \App\Models\Pendaftaran::STATUS_MENUNGGU_TES => ['Ikuti tes penempatan', 'Tes dilakukan di luar sistem, hasilnya dimasukkan admin.'],
        \App\Models\Pendaftaran::STATUS_MENUNGGU_PENEMPATAN => ['Penempatan level & kelas', 'Sistem mencari kelas selevel yang kuotanya masih ada.'],
        \App\Models\Pendaftaran::STATUS_MENUNGGU_PEMBAYARAN => ['Selesaikan pembayaran', 'Tagihan baru terbit setelah kelas ditentukan.'],
        \App\Models\Pendaftaran::STATUS_AKTIF => ['Mulai belajar', 'Materi, risalah pertemuan, dan absensi terbuka.'],
        \App\Models\Pendaftaran::STATUS_SELESAI => ['Kursus selesai', 'Nilai akhir dan sertifikat diterbitkan admin.'],
    ];
    $kini = $pendaftaran->urutanAlur();
    $batal = $pendaftaran->status_pendaftaran === \App\Models\Pendaftaran::STATUS_DIBATALKAN;
@endphp

@if ($batal)
    <div class="bk-note bk-note--buruk">
        <i class="bi bi-x-octagon-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ $pendaftaran->petunjuk }}</span>
    </div>
@else
    <ol class="bk-steps">
        @foreach (array_values($tahap) as $nomor => [$judul, $rincian])
            <li class="{{ ! is_null($kini) && $nomor < $kini ? 'is-selesai' : '' }} {{ $nomor === $kini ? 'is-kini' : '' }}">
                <b>{{ $judul }}</b>
                <small>{{ $rincian }}</small>
            </li>
        @endforeach
    </ol>
@endif
