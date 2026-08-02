@extends('peserta::layouts.student')

@section('title', $kursus->nama)
@section('page-context', 'Peserta · Kelas')
@section('page-description', 'Rincian kelas, catatan tiap pertemuan, dan posisi pembayaran Anda.')

@section('content')

<a href="{{ route('peserta.kursus.saya') }}" class="bk-linkbtn">
    <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke kelas saya
</a>

<div class="bk-duo">
    <div>
        <section class="bk-panel">
            <div class="bk-panel__head">
                <div>
                    <p class="bk-eyebrow">{{ $kursus->program->nama ?? 'Program sudah dihapus' }}</p>
                    <h1 class="bk-panel__title">{{ $kursus->nama }}</h1>
                </div>
                <span class="bk-tag {{ $kursus->status === 'selesai' ? 'bk-tag--info' : ($kursus->status === 'berjalan' ? 'bk-tag--jalan' : 'bk-tag--diam') }}">
                    {{ ucfirst($kursus->status) }}
                </span>
            </div>

            <dl class="bk-facts">
                <div><dt>Level Anda</dt><dd>{{ $pendaftaran->level->nama ?? 'Belum ada level' }}</dd></div>
                <div>
                    <dt>Pengajar</dt>
                    <dd>
                        {{-- Satu level bisa diampu lebih dari satu instruktur; dulu hanya yang pertama tampil. --}}
                        {{ $instrukturs->isEmpty() ? 'Belum diatur' : $instrukturs->join(', ', ' dan ') }}
                    </dd>
                </div>
                <div><dt>Periode</dt><dd>{{ $kursus->periode ?? 'Belum diatur' }}</dd></div>
                <div>
                    <dt>Jadwal kelas</dt>
                    <dd>
                        {{ $kursus->tanggal_mulai?->translatedFormat('j M Y') ?? 'Belum diatur' }}
                        &ndash;
                        {{ $kursus->tanggal_selesai?->translatedFormat('j M Y') ?? 'belum diatur' }}
                    </dd>
                </div>
            </dl>
        </section>

        <section class="bk-panel">
            <div class="bk-panel__head">
                <div>
                    <h2 class="bk-panel__title">Risalah pertemuan</h2>
                    <p class="bk-panel__subtitle">Catatan yang diisi pengajar tiap kali kelas berlangsung, terbaru di atas.</p>
                </div>
                @if ($risalahs->isNotEmpty())
                    <a href="{{ route('peserta.kursus.risalah', $kursus) }}" class="bk-linkbtn">
                        Semua risalah <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>
                @endif
            </div>

            @forelse ($risalahs->take(5) as $risalah)
                <div class="bk-panel__body" @unless ($loop->last) style="border-bottom:1px solid var(--bk-sand-100)" @endunless>
                    <div class="bk-row" style="padding:0;border:0">
                        <span class="bk-row__sp">
                            <b>Pertemuan {{ $risalah->pertemuan_ke }}</b>
                            <span class="bk-muted">
                                · {{ $risalah->tgl_pertemuan?->translatedFormat('j F Y') ?? 'tanggal belum diatur' }}
                                · {{ $risalah->jumlah_hadir }} hadir
                            </span>
                        </span>
                        @if ($risalah->dokumen)
                            <a href="{{ route('instruktur.risalah.download', $risalah) }}" class="bk-iconbtn"
                               title="Unduh dokumen pertemuan {{ $risalah->pertemuan_ke }}">
                                <i class="bi bi-download" aria-hidden="true"></i>
                            </a>
                        @endif
                    </div>
                    <p class="bk-hint" style="padding:.5rem 0 0">
                        {{ $risalah->materi ?: 'Materi belum dicatat.' }}
                    </p>
                </div>
            @empty
                <div class="bk-empty">
                    <span class="bk-empty__icon"><i class="bi bi-journal" aria-hidden="true"></i></span>
                    <h3>Belum ada risalah</h3>
                    <p>Catatan pertemuan muncul setelah pengajar mengisinya seusai kelas.</p>
                </div>
            @endforelse
        </section>
    </div>

    <div>
        <section class="bk-panel">
            <div class="bk-panel__head">
                <div>
                    <h2 class="bk-panel__title">Pembayaran</h2>
                    <p class="bk-panel__subtitle">{{ $pendaftaran->label_pembayaran }}</p>
                </div>
            </div>

            <dl class="bk-facts">
                <div><dt>Total</dt><dd class="bk-num">Rp {{ number_format($pendaftaran->total_bayar, 0, ',', '.') }}</dd></div>
                <div><dt>Terbayar</dt><dd class="bk-num">Rp {{ number_format($pendaftaran->terbayar, 0, ',', '.') }}</dd></div>
                <div><dt>Sisa</dt><dd class="bk-num">Rp {{ number_format($pendaftaran->sisa(), 0, ',', '.') }}</dd></div>
            </dl>

            <div class="bk-panel__body" style="padding-top:0">
                <div class="bk-row" style="padding:0 0 .4rem;border:0">
                    <span class="bk-muted bk-row__sp">Pelunasan</span>
                    <span class="bk-num">{{ $pendaftaran->progress() }}%</span>
                </div>
                <span class="bk-meter" role="img" aria-label="Pelunasan {{ $pendaftaran->progress() }} persen dari total tagihan">
                    <i class="{{ $pendaftaran->progress() >= 100 ? '' : 'is-perlu' }}"
                       style="width: {{ min(100, $pendaftaran->progress()) }}%"></i>
                </span>
            </div>

            <div class="bk-panel__foot">
                @if ($pendaftaran->canBePaid() && $pendaftaran->sisa() > 0)
                    <span class="bk-muted">Diproses lewat Midtrans.</span>
                    {{-- Ditangani penyimak bersama di peserta::partials.bayar --}}
                    <button type="button" class="bk-btn bk-btn--pri bk-btn--sm"
                            data-bk-bayar
                            data-pendaftaran="{{ $pendaftaran->id }}"
                            data-tagihan="{{ $pendaftaran->sisa() }}">
                        <i class="bi bi-credit-card" aria-hidden="true"></i>
                        Bayar Rp {{ number_format($pendaftaran->sisa(), 0, ',', '.') }}
                    </button>
                @else
                    <span class="bk-muted">Tidak ada tagihan terbuka.</span>
                    <a href="{{ route('peserta.riwayat.index') }}" class="bk-btn bk-btn--sm">
                        <i class="bi bi-receipt" aria-hidden="true"></i> Riwayat
                    </a>
                @endif
            </div>
        </section>

        <section class="bk-panel">
            <div class="bk-panel__head">
                <div><h2 class="bk-panel__title">Pintasan</h2></div>
            </div>
            <a href="{{ route('peserta.kursus.risalah', $kursus) }}" class="bk-row">
                <i class="bi bi-journal-text" aria-hidden="true"></i>
                <span class="bk-row__sp">Semua risalah kelas ini</span>
                <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
            <a href="{{ route('peserta.pendaftaran.index') }}" class="bk-row">
                <i class="bi bi-clipboard-check" aria-hidden="true"></i>
                <span class="bk-row__sp">Status pendaftaran</span>
                <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
            <a href="{{ route('profile.certificates') }}" class="bk-row">
                <i class="bi bi-patch-check" aria-hidden="true"></i>
                <span class="bk-row__sp">Sertifikat</span>
                <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
        </section>
    </div>
</div>
@endsection
