@extends('kursus::layouts.master')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</h2>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
            </button>
        </form>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm" style="border-top: 4px solid #667eea;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1"><i class="bi bi-people me-2"></i>Total Peserta</h6>
                            <h2 class="fw-bold text-dark mb-0">{{ $totalPeserta }}</h2>
                        </div>
                        <div style="font-size: 3rem; opacity: 0.1;">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm" style="border-top: 4px solid #764ba2;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1"><i class="bi bi-book me-2"></i>Total Kursus</h6>
                            <h2 class="fw-bold text-dark mb-0">{{ $totalKursus }}</h2>
                        </div>
                        <div style="font-size: 3rem; opacity: 0.1;">
                            <i class="bi bi-book"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm" style="border-top: 4px solid #4f46e5;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1"><i class="bi bi-cash-coin me-2"></i>Total Pemasukan</h6>
                            <h2 class="fw-bold text-dark mb-0">Rp {{ number_format($totalPemasukan) }}</h2>
                        </div>
                        <div style="font-size: 3rem; opacity: 0.1;">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-graph-up me-2 text-primary"></i>Grafik Pemasukan Bulanan
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chart" style="max-height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </div>
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
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#667eea',
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
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>

@endsection
