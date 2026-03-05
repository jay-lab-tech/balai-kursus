@extends('layouts.app-bootstrap')

@section('title', 'Analytics Sertifikat')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-graph-up me-2"></i>Analytics Sertifikat</h2>
        <div class="d-flex gap-2">
            <form method="get" class="d-flex gap-2">
                <select name="period" class="form-select form-select-lg" onchange="this.form.submit()">
                    <option value="30days" {{ $period === '30days' ? 'selected' : '' }}>📅 30 Hari Terakhir</option>
                    <option value="90days" {{ $period === '90days' ? 'selected' : '' }}>📅 90 Hari Terakhir</option>
                    <option value="1year" {{ $period === '1year' ? 'selected' : '' }}>📅 1 Tahun Terakhir</option>
                    <option value="all" {{ $period === 'all' ? 'selected' : '' }}>📅 Semua Data</option>
                </select>
            </form>
            <a href="{{ route('admin.certificates.analytics.export', ['period' => $period]) }}" class="btn btn-success btn-lg">
                <i class="bi bi-download me-2"></i>Export CSV
            </a>
        </div>
    </div>

    {{-- Key Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small fw-bold">Total Sertifikat</div>
                            <div class="h2 mb-0 fw-bold text-dark">{{ $total }}</div>
                        </div>
                        <i class="bi bi-award fs-2 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small fw-bold">Diterbitkan</div>
                            <div class="h2 mb-0 fw-bold text-success">{{ $generated }}</div>
                        </div>
                        <i class="bi bi-check-circle fs-2 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small fw-bold">Dicabut</div>
                            <div class="h2 mb-0 fw-bold text-danger">{{ $revoked }}</div>
                        </div>
                        <i class="bi bi-slash-circle fs-2 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small fw-bold">Menunggu Review</div>
                            <div class="h2 mb-0 fw-bold text-warning">{{ $queued }}</div>
                        </div>
                        <i class="bi bi-hourglass-split fs-2 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-0 bg-light py-3">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-alarm me-2"></i>Status Masa Berlaku</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="small text-muted fw-bold">Akan Berakhir (< 7 hari)</div>
                                    <div class="h4 mb-0 fw-bold text-warning">{{ $expiringSoon }}</div>
                                </div>
                                <i class="bi bi-exclamation-triangle fs-2 text-warning opacity-50"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="small text-muted fw-bold">Kadaluarsa</div>
                                    <div class="h4 mb-0 fw-bold text-danger">{{ $expired }}</div>
                                </div>
                                <i class="bi bi-x-circle fs-2 text-danger opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-0 bg-light py-3">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-graph-up me-2"></i>Trend Penerbitan</h6>
                </div>
                <div class="card-body">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- By Course --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header border-0 bg-light py-3">
            <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-book me-2"></i>Sertifikat per Kursus</h6>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-bold"><i class="bi bi-book me-2"></i>Kursus</th>
                        <th class="fw-bold text-center"><i class="bi bi-award me-2"></i>Total</th>
                        <th class="fw-bold text-center"><i class="bi bi-check-circle me-2"></i>Diterbitkan</th>
                        <th class="fw-bold text-center"><i class="bi bi-slash-circle me-2"></i>Dicabut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($byCourse as $item)
                        <tr class="align-middle">
                            <td class="fw-bold">{{ $item['kursus'] }}</td>
                            <td class="text-center"><span class="badge bg-primary fs-6">{{ $item['issued'] }}</span></td>
                            <td class="text-center"><span class="badge bg-success fs-6">{{ $item['generated'] }}</span></td>
                            <td class="text-center"><span class="badge bg-danger fs-6">{{ $item['revoked'] }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4"><i class="bi bi-inbox me-2"></i>Belum ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header border-0 bg-light py-3">
            <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history me-2"></i>Aktivitas Terbaru</h6>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-bold"><i class="bi bi-barcode me-2"></i>No. Sertifikat</th>
                        <th class="fw-bold"><i class="bi bi-person me-2"></i>Peserta</th>
                        <th class="fw-bold"><i class="bi bi-book me-2"></i>Kursus</th>
                        <th class="fw-bold text-center"><i class="bi bi-tag me-2"></i>Status</th>
                        <th class="fw-bold text-center"><i class="bi bi-calendar me-2"></i>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recent as $cert)
                        <tr class="align-middle">
                            <td><code class="bg-light p-2 rounded">{{ $cert->no_sertifikat }}</code></td>
                            <td>{{ $cert->peserta->nama ?? '-' }}</td>
                            <td>{{ $cert->kursus->nama ?? $cert->kursus->judul ?? '-' }}</td>
                            <td class="text-center">
                                @if ($cert->status === 'generated')
                                    <span class="badge bg-success"><i class="bi bi-hourglass-split me-1"></i>Di-Generate</span>
                                @elseif ($cert->status === 'applied')
                                    <span class="badge bg-primary"><i class="bi bi-check-circle me-1"></i>Diterbitkan</span>
                                @elseif ($cert->status === 'rejected')
                                    <span class="badge bg-warning text-dark"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                                @elseif ($cert->status === 'revoked')
                                    <span class="badge bg-danger"><i class="bi bi-slash-circle me-1"></i>Dicabut</span>
                                @else
                                    <span class="badge bg-secondary">{{ $cert->status }}</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $cert->issued_at?->format('d M Y H:i') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4"><i class="bi bi-inbox me-2"></i>Belum ada sertifikat</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    const ctx = document.getElementById('trendChart').getContext('2d');
    const trend = @json($trend);
    const labels = Object.keys(trend);
    const data = Object.values(trend);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Sertifikat Diterbitkan',
                data: data,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#0d6efd',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        usePointStyle: true,
                        padding: 15,
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
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection
