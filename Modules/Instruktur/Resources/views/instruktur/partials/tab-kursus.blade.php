{{--
    Tiga tampilan dari satu kelas. Dulu strip ini disalin utuh di tiga view
    dengan penanda aktif yang ditulis tangan, sehingga sempat ada halaman yang
    menyorot tab yang salah. Sekarang penandanya dihitung dari nama rute.

    Pemakaian: @include('instruktur::instruktur.partials.tab-kursus', ['kursus' => $kursus])
--}}
@php
    $tab = [
        ['label' => 'Ringkasan', 'ikon' => 'bi-grid-1x2', 'rute' => 'instruktur.kursus.show', 'cocok' => 'instruktur.kursus.show'],
        ['label' => 'Pertemuan & Risalah', 'ikon' => 'bi-journal-text', 'rute' => 'instruktur.risalah.index', 'cocok' => 'instruktur.risalah.*'],
        ['label' => 'Nilai Peserta', 'ikon' => 'bi-clipboard-data', 'rute' => 'instruktur.nilai.index', 'cocok' => 'instruktur.nilai.*'],
    ];
@endphp

<nav class="bk-tabs" aria-label="Tampilan kelas {{ $kursus->nama }}">
    @foreach ($tab as $t)
        @php $aktif = request()->routeIs($t['cocok']); @endphp
        <a href="{{ route($t['rute'], $kursus) }}"
           class="bk-tab {{ $aktif ? 'is-aktif' : '' }}"
           @if ($aktif) aria-current="page" @endif>
            <i class="bi {{ $t['ikon'] }}" aria-hidden="true"></i>
            <span>{{ $t['label'] }}</span>
        </a>
    @endforeach
</nav>
