@extends('layouts.admin')

@section('title', 'Beranda Admin')
@section('page-context', 'Ikhtisar · ' . now()->translatedFormat('l, j F Y'))
@section('page-title', 'Meja kerja admin')
@section('page-description', 'Angka utama dan antrean pekerjaan diringkas di sini supaya langkah berikutnya bisa ditentukan tanpa berpindah-pindah halaman.')

@section('content')

<div class="bk-stats">
    <article class="bk-stat">
        <span class="bk-stat__icon"><i class="bi bi-people" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Peserta terdaftar</span>
        <p class="bk-stat__value">{{ number_format($totalPeserta, 0, ',', '.') }}</p>
        <p class="bk-stat__hint">Akun peserta yang sudah ada di sistem.</p>
    </article>

    <article class="bk-stat">
        <span class="bk-stat__icon"><i class="bi bi-mortarboard" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Kelas program</span>
        <p class="bk-stat__value">{{ number_format($totalKursus, 0, ',', '.') }}</p>
        <p class="bk-stat__hint">Seluruh kelas di katalog, termasuk yang sudah tutup.</p>
    </article>

    <article class="bk-stat bk-stat--amber">
        <span class="bk-stat__icon"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Menunggu tes &amp; penempatan</span>
        <p class="bk-stat__value">{{ number_format($menungguTes, 0, ',', '.') }}</p>
        <p class="bk-stat__hint">Pendaftaran yang belum punya level dan kelas.</p>
    </article>

    <article class="bk-stat bk-stat--terra">
        <span class="bk-stat__icon"><i class="bi bi-cash-coin" aria-hidden="true"></i></span>
        <span class="bk-stat__label">Menunggu pembayaran</span>
        <p class="bk-stat__value">{{ number_format($menungguPembayaran, 0, ',', '.') }}</p>
        <p class="bk-stat__hint">Sudah ditempatkan, kursinya belum lunas.</p>
    </article>
</div>

<section class="bk-panel" style="margin-top:1.5rem">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Pemasukan {{ now()->year }}</h2>
            {{-- Grafiknya per bulan sepanjang tahun, sedangkan angka rupiah di
                 sini hanya 30 hari terakhir — dua rentang itu disebut terpisah
                 supaya tidak terbaca sebagai satu angka yang sama. --}}
            <p class="bk-panel__subtitle">
                Pembayaran daring berstatus sukses, dirangkum per bulan.
                Tiga puluh hari terakhir: <b>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</b>.
            </p>
        </div>
        <span class="bk-tag bk-tag--diam">{{ $grafik->count() }} bulan tercatat</span>
    </div>

    <div class="bk-panel__body">
        @if ($grafik->isEmpty())
            <div class="bk-empty">
                <span class="bk-empty__icon"><i class="bi bi-graph-up" aria-hidden="true"></i></span>
                <h3>Belum ada pembayaran tahun ini</h3>
                <p>Grafik muncul setelah ada pembayaran daring yang berhasil diproses.</p>
            </div>
        @else
            <div style="height:300px"><canvas id="grafikPemasukan"></canvas></div>
        @endif
    </div>
</section>

<section class="bk-panel" style="margin-top:1.5rem">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Pekerjaan yang sering dibuka</h2>
            <p class="bk-panel__subtitle">Pintasan ke layanan yang paling sering dipakai dari halaman ini.</p>
        </div>
    </div>

    <div class="bk-panel__body">
        <div class="bk-row">
            <a href="{{ route('admin.score.index') }}" class="bk-btn bk-btn--pri">
                <i class="bi bi-clipboard-data" aria-hidden="true"></i> Input hasil tes
            </a>
            <a href="{{ route('admin.kursus.create') }}" class="bk-btn">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah kelas
            </a>
            <a href="{{ route('admin.jadwal.all') }}" class="bk-btn">
                <i class="bi bi-calendar3" aria-hidden="true"></i> Lihat jadwal
            </a>
            <a href="{{ route('admin.peserta.create') }}" class="bk-btn">
                <i class="bi bi-person-plus" aria-hidden="true"></i> Tambah peserta
            </a>
            <a href="{{ route('admin.instruktur.create') }}" class="bk-btn">
                <i class="bi bi-person-badge" aria-hidden="true"></i> Tambah instruktur
            </a>
            <a href="{{ route('admin.certificates.index') }}" class="bk-btn">
                <i class="bi bi-patch-check" aria-hidden="true"></i> Kelola sertifikat
            </a>
        </div>
    </div>
</section>
@endsection

@section('scripts')
@if ($grafik->isNotEmpty())
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const kanvasPemasukan = document.getElementById('grafikPemasukan');

        if (kanvasPemasukan && window.Chart) {
            new Chart(kanvasPemasukan, {
                {{-- Satu bulan data tidak membentuk garis, cuma satu titik yang
                     menggantung di tengah kanvas. Batang lebih terbaca sampai
                     ada minimal dua bulan yang bisa dibandingkan. --}}
                type: '{{ $grafik->count() > 1 ? 'line' : 'bar' }}',
                data: {
                    labels: @json($grafik->pluck('bulan')),
                    datasets: [{
                        label: 'Pemasukan',
                        data: @json($grafik->pluck('total')),
                        borderColor: '#c05f3c',
                        backgroundColor: 'rgba(192, 95, 60, .10)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: .35,
                        pointRadius: 4,
                        pointBackgroundColor: '#c05f3c',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        // Dipakai hanya saat tipenya batang; diabaikan oleh garis.
                        maxBarThickness: 64,
                        borderRadius: 6,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (konteks) => 'Rp ' + konteks.parsed.y.toLocaleString('id-ID'),
                            },
                        },
                    },
                    scales: {
                        x: { ticks: { color: '#6b7a75' }, grid: { color: '#f3ece2' } },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#6b7a75',
                                callback: (nilai) => 'Rp ' + Number(nilai).toLocaleString('id-ID'),
                            },
                            grid: { color: '#f3ece2' },
                        },
                    },
                },
            });
        }
    </script>
@endif
@endsection
