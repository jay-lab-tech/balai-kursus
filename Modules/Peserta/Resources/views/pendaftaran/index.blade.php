@extends('peserta::layouts.student')

@section('title', 'Pendaftaran Saya - Balai Kursus')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-white mb-2">
                <i class="bi bi-clipboard text-yellow-400 mr-3"></i>Pendaftaran Saya
            </h1>
            <p class="text-gray-400">Kelola status pembayaran dan detail pendaftaran kursus Anda</p>
        </div>

        @if(Auth::user()->peserta)
            @php
                $pendaftarans = Auth::user()->peserta->pendaftarans()->with('kursus', 'pembayarans')->get();
            @endphp

            @if($pendaftarans && count($pendaftarans) > 0)
                <!-- Tabs -->
                <div class="mb-8 flex gap-2 border-b border-gray-700">
                    <button onclick="filterStatus('all')" class="filter-btn px-6 py-3 text-white border-b-2 border-yellow-400 font-semibold transition-colors" data-status="all">
                        <i class="bi bi-list mr-2"></i>Semua ({{ count($pendaftarans) }})
                    </button>
                    <button onclick="filterStatus('selesai')" class="filter-btn px-6 py-3 text-gray-400 hover:text-white border-b-2 border-transparent font-semibold transition-colors" data-status="selesai">
                        <i class="bi bi-check-circle mr-2"></i>Selesai ({{ count($pendaftarans->where('status_pembayaran', 'selesai')) }})
                    </button>
                    <button onclick="filterStatus('dp')" class="filter-btn px-6 py-3 text-gray-400 hover:text-white border-b-2 border-transparent font-semibold transition-colors" data-status="dp">
                        <i class="bi bi-hourglass-split mr-2"></i>DP ({{ count($pendaftarans->where('status_pembayaran', 'dp')) }})
                    </button>
                    <button onclick="filterStatus('pending')" class="filter-btn px-6 py-3 text-gray-400 hover:text-white border-b-2 border-transparent font-semibold transition-colors" data-status="pending">
                        <i class="bi bi-clock mr-2"></i>Pending ({{ count($pendaftarans->where('status_pembayaran', 'pending')) }})
                    </button>
                </div>

                <!-- Registrations Grid -->
                <div class="space-y-6">
                    @foreach($pendaftarans as $pendaftaran)
                    @php
                        $sisa = $pendaftaran->total_bayar - $pendaftaran->terbayar;
                    @endphp
                    <div class="registration-card" data-status="{{ $pendaftaran->status_pembayaran }}" data-pendaftaran-id="{{ $pendaftaran->id }}" data-sisa="{{ $sisa }}">
                        <div class="bg-gradient-to-r from-gray-800 to-gray-900 border border-gray-700 rounded-xl p-6 hover:border-yellow-500/50 transition-colors duration-200">
                            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                                <!-- Course Info -->
                                <div class="lg:col-span-2">
                                    <h3 class="text-xl font-bold text-white mb-2">{{ $pendaftaran->kursus->nama }}</h3>
                                    <div class="space-y-2 text-gray-400 text-sm">
                                        <div class="flex items-center">
                                            <i class="bi bi-diagram-3 text-red-500 mr-2 flex-shrink-0"></i>
                                            <span>{{ $pendaftaran->kursus->program->nama ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="bi bi-bar-chart text-yellow-500 mr-2 flex-shrink-0"></i>
                                            <span>{{ $pendaftaran->kursus->level->nama ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="bi bi-person text-blue-500 mr-2 flex-shrink-0"></i>
                                            <span>{{ $pendaftaran->kursus->instruktur->nama_instr ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="bi bi-calendar text-green-500 mr-2 flex-shrink-0"></i>
                                            <span>{{ $pendaftaran->tgl_pendaftaran ? \Carbon\Carbon::parse($pendaftaran->tgl_pendaftaran)->translatedFormat('d F Y') : 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Status -->
                                <div class="lg:col-span-1 flex flex-col justify-between">
                                    <div>
                                        <p class="text-gray-400 text-sm mb-2 font-semibold">Status Pembayaran</p>
                                        @if($pendaftaran->status_pembayaran === 'selesai')
                                            <span class="inline-flex items-center px-4 py-2 rounded-lg bg-green-500/20 text-green-400 border border-green-500/30 font-semibold text-sm">
                                                <i class="bi bi-check-circle mr-2"></i>Selesai
                                            </span>
                                        @elseif($pendaftaran->status_pembayaran === 'dp')
                                            <span class="inline-flex items-center px-4 py-2 rounded-lg bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 font-semibold text-sm">
                                                <i class="bi bi-hourglass-split mr-2"></i>DP (Cicilan)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-4 py-2 rounded-lg bg-red-500/20 text-red-400 border border-red-500/30 font-semibold text-sm">
                                                <i class="bi bi-clock mr-2"></i>Pending
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Payment Info -->
                                <div class="lg:col-span-1 flex flex-col justify-between">
                                    <div>
                                        <p class="text-gray-400 text-sm mb-2 font-semibold">Ringkasan</p>
                                        <div class="bg-transparent p-0 space-y-1 text-sm">
                                            <p class="text-white">
                                                <span class="text-gray-400">Total:</span>
                                                <span class="font-semibold text-yellow-400">Rp {{ number_format($pendaftaran->total_bayar, 0, ',', '.') }}</span>
                                            </p>
                                            <p class="text-white">
                                                <span class="text-gray-400">Terbayar:</span>
                                                <span class="font-semibold text-green-400">Rp {{ number_format($pendaftaran->terbayar, 0, ',', '.') }}</span>
                                            </p>
                                            <p class="text-white">
                                                <span class="text-gray-400">Sisa:</span>
                                                <span class="font-semibold {{ $sisa > 0 ? 'text-red-400' : 'text-green-400' }}">Rp {{ number_format(max(0, $sisa), 0, ',', '.') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            @php
                                $progress = $pendaftaran->total_bayar > 0 ? ($pendaftaran->terbayar / $pendaftaran->total_bayar) * 100 : 0;
                            @endphp
                            <div class="mt-6 pt-6 border-t border-gray-700">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-gray-400 text-sm">Progress Pembayaran</p>
                                    <p class="text-white font-semibold text-sm">{{ number_format(min($progress, 100), 0) }}%</p>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-yellow-500 to-red-500 h-full transition-all duration-500" style="width: {{ min($progress, 100) }}%"></div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-6 pt-6 border-t border-gray-700 flex items-center justify-between flex-wrap gap-3">
                                <div class="flex gap-2 flex-wrap">
                                    <a href="/peserta/kursus/{{ $pendaftaran->kursus->id }}/detail" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg transform hover:scale-105 transition-all duration-200 flex items-center text-sm">
                                        <i class="bi bi-file-earmark mr-1"></i>Lihat Material
                                    </a>
                                    @if($sisa > 0)
                                    <button onclick="startPayment({{ $pendaftaran->id }})" class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-lg transform hover:scale-105 transition-all duration-200 flex items-center text-sm">
                                        <i class="bi bi-credit-card mr-1"></i>Bayar Sekarang
                                    </button>
                                    @endif
                                </div>
                                <button onclick="showDetails({{ $pendaftaran->id }})" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors duration-200 flex items-center text-sm">
                                    <i class="bi bi-eye mr-1"></i>Detail Pembayaran
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Modal -->
                    <div id="detailModal{{ $pendaftaran->id }}" class="modal-overlay fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" onclick="event.target === this && closeDetails({{ $pendaftaran->id }})">
                        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl max-w-2xl w-full border border-gray-700 animate-fade-in-up">
                            <!-- Modal Header -->
                            <div class="flex items-center justify-between p-6 border-b border-gray-700">
                                <h3 class="text-2xl font-bold text-white">Detail Pembayaran</h3>
                                <button onclick="closeDetails({{ $pendaftaran->id }})" class="text-gray-400 hover:text-white text-2xl transition-colors">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>

                            <!-- Modal Body -->
                            <div class="p-6 max-h-96 overflow-y-auto space-y-4">
                                <!-- Registration Details -->
                                <div class="pb-4 border-b border-gray-700">
                                    <h4 class="text-white font-semibold mb-3">Informasi Pendaftaran</h4>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <p class="text-gray-400 mb-1">Kursus</p>
                                            <p class="text-white font-semibold">{{ $pendaftaran->kursus->nama }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-400 mb-1">Tanggal Pendaftaran</p>
                                            <p class="text-white font-semibold">{{ $pendaftaran->tgl_pendaftaran ? \Carbon\Carbon::parse($pendaftaran->tgl_pendaftaran)->translatedFormat('d F Y') : 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Summary -->
                                <div class="pb-4 border-b border-gray-700">
                                    <h4 class="text-white font-semibold mb-3">Ringkasan Pembayaran</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center justify-between p-3 bg-gray-700/30 rounded-lg">
                                            <span class="text-gray-400">Total Biaya</span>
                                            <span class="text-yellow-400 font-semibold">Rp {{ number_format($pendaftaran->total_bayar, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between p-3 bg-gray-700/30 rounded-lg">
                                            <span class="text-gray-400">Sudah Terbayar</span>
                                            <span class="text-green-400 font-semibold">Rp {{ number_format($pendaftaran->terbayar, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between p-3 bg-gray-700/30 rounded-lg">
                                            <span class="text-gray-400">Sisa Pembayaran</span>
                                            <span class="text-red-400 font-semibold">Rp {{ number_format(max(0, $sisa), 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment History -->
                                @if($pendaftaran->pembayarans && count($pendaftaran->pembayarans) > 0)
                                <div>
                                    <h4 class="text-white font-semibold mb-3">Riwayat Pembayaran</h4>
                                    <div class="space-y-2">
                                        @foreach($pendaftaran->pembayarans as $pembayaran)
                                        <div class="p-3 bg-gray-700/30 rounded-lg text-sm">
                                            <div class="flex items-center justify-between mb-1">
                                                <p class="text-white font-semibold">Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</p>
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold {{ $pembayaran->status === 'settlement' || $pembayaran->status === 'capture' ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400' }}">
                                                    {{ ucfirst($pembayaran->status) }}
                                                </span>
                                            </div>
                                            <p class="text-gray-400">{{ $pembayaran->tgl_pembayaran ? \Carbon\Carbon::parse($pembayaran->tgl_pembayaran)->translatedFormat('d F Y H:i') : 'Menunggu' }}</p>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @else
                                <div class="text-center py-6 text-gray-400">
                                    <i class="bi bi-inbox text-3xl mb-2 block opacity-50"></i>
                                    <p>Belum ada riwayat pembayaran</p>
                                </div>
                                @endif
                            </div>

                            <!-- Modal Footer -->
                            <div class="flex items-center justify-end p-6 border-t border-gray-700 bg-gray-900/50 gap-3">
                                <button onclick="closeDetails({{ $pendaftaran->id }})" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors duration-200">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-20">
                    <div class="inline-flex items-center justify-center h-24 w-24 bg-gray-700/50 rounded-full mb-6">
                        <i class="bi bi-inbox text-4xl text-gray-400"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Belum Ada Pendaftaran</h2>
                    <p class="text-gray-400 mb-8">Daftarkan diri Anda di kursus untuk melihat status pembayaran</p>
                    <a href="/peserta/kursus" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-lg transform hover:scale-105 transition-all duration-200">
                        <i class="bi bi-plus-circle mr-2"></i>
                        <span>Cari Kursus</span>
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-20 text-gray-400">
                <p>Data peserta tidak ditemukan. Hubungi administrator.</p>
            </div>
        @endif
    </div>
</div>

<script>
// Ensure all modals are hidden on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.classList.add('hidden');
    });
});

function filterStatus(status) {
    // Update active tab
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('border-yellow-400');
        btn.classList.add('border-transparent', 'text-gray-400');
    });
    event.target.closest('button').classList.add('border-yellow-400');
    event.target.closest('button').classList.remove('border-transparent', 'text-gray-400');

    // Filter cards
    document.querySelectorAll('.registration-card').forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

function showDetails(id) {
    const modal = document.getElementById('detailModal' + id);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeDetails(id) {
    const modal = document.getElementById('detailModal' + id);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Close modal on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.classList.add('hidden');
        });
        document.body.style.overflow = 'auto';
    }
});

// Payment function using Midtrans Snap
function startPayment(pendaftaranId) {
    // Find the card element
    const card = document.querySelector(`[data-pendaftaran-id="${pendaftaranId}"]`);
    if (!card) {
        alert('Data pendaftaran tidak ditemukan');
        return;
    }

    const sisa = parseInt(card.dataset.sisa) || 0;
    if (sisa <= 0) {
        alert('Tidak ada sisa pembayaran');
        return;
    }

    // Show loading state
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="bi bi-hourglass-split animate-spin mr-1"></i>Memproses...';

    // Send AJAX request to get snap token
    fetch(`/peserta/pembayaran-online/${pendaftaranId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({
            amount: sisa
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert('Error: ' + data.error);
            button.disabled = false;
            button.innerHTML = originalText;
            return;
        }

        // Open Midtrans Snap popup
        snap.pay(data.snap_token, {
            onSuccess: function(result) {
                console.log('Payment success:', result);
                window.location.href = '/peserta/pembayaran-success/' + data.order_id;
            },
            onPending: function(result) {
                console.log('Waiting for payment:', result);
            },
            onError: function(result) {
                console.log('Payment failed:', result);
                alert('Pembayaran gagal. Silakan coba lagi.');
                button.disabled = false;
                button.innerHTML = originalText;
            },
            onClose: function() {
                console.log('Customer closed the popup');
                button.disabled = false;
                button.innerHTML = originalText;
            }
        });
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + error.message);
        button.disabled = false;
        button.innerHTML = originalText;
    });
}
</script>

<style>
    .modal-overlay.hidden {
        display: none !important;
    }
</style>
@endsection
