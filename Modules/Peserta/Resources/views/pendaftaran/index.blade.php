@extends('peserta::layouts.student')

@section('title', 'Pendaftaran saya')
@section('page-context', 'Peserta · Pendaftaran')
@section('page-description', 'Hasil tes penempatan, kelas yang didapat, dan tagihan tiap program yang Anda daftar.')

@section('content')

@if (session('success'))
    <div class="bk-note bk-note--baik">
        <i class="bi bi-check-circle-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="bk-note bk-note--perlu">
        <i class="bi bi-exclamation-triangle-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if ($pendaftarans->isEmpty())
    <section class="bk-panel">
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-clipboard-x" aria-hidden="true"></i></span>
            <h3>Belum ada pendaftaran</h3>
            <p>Anda mendaftar ke program dulu, bukan langsung ke kelas. Kelas ditentukan setelah hasil tes penempatan masuk.</p>
            <a href="{{ route('peserta.program.index') }}" class="bk-btn bk-btn--pri bk-btn--sm">
                <i class="bi bi-compass" aria-hidden="true"></i> Telusuri program
            </a>
        </div>
    </section>
@else
    @foreach ($pendaftarans as $p)
        @php
            $bisaBayar = $p->canBePaid() && $p->sisa() > 0;
            $rupa = match ($p->status_pendaftaran) {
                \App\Models\Pendaftaran::STATUS_AKTIF => '',
                \App\Models\Pendaftaran::STATUS_SELESAI => 'bk-tag--info',
                \App\Models\Pendaftaran::STATUS_DIBATALKAN => 'bk-tag--gagal',
                \App\Models\Pendaftaran::STATUS_MENUNGGU_PEMBAYARAN => 'bk-tag--perlu',
                default => 'bk-tag--diam',
            };
        @endphp

        <section class="bk-panel">
            <div class="bk-panel__head">
                <div>
                    <h2 class="bk-panel__title">{{ $p->program->nama ?? 'Program sudah dihapus' }}</h2>
                    <p class="bk-panel__subtitle">
                        <span class="bk-code">{{ $p->nomor }}</span> ·
                        terdaftar atas {{ $p->participant_email_snapshot ?? auth()->user()->email }}
                    </p>
                </div>
                <span class="bk-tag {{ $rupa }}">{{ $p->label_status }}</span>
            </div>

            <div class="bk-panel__body">
                <div class="bk-note {{ $p->status_pendaftaran === \App\Models\Pendaftaran::STATUS_MENUNGGU_PEMBAYARAN ? 'bk-note--perlu' : 'bk-note--baik' }}">
                    <i class="bi bi-signpost-split-fill bk-note__icon" aria-hidden="true"></i>
                    <span>{{ $p->petunjuk }}</span>
                </div>

                <dl class="bk-kv">
                    <div>
                        <dt>Nilai tes penempatan</dt>
                        <dd>{{ optional($p->placementScore)->final_score ?? 'Belum diinput admin' }}</dd>
                    </div>
                    <div>
                        <dt>Level</dt>
                        <dd>{{ $p->level->nama ?? 'Belum ditentukan' }}</dd>
                    </div>
                    <div>
                        <dt>Kelas</dt>
                        <dd>{{ $p->kursus->nama ?? 'Belum ditempatkan' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bk-duo bk-duo--even" style="margin:0">
                <div class="bk-panel__body">
                    <h3 class="bk-eyebrow">Tagihan kelas</h3>
                    <dl class="bk-facts">
                        <div><dt>Biaya kelas</dt><dd class="bk-num">Rp {{ number_format($p->total_bayar, 0, ',', '.') }}</dd></div>
                        <div><dt>Sudah terbayar</dt><dd class="bk-num">Rp {{ number_format($p->terbayar, 0, ',', '.') }}</dd></div>
                        <div><dt>Sisa</dt><dd class="bk-num">Rp {{ number_format($p->sisa(), 0, ',', '.') }}</dd></div>
                        <div><dt>Status bayar</dt><dd>{{ $p->label_pembayaran }}</dd></div>
                    </dl>

                    <div style="padding:0 var(--bk-pad-x)">
                        <div class="bk-row" style="padding-left:0;padding-right:0;border:0">
                            <span class="bk-muted bk-row__sp">Pelunasan</span>
                            <span class="bk-num">{{ $p->progress() }}%</span>
                        </div>
                        <span class="bk-meter" role="img"
                              aria-label="Pelunasan {{ $p->progress() }} persen dari total tagihan">
                            <i class="{{ $p->progress() >= 100 ? '' : 'is-perlu' }}" style="width: {{ min(100, $p->progress()) }}%"></i>
                        </span>
                    </div>
                </div>

                <div class="bk-panel__body">
                    <h3 class="bk-eyebrow">Riwayat pembayaran</h3>
                    @forelse ($p->payments->sortByDesc('id')->take(3) as $bayar)
                        <div class="bk-row" style="padding-left:0;padding-right:0">
                            <span class="bk-row__sp">
                                <b class="bk-num">Rp {{ number_format($bayar->amount, 0, ',', '.') }}</b><br>
                                <span class="bk-muted">{{ optional($bayar->created_at)->translatedFormat('j M Y, H:i') ?? '—' }}</span>
                            </span>
                            <span class="bk-tag {{ $bayar->status === 'success' ? '' : ($bayar->status === 'failed' ? 'bk-tag--gagal' : 'bk-tag--diam') }}">
                                {{ $bayar->label_status }}
                            </span>
                        </div>
                    @empty
                        <p class="bk-hint" style="padding:0">Belum ada transaksi untuk pendaftaran ini.</p>
                    @endforelse
                </div>
            </div>

            <div class="bk-panel__foot">
                <span class="bk-muted">
                    @if ($bisaBayar)
                        Pembayaran diproses Midtrans. Tagihan yang dibuat sebesar sisa yang belum lunas.
                    @else
                        Tidak ada tagihan terbuka pada pendaftaran ini.
                    @endif
                </span>
                <span class="bk-row" style="border:0">
                    @if ($p->kursus)
                        <a href="{{ route('peserta.kursus.detail', $p->kursus) }}" class="bk-btn bk-btn--sm">
                            <i class="bi bi-journal-bookmark" aria-hidden="true"></i> Buka kelas
                        </a>
                    @endif
                    @if ($bisaBayar)
                        {{-- Ditangani penyimak bersama di peserta::partials.bayar --}}
                        <button type="button" class="bk-btn bk-btn--pri bk-btn--sm"
                                data-bk-bayar
                                data-pendaftaran="{{ $p->id }}"
                                data-tagihan="{{ $p->sisa() }}">
                            <i class="bi bi-credit-card" aria-hidden="true"></i>
                            Bayar Rp {{ number_format($p->sisa(), 0, ',', '.') }}
                        </button>
                    @endif
                </span>
            </div>
        </section>
    @endforeach
@endif
@endsection
