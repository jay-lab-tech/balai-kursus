@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('page-title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-900"><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</h2>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white shadow rounded-lg p-6 border-t-4 border-indigo-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm text-gray-600 mb-1"><i class="bi bi-people me-2"></i>Total Peserta</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalPeserta }}</p>
                </div>
                <div class="text-4xl text-gray-100">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 border-t-4 border-purple-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm text-gray-600 mb-1"><i class="bi bi-book me-2"></i>Total Kursus</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalKursus }}</p>
                </div>
                <div class="text-4xl text-gray-100">
                    <i class="bi bi-book"></i>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 border-t-4 border-blue-600">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm text-gray-600 mb-1"><i class="bi bi-cash-coin me-2"></i>Total Pemasukan</p>
                    <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($totalPemasukan) }}</p>
                </div>
                <div class="text-4xl text-gray-100">
                    <i class="bi bi-cash-coin"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">
                <i class="bi bi-graph-up text-blue-600 me-2"></i>Grafik Pemasukan Bulanan
            </h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <canvas id="chart" style="max-height: 400px;"></canvas>
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
