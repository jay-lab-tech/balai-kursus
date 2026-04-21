@extends('peserta::layouts.student')

@section('title', 'Pendaftaran Saya - Balai Kursus')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black py-8 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="text-4xl font-bold text-white">
                    <i class="bi bi-clipboard-check text-yellow-400 mr-3"></i>Pendaftaran Saya
                </h1>
                <p class="mt-2 max-w-3xl text-gray-400">
                    Pantau hasil placement test, level, kelas yang didapat, dan selesaikan pembayaran kelas langsung dari halaman ini.
                </p>
            </div>
            <a href="{{ route('peserta.program.index') }}" class="inline-flex items-center rounded-xl bg-white/10 px-5 py-3 font-semibold text-white hover:bg-white/20 transition">
                <i class="bi bi-plus-circle mr-2"></i>Daftar Program Lain
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-2xl border border-green-500/30 bg-green-500/10 px-5 py-4 text-green-100">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl border border-yellow-500/30 bg-yellow-500/10 px-5 py-4 text-yellow-100">
                {{ session('error') }}
            </div>
        @endif

        @if($pendaftarans->isEmpty())
            <div class="rounded-3xl border border-dashed border-white/10 px-8 py-16 text-center text-gray-400">
                Anda belum memiliki pendaftaran program.
            </div>
        @else
            <div class="space-y-6">
                @foreach($pendaftarans as $pendaftaran)
                    @php
                        $statusPendaftaran = str_replace('_', ' ', ucfirst($pendaftaran->status_pendaftaran));
                        $statusBayar = strtoupper($pendaftaran->status_pembayaran);
                        $isPayable = $pendaftaran->canBePaid() && $pendaftaran->sisa() > 0;
                    @endphp

                    <article class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-2xl">
                        <div class="grid gap-0 xl:grid-cols-[1.15fr_0.85fr]">
                            <div class="border-b border-white/10 p-6 xl:border-b-0 xl:border-r">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.25em] text-gray-400">{{ $pendaftaran->nomor }}</p>
                                        <h2 class="mt-3 text-3xl font-bold text-white">{{ $pendaftaran->program->nama ?? 'Program tidak ditemukan' }}</h2>
                                        <p class="mt-2 text-sm text-gray-400">
                                            Email terdaftar:
                                            <span class="font-medium text-white">{{ $pendaftaran->participant_email_snapshot ?? auth()->user()?->email ?? '-' }}</span>
                                        </p>
                                        <p class="mt-3 text-sm leading-7 text-gray-400">
                                            Setelah nilai placement test diinput admin, sistem menempatkan Anda ke level dan kelas yang paling sesuai.
                                        </p>
                                    </div>
                                    <span class="inline-flex w-fit items-center rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-white">
                                        {{ $statusPendaftaran }}
                                    </span>
                                </div>

                                <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                                    <div class="rounded-2xl bg-black/20 p-4">
                                        <p class="text-xs uppercase tracking-wider text-gray-400">Status</p>
                                        <p class="mt-2 text-base font-semibold text-white">{{ $statusPendaftaran }}</p>
                                    </div>
                                    <div class="rounded-2xl bg-black/20 p-4">
                                        <p class="text-xs uppercase tracking-wider text-gray-400">Nilai Tes Masuk</p>
                                        <p class="mt-2 text-base font-semibold text-white">{{ $pendaftaran->placementScore?->final_score ?? 'Belum diinput' }}</p>
                                    </div>
                                    <div class="rounded-2xl bg-black/20 p-4">
                                        <p class="text-xs uppercase tracking-wider text-gray-400">Level</p>
                                        <p class="mt-2 text-base font-semibold text-white">{{ $pendaftaran->level->nama ?? 'Belum ditentukan' }}</p>
                                    </div>
                                    <div class="rounded-2xl bg-black/20 p-4">
                                        <p class="text-xs uppercase tracking-wider text-gray-400">Kelas / Kursus</p>
                                        <p class="mt-2 text-base font-semibold text-white">{{ $pendaftaran->kursus->nama ?? 'Belum ditempatkan' }}</p>
                                    </div>
                                </div>

                                <div class="mt-6 rounded-2xl border border-white/10 bg-black/20 p-4 text-sm leading-7 text-gray-300">
                                    @if($pendaftaran->status_pendaftaran === \App\Models\Pendaftaran::STATUS_MENUNGGU_TES)
                                        Menunggu admin memasukkan hasil tes penempatan.
                                    @elseif($pendaftaran->status_pendaftaran === \App\Models\Pendaftaran::STATUS_MENUNGGU_PENEMPATAN)
                                        Hasil tes sudah masuk, tetapi kelas yang sesuai masih belum tersedia atau sedang penuh.
                                    @elseif($pendaftaran->status_pendaftaran === \App\Models\Pendaftaran::STATUS_MENUNGGU_PEMBAYARAN)
                                        Kelas sudah ditentukan. Saat ini Anda bisa langsung membayar sisa tagihan kelas.
                                    @elseif($pendaftaran->status_pendaftaran === \App\Models\Pendaftaran::STATUS_AKTIF)
                                        Pendaftaran aktif dan kelas sudah bisa diikuti.
                                    @else
                                        Status pendaftaran saat ini: {{ $pendaftaran->status_pendaftaran }}.
                                    @endif
                                </div>
                            </div>

                            <aside class="p-6">
                                <div class="rounded-2xl border border-white/10 bg-black/20 p-5">
                                    <p class="text-xs uppercase tracking-wider text-gray-400">Ringkasan Pembayaran Kelas</p>
                                    <div class="mt-5 space-y-3 text-sm text-gray-300">
                                        <div class="flex items-center justify-between gap-4">
                                            <span>Biaya Kelas</span>
                                            <span class="text-lg font-semibold text-white">Rp {{ number_format($pendaftaran->total_bayar, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between gap-4">
                                            <span>Terbayar</span>
                                            <span class="text-lg font-semibold text-white">Rp {{ number_format($pendaftaran->terbayar, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between gap-4">
                                            <span>Sisa Tagihan</span>
                                            <span class="text-lg font-semibold text-white">Rp {{ number_format($pendaftaran->sisa(), 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between gap-4">
                                            <span>Status Bayar</span>
                                            <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase text-white">{{ $statusBayar }}</span>
                                        </div>
                                    </div>

                                    <div class="mt-5">
                                        <div class="mb-2 flex items-center justify-between text-xs text-gray-400">
                                            <span>Progress</span>
                                            <span>{{ $pendaftaran->progress() }}%</span>
                                        </div>
                                        <div class="h-3 overflow-hidden rounded-full bg-gray-700">
                                            <div class="h-full bg-gradient-to-r from-yellow-400 to-red-500" style="width: {{ $pendaftaran->progress() }}%"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 grid gap-3">
                                    @if($isPayable)
                                        <button
                                            type="button"
                                            data-pendaftaran-id="{{ $pendaftaran->id }}"
                                            data-amount="{{ $pendaftaran->sisa() }}"
                                            class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-red-600 to-red-700 px-5 py-3 font-semibold text-white hover:from-red-500 hover:to-red-600 transition"
                                            onclick="startPayment(this)"
                                        >
                                            <i class="bi bi-credit-card mr-2"></i>Bayar Langsung via Midtrans
                                        </button>
                                        <p class="text-xs leading-6 text-gray-400">
                                            Tombol ini langsung membuat tagihan sebesar sisa pembayaran kelas Anda.
                                        </p>
                                    @endif

                                    @if($pendaftaran->kursus)
                                        <a href="{{ route('peserta.kursus.detail', $pendaftaran->kursus) }}" class="inline-flex items-center justify-center rounded-xl bg-white/10 px-5 py-3 font-semibold text-white hover:bg-white/20 transition">
                                            <i class="bi bi-book mr-2"></i>Buka Detail Kelas
                                        </a>
                                    @endif
                                </div>

                                @if($pendaftaran->payments->isNotEmpty())
                                    <div class="mt-5 rounded-2xl border border-white/10 bg-black/20 p-5">
                                        <p class="text-xs uppercase tracking-wider text-gray-400">Riwayat Pembayaran Kelas</p>
                                        <div class="mt-4 space-y-3">
                                            @foreach($pendaftaran->payments->sortByDesc('id')->take(3) as $payment)
                                                <div class="rounded-xl bg-white/5 px-4 py-3 text-sm text-gray-200">
                                                    <div class="flex items-center justify-between">
                                                        <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                                        <span class="rounded-full bg-white/10 px-3 py-1 text-xs uppercase">{{ $payment->status }}</span>
                                                    </div>
                                                    <p class="mt-1 text-xs text-gray-400">{{ $payment->description }}</p>
                                                    <p class="mt-1 text-xs text-gray-400">{{ $payment->created_at->format('d M Y H:i') }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </aside>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
function startPayment(button) {
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
