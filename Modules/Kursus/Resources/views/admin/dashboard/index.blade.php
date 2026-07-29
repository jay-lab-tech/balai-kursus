@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Ringkasan operasional')
@section('page-description', 'Pantau kelas, peserta, pemasukan, dan aktivitas yang perlu ditindaklanjuti.')

@section('content')
<div class="space-y-8">
    <section class="grid gap-8 border-b border-[#dce7e5] pb-8 xl:grid-cols-[minmax(0,1.25fr)_minmax(260px,.75fr)]">
        <div>
            <p class="font-mono text-xs font-semibold uppercase tracking-[0.24em] text-[#0d9488]">Pusat kendali / {{ now()->translatedFormat('j F Y') }}</p>
            <h1 class="mt-3 max-w-3xl text-4xl font-bold tracking-[-0.04em] text-[#173f5f] sm:text-5xl">Selamat datang di meja kerja admin.</h1>
            <p class="mt-4 max-w-2xl text-base leading-7 text-[#718596]">Angka utama dan pekerjaan penting diringkas di sini supaya Anda bisa menentukan langkah berikutnya tanpa mencari-cari di banyak halaman.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('admin.kursus.create') }}" class="inline-flex min-h-11 items-center gap-2 rounded-xl bg-[#173f5f] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#0f766e]"><i class="bi bi-plus-lg"></i>Tambah kelas</a>
                <a href="{{ route('admin.jadwal.all') }}" class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-[#c8d9d6] bg-white px-4 py-3 text-sm font-semibold text-[#40627d] transition hover:border-[#0d9488] hover:text-[#0f766e]"><i class="bi bi-calendar3"></i>Lihat jadwal</a>
            </div>
        </div>
        <div class="self-end border-l-4 border-[#d97706] bg-[#fffaf0] p-6">
            <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#b45309]">Pemasukan tercatat</p>
            <p class="mt-3 text-3xl font-bold tracking-tight text-[#173f5f]">Rp {{ number_format($totalPemasukan) }}</p>
            <p class="mt-2 text-sm leading-6 text-[#718596]">Akumulasi pembayaran online yang berstatus sukses.</p>
        </div>
    </section>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Metrik operasional">
        <div class="border border-[#dce7e5] bg-white p-5 shadow-[0_14px_35px_rgba(23,63,95,.06)]"><p class="font-mono text-xs uppercase tracking-[0.18em] text-[#718596]">Peserta</p><p class="mt-3 text-4xl font-bold text-[#173f5f]">{{ number_format($totalPeserta) }}</p><p class="mt-2 text-sm text-[#718596]">akun peserta terdaftar</p></div>
        <div class="border border-[#dce7e5] bg-white p-5 shadow-[0_14px_35px_rgba(23,63,95,.06)]"><p class="font-mono text-xs uppercase tracking-[0.18em] text-[#718596]">Kelas program</p><p class="mt-3 text-4xl font-bold text-[#173f5f]">{{ number_format($totalKursus) }}</p><p class="mt-2 text-sm text-[#718596]">kelas tersedia di katalog</p></div>
        <div class="border border-[#dce7e5] bg-white p-5 shadow-[0_14px_35px_rgba(23,63,95,.06)]"><p class="font-mono text-xs uppercase tracking-[0.18em] text-[#718596]">Data bulanan</p><p class="mt-3 text-4xl font-bold text-[#173f5f]">{{ $grafik->count() }}</p><p class="mt-2 text-sm text-[#718596]">periode yang tercatat</p></div>
        <div class="border border-[#dce7e5] bg-[#e8f7f4] p-5"><p class="font-mono text-xs uppercase tracking-[0.18em] text-[#0f766e]">Status sistem</p><p class="mt-3 flex items-center gap-2 text-xl font-bold text-[#173f5f]"><span class="h-2.5 w-2.5 rounded-full bg-[#16a34a]"></span>Berjalan baik</p><p class="mt-2 text-sm text-[#40627d]">Semua modul siap digunakan</p></div>
    </section>

    <section class="grid gap-8 xl:grid-cols-[minmax(0,1.35fr)_minmax(280px,.65fr)]">
        <div class="border border-[#dce7e5] bg-white p-6 shadow-[0_14px_35px_rgba(23,63,95,.06)] sm:p-8">
            <div class="flex flex-col gap-3 border-b border-[#e8efed] pb-5 sm:flex-row sm:items-end sm:justify-between">
                <div><p class="font-mono text-xs uppercase tracking-[0.18em] text-[#0d9488]">Tren pembayaran</p><h2 class="mt-2 text-2xl font-bold tracking-tight text-[#173f5f]">Pemasukan bulanan</h2></div>
                <span class="font-mono text-xs text-[#718596]">{{ $grafik->count() }} periode</span>
            </div>
            <div class="mt-6 h-[300px]"><canvas id="chart"></canvas></div>
        </div>

        <aside class="border border-[#dce7e5] bg-white p-6 shadow-[0_14px_35px_rgba(23,63,95,.06)]">
            <p class="font-mono text-xs uppercase tracking-[0.18em] text-[#0d9488]">Pekerjaan cepat</p>
            <h2 class="mt-2 text-xl font-bold text-[#173f5f]">Mulai dari sini</h2>
            <div class="mt-5 divide-y divide-[#e8efed]">
                <a href="{{ route('admin.program.create') }}" class="flex min-h-12 items-center justify-between py-3 text-sm font-semibold text-[#40627d] transition hover:text-[#0f766e]"><span><i class="bi bi-diagram-3 mr-3 text-[#0d9488]"></i>Tambah program</span><i class="bi bi-arrow-up-right"></i></a>
                <a href="{{ route('admin.peserta.create') }}" class="flex min-h-12 items-center justify-between py-3 text-sm font-semibold text-[#40627d] transition hover:text-[#0f766e]"><span><i class="bi bi-person-plus mr-3 text-[#0d9488]"></i>Tambah peserta</span><i class="bi bi-arrow-up-right"></i></a>
                <a href="{{ route('admin.instruktur.create') }}" class="flex min-h-12 items-center justify-between py-3 text-sm font-semibold text-[#40627d] transition hover:text-[#0f766e]"><span><i class="bi bi-person-badge mr-3 text-[#0d9488]"></i>Tambah instruktur</span><i class="bi bi-arrow-up-right"></i></a>
                <a href="{{ route('admin.certificates.index') }}" class="flex min-h-12 items-center justify-between py-3 text-sm font-semibold text-[#40627d] transition hover:text-[#0f766e]"><span><i class="bi bi-patch-check mr-3 text-[#0d9488]"></i>Kelola sertifikat</span><i class="bi bi-arrow-up-right"></i></a>
            </div>
        </aside>
    </section>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($grafik->pluck('bulan')) !!},
                datasets: [{ label: 'Pemasukan', data: {!! json_encode($grafik->pluck('total')) !!}, borderColor: '#0d9488', backgroundColor: 'rgba(13,148,136,.10)', borderWidth: 3, fill: true, tension: .35, pointRadius: 4, pointBackgroundColor: '#d97706', pointBorderColor: '#fff', pointBorderWidth: 2 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { ticks: { color: '#718596' }, grid: { color: '#e8efed' } }, y: { ticks: { color: '#718596' }, grid: { color: '#e8efed' }, beginAtZero: true } } }
        });
    }
</script>
@endsection
