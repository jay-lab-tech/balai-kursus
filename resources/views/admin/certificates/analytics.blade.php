@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1>Analytics Sertifikat</h1>
        </div>
        <div class="col-auto">
            <form method="get" class="d-flex gap-2">
                <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="30days" {{ $period === '30days' ? 'selected' : '' }}>30 Hari</option>
                    <option value="90days" {{ $period === '90days' ? 'selected' : '' }}>90 Hari</option>
                    <option value="1year" {{ $period === '1year' ? 'selected' : '' }}>1 Tahun</option>
                    <option value="all" {{ $period === 'all' ? 'selected' : '' }}>Semua</option>
                </select>
                <a href="{{ route('admin.certificates.analytics.export', ['period' => $period]) }}" class="btn btn-sm btn-success">
                    📥 Export CSV
                </a>
            </form>
        </div>
    </div>

    {{-- Key Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Total Sertifikat</div>
                    <div class="h2 mb-0">{{ $total }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Di-Generate</div>
                    <div class="h2 mb-0 text-success">{{ $generated }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Dicabut</div>
                    <div class="h2 mb-0 text-danger">{{ $revoked }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Dalam Antri</div>
                    <div class="h2 mb-0 text-warning">{{ $queued }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">⏰ Masa Berlaku</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-6">
                            <small class="text-muted">Habit Berakhir (< 7 hari)</small>
                            <div class="h4 mb-0 text-warning">{{ $expiringSoon }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Sudah Kadaluarsa</small>
                            <div class="h4 mb-0 text-danger">{{ $expired }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">📊 Trend (Terbit per Periode)</h6>
                </div>
                <div class="card-body">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- By Course --}}
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">Sertifikat per Kursus</h6>
        </div>
        <table class="table table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kursus</th>
                    <th>Total</th>
                    <th>Di-Generate</th>
                    <th>Dicabut</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($byCourse as $item)
                    <tr>
                        <td>{{ $item['kursus'] }}</td>
                        <td><strong>{{ $item['issued'] }}</strong></td>
                        <td><span class="badge bg-success">{{ $item['generated'] }}</span></td>
                        <td><span class="badge bg-danger">{{ $item['revoked'] }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">Belum ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Recent Activity --}}
    <div class="card">
        <div class="card-header bg-light">
            <h6 class="mb-0">Aktivitas Terbaru</h6>
        </div>
        <table class="table table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Sertifikat</th>
                    <th>Peserta</th>
                    <th>Kursus</th>
                    <th>Status</th>
                    <th>Terbit</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recent as $cert)
                    <tr>
                        <td>{{ $cert->no_sertifikat }}</td>
                        <td>{{ $cert->peserta->nama ?? '-' }}</td>
                        <td>{{ $cert->kursus->judul ?? '-' }}</td>
                        <td>
                            @if ($cert->status === 'generated')
                                <span class="badge bg-success">Generated</span>
                            @elseif ($cert->status === 'revoked')
                                <span class="badge bg-danger">Dicabut</span>
                            @else
                                <span class="badge bg-warning">Antri</span>
                            @endif
                        </td>
                        <td>{{ $cert->issued_at?->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">Belum ada sertifikat</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
                label: 'Sertifikat Di-Generate',
                data: data,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
