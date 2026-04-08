@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('page-title', 'Dashboard Admin')

@section('page-description', 'Pantau performa operasional, aktivitas kelas, dan ringkasan finansial Balai Kursus dalam satu tampilan.')

@section('content')
<div class="space-y-8">
    <section class="admin-panel overflow-hidden rounded-[2rem] p-6 sm:p-8">
        <div class="grid gap-6 xl:grid-cols-[1.35fr_0.65fr]">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-600/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">
                    <i class="bi bi-lightning-charge-fill text-red-400"></i>
                    Pusat Kendali
                </div>
                <h1 class="mt-5 text-3xl font-bold text-white sm:text-4xl">Panel admin yang lebih fokus, modern, dan siap dipakai setiap hari.</h1>
                <p class="mt-4 max-w-3xl text-base leading-7 text-slate-300">
                    Ringkasan utama ditampilkan di bagian atas agar tim admin bisa langsung menangkap jumlah peserta, kelas aktif, pemasukan, dan ritme kegiatan hari ini tanpa berpindah halaman.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('admin.kursus.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-red-600 to-red-700 px-5 py-3 text-sm font-semibold text-white transition hover:from-red-500 hover:to-red-600">
                        <i class="bi bi-plus-circle"></i>
                        Tambah Kelas Program
                    </a>
                    <a href="{{ route('admin.jadwal.all') }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                        <i class="bi bi-calendar-week"></i>
                        Kelola Jadwal
                    </a>
                    <a href="{{ route('admin.peserta.create') }}" class="inline-flex items-center gap-2 rounded-2xl border border-yellow-400/20 bg-yellow-400/10 px-5 py-3 text-sm font-semibold text-yellow-300 transition hover:bg-yellow-400/15">
                        <i class="bi bi-person-plus"></i>
                        Tambah Peserta
                    </a>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                <div class="rounded-[1.75rem] bg-gradient-to-br from-yellow-400 to-yellow-500 p-5 text-slate-900 shadow-2xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] opacity-75">Pemasukan Total</p>
                    <p class="mt-3 text-3xl font-bold">Rp {{ number_format($totalPemasukan) }}</p>
                    <p class="mt-2 text-sm opacity-75">Akumulasi pembayaran online yang sudah sukses.</p>
                </div>
                <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Arah Hari Ini</p>
                    <div class="mt-3 space-y-3 text-sm text-slate-300">
                        <div class="flex items-center justify-between rounded-2xl bg-black/20 px-4 py-3">
                            <span class="flex items-center gap-2"><i class="bi bi-people-fill text-yellow-300"></i>Peserta</span>
                            <span class="font-semibold text-white">{{ number_format($totalPeserta) }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-black/20 px-4 py-3">
                            <span class="flex items-center gap-2"><i class="bi bi-book-half text-yellow-300"></i>Kelas</span>
                            <span class="font-semibold text-white">{{ number_format($totalKursus) }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-black/20 px-4 py-3">
                            <span class="flex items-center gap-2"><i class="bi bi-graph-up-arrow text-yellow-300"></i>Bulan data</span>
                            <span class="font-semibold text-white">{{ $grafik->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[1.75rem] bg-gradient-to-br from-red-600 to-red-700 p-6 text-white shadow-2xl">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-red-100">Total Peserta</p>
                    <p class="mt-4 text-4xl font-bold">{{ number_format($totalPeserta) }}</p>
                    <p class="mt-2 text-sm text-red-100/90">Pengguna yang sudah terdaftar dalam sistem.</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-3 text-2xl">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.75rem] bg-gradient-to-br from-slate-800 to-slate-950 p-6 text-white shadow-2xl ring-1 ring-yellow-400/20">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">Total Kelas Program</p>
                    <p class="mt-4 text-4xl font-bold">{{ number_format($totalKursus) }}</p>
                    <p class="mt-2 text-sm text-slate-300">Kelas yang sudah dibuat dan dikelola admin.</p>
                </div>
                <div class="rounded-2xl bg-yellow-400/10 p-3 text-2xl text-yellow-300">
                    <i class="bi bi-book-half"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.75rem] bg-gradient-to-br from-emerald-500 to-emerald-700 p-6 text-white shadow-2xl">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-emerald-100">Pemasukan</p>
                    <p class="mt-4 text-4xl font-bold">Rp {{ number_format($totalPemasukan) }}</p>
                    <p class="mt-2 text-sm text-emerald-100/90">Diambil dari pembayaran online yang sukses.</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-3 text-2xl">
                    <i class="bi bi-cash-coin"></i>
                </div>
            </div>
        </div>

        <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6 text-white shadow-2xl">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Grafik Aktif</p>
                    <p class="mt-4 text-4xl font-bold">{{ $grafik->count() }}</p>
                    <p class="mt-2 text-sm text-slate-300">Jumlah titik data yang tersedia untuk tren pemasukan.</p>
                </div>
                <div class="rounded-2xl bg-red-600/15 p-3 text-2xl text-red-300">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.35fr_0.65fr]">
        <div class="admin-panel rounded-[2rem] p-6 sm:p-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white">
                        <i class="bi bi-bar-chart-line-fill mr-3 text-yellow-300"></i>Grafik Pemasukan Bulanan
                    </h2>
                    <p class="mt-2 text-slate-400">Pantau tren pemasukan untuk melihat ritme performa pembayaran dari waktu ke waktu.</p>
                </div>
                <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-300">
                    {{ $grafik->count() }} bulan tercatat
                </span>
            </div>

            <div class="mt-6 rounded-[1.75rem] border border-white/10 bg-slate-950/45 p-4">
                <canvas id="chart" style="max-height: 420px;"></canvas>
            </div>
        </div>

        <div class="space-y-6">
            <div class="admin-panel rounded-[2rem] p-6">
                <h2 class="text-xl font-bold text-white">
                    <i class="bi bi-stars mr-3 text-yellow-300"></i>Aksi Cepat
                </h2>
                <div class="mt-5 space-y-3">
                    <a href="{{ route('admin.program.create') }}" class="flex items-center justify-between rounded-2xl bg-black/20 px-4 py-3 text-sm text-slate-200 transition hover:bg-black/30">
                        <span class="flex items-center gap-3"><i class="bi bi-diagram-3-fill text-yellow-300"></i>Tambah Program</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{ route('admin.instruktur.create') }}" class="flex items-center justify-between rounded-2xl bg-black/20 px-4 py-3 text-sm text-slate-200 transition hover:bg-black/30">
                        <span class="flex items-center gap-3"><i class="bi bi-person-badge-fill text-yellow-300"></i>Tambah Instruktur</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{ route('admin.certificates.index') }}" class="flex items-center justify-between rounded-2xl bg-black/20 px-4 py-3 text-sm text-slate-200 transition hover:bg-black/30">
                        <span class="flex items-center gap-3"><i class="bi bi-patch-check-fill text-yellow-300"></i>Kelola Sertifikat</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="rounded-[2rem] bg-gradient-to-br from-yellow-400 to-yellow-500 p-6 text-slate-900 shadow-2xl">
                <h2 class="text-xl font-bold">Arah Desain Baru</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 opacity-80">
                    <p>1. Sidebar kini menjadi pusat navigasi admin yang lebih terstruktur.</p>
                    <p>2. Dashboard dirancang untuk mempermudah pemindaian angka utama dan aksi cepat.</p>
                    <p>3. Halaman CRUD berikutnya akan mengikuti sistem visual ini agar admin konsisten.</p>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = {!! json_encode($grafik->pluck('bulan')) !!};
    const data = {!! json_encode($grafik->pluck('total')) !!};

    const ctx = document.getElementById('chart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pemasukan Bulanan',
                data: data,
                borderColor: '#facc15',
                backgroundColor: 'rgba(250, 204, 21, 0.12)',
                borderWidth: 3,
                fill: true,
                tension: 0.35,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#ef4444',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: '#cbd5e1',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#94a3b8'
                    },
                    grid: {
                        color: 'rgba(148, 163, 184, 0.08)'
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#94a3b8',
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    },
                    grid: {
                        color: 'rgba(148, 163, 184, 0.08)'
                    }
                }
            }
        }
    });
</script>
@endsection
