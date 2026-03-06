@extends('layouts.admin')

@section('title', 'Analytics Sertifikat')

@section('page-title', 'Analytics Sertifikat')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Analytics Sertifikat</h1>
        <div class="flex space-x-3">
            <form method="get" class="flex items-center space-x-2">
                <select name="period" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" onchange="this.form.submit()">
                    <option value="30days" {{ $period === '30days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                    <option value="90days" {{ $period === '90days' ? 'selected' : '' }}>90 Hari Terakhir</option>
                    <option value="1year" {{ $period === '1year' ? 'selected' : '' }}>1 Tahun Terakhir</option>
                    <option value="all" {{ $period === 'all' ? 'selected' : '' }}>Semua Data</option>
                </select>
            </form>
            <a href="{{ route('admin.certificates.analytics.export', ['period' => $period]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export CSV
            </a>
        </div>
    </div>

    <!-- Key Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Sertifikat</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $total }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Diterbitkan</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $generated }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Dicabut</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $revoked }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Menunggu Review</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $queued }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Masa Berlaku and Trend -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Status Masa Berlaku</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Akan Berakhir (&lt; 7 hari)</p>
                            <p class="text-2xl font-semibold text-yellow-600">{{ $expiringSoon }}</p>
                        </div>
                        <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Kadaluarsa</p>
                            <p class="text-2xl font-semibold text-red-600">{{ $expired }}</p>
                        </div>
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Trend Penerbitan</h3>
                <canvas id="trendChart" class="w-full"></canvas>
            </div>
        </div>
    </div>

    <!-- By Course -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
        <div class="px-6 py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-semibold text-gray-900 flex items-center">
                <i class="bi bi-bar-chart-line mr-2 text-blue-600"></i>
                Sertifikat per Kursus
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
                            <div class="flex items-center">
                                <i class="bi bi-book mr-2 text-gray-400"></i>
                                Kursus
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
                            <div class="flex items-center">
                                <i class="bi bi-trophy mr-2 text-gray-400"></i>
                                Total
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
                            <div class="flex items-center">
                                <i class="bi bi-check-circle mr-2 text-gray-400"></i>
                                Diterbitkan
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
                            <div class="flex items-center">
                                <i class="bi bi-x-circle mr-2 text-gray-400"></i>
                                Dicabut
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($byCourse as $item)
                        <tr class="hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 transition-all duration-200 hover:shadow-sm">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                                            <i class="bi bi-book text-white text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $item['kursus'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 border-2 border-blue-300">
                                    <i class="bi bi-trophy-fill mr-2"></i>
                                    {{ $item['issued'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-green-100 to-green-200 text-green-800 border-2 border-green-300">
                                    <i class="bi bi-check-circle-fill mr-2"></i>
                                    {{ $item['generated'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-red-100 to-red-200 text-red-800 border-2 border-red-300">
                                    <i class="bi bi-x-circle-fill mr-2"></i>
                                    {{ $item['revoked'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="bi bi-info-circle text-gray-400 text-3xl mb-2"></i>
                                    <span class="text-gray-500 font-medium">Belum ada data sertifikat</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
        <div class="px-6 py-5 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-semibold text-gray-900 flex items-center">
                <i class="bi bi-activity mr-2 text-purple-600"></i>
                Aktivitas Terbaru
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
                            <div class="flex items-center">
                                <i class="bi bi-upc mr-2 text-gray-400"></i>
                                No. Sertifikat
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
                            <div class="flex items-center">
                                <i class="bi bi-person mr-2 text-gray-400"></i>
                                Peserta
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
                            <div class="flex items-center">
                                <i class="bi bi-book mr-2 text-gray-400"></i>
                                Kursus
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
                            <div class="flex items-center">
                                <i class="bi bi-flag mr-2 text-gray-400"></i>
                                Status
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-300">
                            <div class="flex items-center">
                                <i class="bi bi-calendar mr-2 text-gray-400"></i>
                                Tanggal
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($recent as $cert)
                        <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-all duration-200 hover:shadow-sm">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <code class="bg-gradient-to-r from-gray-100 to-gray-200 px-3 py-2 rounded-lg text-sm font-mono font-semibold text-gray-800 border border-gray-300">
                                    {{ $cert->no_sertifikat }}
                                </code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                            <i class="bi bi-person-fill text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $cert->peserta->nama ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                                    <i class="bi bi-book mr-1"></i>
                                    {{ $cert->kursus->nama ?? $cert->kursus->judul ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($cert->status === 'generated')
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-green-100 to-green-200 text-green-800 border-2 border-green-300">
                                        <i class="bi bi-check-circle-fill mr-2"></i>
                                        Di-Generate
                                    </span>
                                @elseif ($cert->status === 'applied')
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 border-2 border-blue-300">
                                        <i class="bi bi-file-earmark-check-fill mr-2"></i>
                                        Diterbitkan
                                    </span>
                                @elseif ($cert->status === 'rejected')
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 border-2 border-yellow-300">
                                        <i class="bi bi-x-circle-fill mr-2"></i>
                                        Ditolak
                                    </span>
                                @elseif ($cert->status === 'revoked')
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-red-100 to-red-200 text-red-800 border-2 border-red-300">
                                        <i class="bi bi-slash-circle-fill mr-2"></i>
                                        Dicabut
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 border-2 border-gray-300">
                                        <i class="bi bi-question-circle-fill mr-2"></i>
                                        {{ $cert->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <i class="bi bi-calendar-event text-gray-400 mr-2"></i>
                                    <span class="font-medium">{{ $cert->issued_at?->format('d M Y') ?? '-' }}</span>
                                    @if($cert->issued_at)
                                        <br><small class="text-gray-500 ml-6">{{ $cert->issued_at->format('H:i') }}</small>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="bi bi-info-circle text-gray-400 text-3xl mb-2"></i>
                                    <span class="text-gray-500 font-medium">Belum ada sertifikat</span>
                                </div>
                            </td>
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
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#2563eb',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 12,
                            weight: '500'
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
