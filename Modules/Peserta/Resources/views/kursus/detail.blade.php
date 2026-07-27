@extends('peserta::layouts.student')

@section('title', 'Detail Kelas - Balai Kursus')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl space-y-8">
        <a href="{{ route('peserta.kursus.saya') }}" class="inline-flex items-center text-sm text-yellow-300 hover:text-yellow-200">
            <i class="bi bi-arrow-left mr-2"></i>Kembali ke kelas saya
        </a>

        <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="space-y-6">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl">
                    <p class="text-xs uppercase tracking-[0.25em] text-gray-400">{{ $kursus->program->nama ?? '-' }}</p>
                    <h1 class="mt-3 text-4xl font-bold text-white">{{ $kursus->nama }}</h1>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-2xl bg-black/20 p-4">
                            <p class="text-xs uppercase tracking-wider text-gray-400">Level</p>
                            <p class="mt-2 font-semibold text-white">{{ $levelPeserta ?? 'Belum ada level' }}</p>
                        </div>
                        <div class="rounded-2xl bg-black/20 p-4">
                            <p class="text-xs uppercase tracking-wider text-gray-400">Instruktur</p>
                            <p class="mt-2 font-semibold text-white">{{ $instrukturPeserta ?? 'Belum diatur' }}</p>
                        </div>
                        <div class="rounded-2xl bg-black/20 p-4">
                            <p class="text-xs uppercase tracking-wider text-gray-400">Periode</p>
                            <p class="mt-2 font-semibold text-white">{{ $kursus->periode ?? 'Belum diatur' }}</p>
                        </div>
                        <div class="rounded-2xl bg-black/20 p-4">
                            <p class="text-xs uppercase tracking-wider text-gray-400">Status Kelas</p>
                            <p class="mt-2 font-semibold text-white">{{ ucfirst($kursus->status) }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl">
                    <h2 class="text-2xl font-bold text-white">Risalah dan Pertemuan</h2>
                    @if($risalahs->isEmpty())
                        <div class="mt-6 rounded-2xl border border-dashed border-white/10 px-6 py-12 text-center text-gray-400">
                            Belum ada risalah untuk kelas ini.
                        </div>
                    @else
                        <div class="mt-6 space-y-4">
                            @foreach($risalahs as $risalah)
                                <div class="rounded-2xl border border-white/10 bg-black/20 p-5">
                                    <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                        <div>
                                            <h3 class="text-xl font-semibold text-white">Pertemuan {{ $risalah->pertemuan_ke }}</h3>
                                            <p class="mt-1 text-sm text-gray-400">{{ optional($risalah->tgl_pertemuan)->format('d M Y') ?? 'Tanggal belum diatur' }}</p>
                                        </div>
                                        @if($risalah->dokumen)
                                            <a href="{{ route('instruktur.risalah.download', $risalah->id) }}" class="text-sm font-semibold text-yellow-300 hover:text-yellow-200">
                                                Download Dokumen
                                            </a>
                                        @endif
                                    </div>
                                    <div class="mt-4 space-y-3 text-sm text-gray-300">
                                        <p><span class="text-gray-400">Materi:</span> {{ $risalah->materi ?: 'Belum ada materi' }}</p>
                                        <p><span class="text-gray-400">Catatan:</span> {{ $risalah->catatan ?: 'Tidak ada catatan' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl">
                    <h2 class="text-xl font-bold text-white">Pembayaran</h2>
                    <div class="mt-5 space-y-4">
                        <div class="rounded-2xl bg-black/20 p-4">
                            <p class="text-xs uppercase tracking-wider text-gray-400">Status</p>
                            <p class="mt-2 font-semibold text-white">{{ strtoupper($pendaftaran->status_pembayaran) }}</p>
                        </div>
                        <div class="rounded-2xl bg-black/20 p-4">
                            <p class="text-xs uppercase tracking-wider text-gray-400">Total</p>
                            <p class="mt-2 font-semibold text-white">Rp {{ number_format($pendaftaran->total_bayar, 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-2xl bg-black/20 p-4">
                            <p class="text-xs uppercase tracking-wider text-gray-400">Terbayar</p>
                            <p class="mt-2 font-semibold text-white">Rp {{ number_format($pendaftaran->terbayar, 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-2xl bg-black/20 p-4">
                            <div class="mb-2 flex items-center justify-between text-xs uppercase tracking-wider text-gray-400">
                                <span>Progress</span>
                                <span>{{ $pendaftaran->progress() }}%</span>
                            </div>
                            <div class="h-3 overflow-hidden rounded-full bg-gray-700">
                                <div class="h-full bg-gradient-to-r from-yellow-400 to-sky-500" style="width: {{ $pendaftaran->progress() }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl">
                    <h2 class="text-xl font-bold text-white">Aksi</h2>
                    <div class="mt-5 space-y-3">
                        <a href="{{ route('peserta.kursus.risalah', $kursus) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-white/10 px-5 py-3 font-semibold text-white hover:bg-white/20 transition">
                            <i class="bi bi-journal-text mr-2"></i>Lihat Semua Risalah
                        </a>
                        @if($pendaftaran->canBePaid() && $pendaftaran->sisa() > 0)
                            <button
                                type="button"
                                data-pendaftaran-id="{{ $pendaftaran->id }}"
                                data-amount="{{ $pendaftaran->sisa() }}"
                                class="inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-sky-600 to-sky-700 px-5 py-3 font-semibold text-white hover:from-sky-500 hover:to-sky-600 transition"
                                onclick="startCoursePayment(this)"
                            >
                                <i class="bi bi-credit-card mr-2"></i>Bayar Kelas Ini
                            </button>
                        @else
                            <a href="{{ route('peserta.pendaftaran.index') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-sky-600 to-sky-700 px-5 py-3 font-semibold text-white hover:from-sky-500 hover:to-sky-600 transition">
                                <i class="bi bi-credit-card mr-2"></i>Cek Pembayaran
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function startCoursePayment(button) {
    const pendaftaranId = Number(button.dataset.pendaftaranId);
    const amount = Number(button.dataset.amount);

    if (!pendaftaranId || !amount || amount < 1) {
        alert('Tagihan kelas tidak valid.');
        return;
    }

    if (typeof snap === 'undefined') {
        alert('Midtrans Snap belum termuat. Muat ulang halaman lalu coba lagi.');
        return;
    }

    button.disabled = true;
    const originalHtml = button.innerHTML;
    button.innerHTML = '<i class="bi bi-arrow-repeat mr-2 animate-spin"></i>Memproses...';

    fetch(`/peserta/pembayaran-online/${pendaftaranId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({ amount: amount })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }

        snap.pay(data.snap_token, {
            onSuccess: function () {
                window.location.href = '/peserta/pembayaran-success/' + data.order_id;
            },
            onPending: function () {
                window.location.reload();
            },
            onError: function () {
                alert('Pembayaran gagal diproses.');
                button.disabled = false;
                button.innerHTML = originalHtml;
            },
            onClose: function () {
                button.disabled = false;
                button.innerHTML = originalHtml;
            }
        });
    })
    .catch(error => {
        alert(error.message);
        button.disabled = false;
        button.innerHTML = originalHtml;
    });
}
</script>
@endsection
