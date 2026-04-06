@extends('peserta::layouts.student')

@section('title', 'Kelas Saya - Balai Kursus')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="text-4xl font-bold text-white">
                    <i class="bi bi-door-open text-yellow-400 mr-3"></i>Kelas Saya
                </h1>
                <p class="mt-2 text-gray-400">Daftar kelas yang sudah Anda tempati setelah proses klasifikasi program selesai.</p>
            </div>
            <a href="{{ route('peserta.program.index') }}" class="inline-flex items-center rounded-xl bg-white/10 px-5 py-3 font-semibold text-white hover:bg-white/20 transition">
                <i class="bi bi-diagram-3 mr-2"></i>Lihat Program
            </a>
        </div>

        @if($pendaftarans->isEmpty())
            <div class="rounded-3xl border border-dashed border-white/10 px-8 py-16 text-center text-gray-400">
                Anda belum ditempatkan ke kelas manapun.
            </div>
        @else
            <div class="grid gap-6 lg:grid-cols-2">
                @foreach($pendaftarans as $pendaftaran)
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.25em] text-gray-400">{{ $pendaftaran->program->nama ?? '-' }}</p>
                                <h2 class="mt-2 text-2xl font-bold text-white">{{ $pendaftaran->kursus->nama ?? '-' }}</h2>
                            </div>
                            <span class="rounded-full bg-white/10 px-4 py-2 text-xs font-semibold uppercase text-white">
                                {{ $pendaftaran->status_pembayaran }}
                            </span>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl bg-black/20 p-4">
                                <p class="text-xs uppercase tracking-wider text-gray-400">Level</p>
                                <p class="mt-2 font-semibold text-white">{{ $pendaftaran->level->nama ?? 'Belum ada level' }}</p>
                            </div>
                            <div class="rounded-2xl bg-black/20 p-4">
                                <p class="text-xs uppercase tracking-wider text-gray-400">Periode</p>
                                <p class="mt-2 font-semibold text-white">{{ $pendaftaran->kursus->periode ?? 'Belum diatur' }}</p>
                            </div>
                            <div class="rounded-2xl bg-black/20 p-4">
                                <p class="text-xs uppercase tracking-wider text-gray-400">Tanggal Mulai</p>
                                <p class="mt-2 font-semibold text-white">{{ optional($pendaftaran->kursus->tanggal_mulai)->format('d M Y') ?? 'Belum diatur' }}</p>
                            </div>
                            <div class="rounded-2xl bg-black/20 p-4">
                                <p class="text-xs uppercase tracking-wider text-gray-400">Pembayaran</p>
                                <p class="mt-2 font-semibold text-white">{{ $pendaftaran->progress() }}%</p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('peserta.kursus.detail', $pendaftaran->kursus) }}" class="inline-flex flex-1 items-center justify-center rounded-xl bg-gradient-to-r from-red-600 to-red-700 px-5 py-3 font-semibold text-white hover:from-red-500 hover:to-red-600 transition">
                                <i class="bi bi-book mr-2"></i>Masuk Kelas
                            </a>
                            <a href="{{ route('peserta.kursus.risalah', $pendaftaran->kursus) }}" class="inline-flex flex-1 items-center justify-center rounded-xl bg-white/10 px-5 py-3 font-semibold text-white hover:bg-white/20 transition">
                                <i class="bi bi-journal-text mr-2"></i>Lihat Risalah
                            </a>
                        </div>

                        @if($pendaftaran->canBePaid() && $pendaftaran->sisa() > 0)
                            <div class="mt-3">
                                <button
                                    type="button"
                                    data-pendaftaran-id="{{ $pendaftaran->id }}"
                                    data-amount="{{ $pendaftaran->sisa() }}"
                                    class="inline-flex w-full items-center justify-center rounded-xl bg-white/10 px-5 py-3 font-semibold text-white hover:bg-white/20 transition"
                                    onclick="payAssignedClass(this)"
                                >
                                    <i class="bi bi-credit-card mr-2"></i>Bayar Kelas Langsung
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
function payAssignedClass(button) {
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
