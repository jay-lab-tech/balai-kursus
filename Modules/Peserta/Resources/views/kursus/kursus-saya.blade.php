@extends('peserta::layouts.student')

@section('title', 'Kelas saya')
@section('page-context', 'Peserta · Kelas saya')
@section('page-description', 'Kelas yang sudah Anda tempati setelah hasil tes penempatan diproses admin.')

@section('content')

<div class="bk-panel__head" style="border:0;padding-left:0;padding-right:0">
    <div>
        <h1 class="bk-panel__title">Kelas saya</h1>
        <p class="bk-panel__subtitle">Kelas muncul di sini begitu admin menempatkan pendaftaran Anda ke satu rombongan.</p>
    </div>
    <a href="{{ route('peserta.program.index') }}" class="bk-btn bk-btn--sm">
        <i class="bi bi-compass" aria-hidden="true"></i> Lihat program
    </a>
</div>

@if ($pendaftarans->isEmpty())
    <section class="bk-panel">
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-door-closed" aria-hidden="true"></i></span>
            <h3>Belum ditempatkan di kelas mana pun</h3>
            <p>Penempatan dilakukan setelah nilai tes penempatan masuk. Status prosesnya bisa Anda pantau di halaman pendaftaran.</p>
            <a href="{{ route('peserta.pendaftaran.index') }}" class="bk-btn bk-btn--pri bk-btn--sm">
                <i class="bi bi-clipboard-check" aria-hidden="true"></i> Lihat status pendaftaran
            </a>
        </div>
    </section>
@else
    <div class="bk-duo bk-duo--even">
        @foreach ($pendaftarans as $pendaftaran)
            @php $sisa = $pendaftaran->sisa(); @endphp
            <section class="bk-panel">
                <div class="bk-panel__head">
                    <div>
                        <p class="bk-eyebrow">{{ $pendaftaran->program->nama ?? 'Program sudah dihapus' }}</p>
                        <h2 class="bk-panel__title">{{ $pendaftaran->kursus->nama ?? 'Kelas sudah dihapus' }}</h2>
                    </div>
                    <span class="bk-tag {{ $pendaftaran->isLunas() ? '' : ($sisa > 0 ? 'bk-tag--perlu' : 'bk-tag--diam') }}">
                        {{ $pendaftaran->label_pembayaran }}
                    </span>
                </div>

                <dl class="bk-facts">
                    <div><dt>Level</dt><dd>{{ $pendaftaran->level->nama ?? 'Belum ada level' }}</dd></div>
                    <div><dt>Periode</dt><dd>{{ $pendaftaran->kursus->periode ?? 'Belum diatur' }}</dd></div>
                    <div>
                        <dt>Mulai</dt>
                        <dd>{{ optional($pendaftaran->kursus)->tanggal_mulai?->translatedFormat('j F Y') ?? 'Belum diatur' }}</dd>
                    </div>
                    <div>
                        <dt>Pelunasan</dt>
                        <dd>
                            <span class="bk-num">{{ $pendaftaran->progress() }}%</span>
                            <span class="bk-meter" role="img"
                                  aria-label="Pelunasan {{ $pendaftaran->progress() }} persen">
                                <i class="{{ $pendaftaran->progress() >= 100 ? '' : 'is-perlu' }}"
                                   style="width: {{ min(100, $pendaftaran->progress()) }}%"></i>
                            </span>
                        </dd>
                    </div>
                </dl>

                <div class="bk-panel__foot">
                    <span class="bk-muted">
                        @if ($sisa > 0)
                            Sisa Rp {{ number_format($sisa, 0, ',', '.') }}
                        @else
                            Tidak ada tagihan terbuka
                        @endif
                    </span>
                    <span class="bk-row" style="border:0">
                        <a href="{{ route('peserta.kursus.risalah', $pendaftaran->kursus) }}" class="bk-btn bk-btn--sm">
                            <i class="bi bi-journal-text" aria-hidden="true"></i> Risalah
                        </a>
                        @if ($pendaftaran->canBePaid() && $sisa > 0)
                            {{-- Ditangani penyimak bersama di peserta::partials.bayar --}}
                            <button type="button" class="bk-btn bk-btn--pri bk-btn--sm"
                                    data-bk-bayar
                                    data-pendaftaran="{{ $pendaftaran->id }}"
                                    data-tagihan="{{ $sisa }}">
                                <i class="bi bi-credit-card" aria-hidden="true"></i> Bayar
                            </button>
                        @else
                            <a href="{{ route('peserta.kursus.detail', $pendaftaran->kursus) }}" class="bk-btn bk-btn--pri bk-btn--sm">
                                <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> Masuk kelas
                            </a>
                        @endif
                    </span>
                </div>
            </section>
        @endforeach
    </div>
@endif
@endsection
