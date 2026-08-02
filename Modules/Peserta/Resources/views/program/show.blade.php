@extends('peserta::layouts.student')

@section('title', $program->nama)
@section('page-context', 'Peserta · Program')
@section('page-description', 'Struktur level dan kelas pada program '.$program->nama.'.')

@section('content')

<a href="{{ route('peserta.program.index') }}" class="bk-linkbtn">
    <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke daftar program
</a>

<div class="bk-hello">
    <p class="bk-hello__kicker">Program</p>
    <h1 class="bk-hello__title">{{ $program->nama }}</h1>
    <p class="bk-hello__lede">
        Anda mendaftar ke program, bukan ke kelas. Tes penempatan dilakukan di luar sistem, hasilnya dimasukkan
        admin, lalu Anda ditempatkan ke kelas selevel yang kuotanya masih ada.
    </p>
    <div class="bk-hello__actions">
        @if (! $registration)
            <form action="{{ route('peserta.program.daftar', $program) }}" method="POST">
                @csrf
                <button type="submit" class="bk-btn bk-btn--pri bk-btn--sm">
                    <i class="bi bi-check2-circle" aria-hidden="true"></i> Daftar program ini
                </button>
            </form>
        @else
            <a href="{{ route('peserta.pendaftaran.index') }}" class="bk-btn bk-btn--pri bk-btn--sm">
                <i class="bi bi-clipboard-check" aria-hidden="true"></i> Lihat status pendaftaran
            </a>
        @endif
        <a href="{{ route('peserta.kursus.saya') }}" class="bk-btn bk-btn--sm">
            <i class="bi bi-door-open" aria-hidden="true"></i> Kelas saya
        </a>
    </div>
</div>

<div class="bk-duo">
    <section class="bk-panel">
        <div class="bk-panel__head">
            <div>
                <h2 class="bk-panel__title">Struktur level dan kelas</h2>
                <p class="bk-panel__subtitle">Kuota di bawah dihitung dari jumlah pendaftaran yang sudah masuk tiap kelas.</p>
            </div>
        </div>

        @forelse ($program->kursuses->groupBy(fn ($kursus) => $kursus->level?->nama ?? 'Tanpa level') as $namaLevel => $daftarKelas)
            @php $rentang = $daftarKelas->first()?->level?->rentang_nilai; @endphp
            <div class="bk-panel__body" @unless ($loop->last) style="border-bottom:1px solid var(--bk-sand-100)" @endunless>
                <div class="bk-row" style="padding:0 0 .6rem">
                    <span class="bk-row__sp">
                        <b>{{ $namaLevel }}</b>
                        @if ($rentang)
                            <span class="bk-muted">· rekomendasi skor {{ $rentang }}</span>
                        @endif
                    </span>
                    <span class="bk-muted bk-num">{{ $daftarKelas->count() }} kelas</span>
                </div>

                <table class="bk-table is-padat">
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Periode</th>
                            <th class="r nw">Sisa kuota</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarKelas as $kelas)
                            <tr>
                                <td>
                                    <b>{{ $kelas->nama }}</b>
                                    @unless ($kelas->masihMenerima())
                                        <span class="bk-tag bk-tag--diam">tutup</span>
                                    @endunless
                                </td>
                                <td>
                                    {{ $kelas->periode ?: 'belum diatur' }}
                                    @if ($kelas->tanggal_mulai)
                                        <br><span class="bk-muted">mulai {{ $kelas->tanggal_mulai->translatedFormat('j M Y') }}</span>
                                    @endif
                                </td>
                                <td class="r nw bk-num">{{ $kelas->sisaKuota() }} / {{ $kelas->kuota }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <div class="bk-empty">
                <span class="bk-empty__icon"><i class="bi bi-diagram-3" aria-hidden="true"></i></span>
                <h3>Belum ada kelas</h3>
                <p>Program ini belum punya kelas yang dikaitkan, jadi penempatan belum bisa dilakukan.</p>
            </div>
        @endforelse
    </section>

    <div>
        @if ($registration)
            <section class="bk-panel">
                <div class="bk-panel__head">
                    <div>
                        <h2 class="bk-panel__title">Status Anda</h2>
                        <p class="bk-panel__subtitle">{{ $registration->label_status }}</p>
                    </div>
                </div>
                <dl class="bk-facts">
                    <div><dt>Nomor</dt><dd><span class="bk-code">{{ $registration->nomor }}</span></dd></div>
                    <div>
                        <dt>Nilai tes</dt>
                        <dd>{{ optional($registration->placementScore)->final_score ?? 'Belum diinput' }}</dd>
                    </div>
                    <div><dt>Level</dt><dd>{{ $registration->level->nama ?? 'Belum ditentukan' }}</dd></div>
                    <div><dt>Kelas</dt><dd>{{ $registration->kursus->nama ?? 'Belum ditempatkan' }}</dd></div>
                </dl>
                <div class="bk-panel__body" style="padding-top:0">
                    @include('peserta::partials.alur', ['pendaftaran' => $registration])
                </div>
            </section>
        @else
            <section class="bk-panel">
                <div class="bk-panel__head">
                    <div>
                        <h2 class="bk-panel__title">Alur pendaftaran</h2>
                        <p class="bk-panel__subtitle">Empat tahap sebelum kelas bisa diikuti.</p>
                    </div>
                </div>
                <ol class="bk-steps">
                    <li><b>Daftar program</b><small>Tanpa memilih kelas terlebih dahulu.</small></li>
                    <li><b>Tes penempatan</b><small>Dilakukan di luar sistem, hasilnya dimasukkan admin.</small></li>
                    <li><b>Penempatan kelas</b><small>Sistem mencari kelas selevel yang kuotanya masih ada.</small></li>
                    <li><b>Pembayaran</b><small>Tagihan terbit setelah kelas ditentukan.</small></li>
                </ol>
            </section>
        @endif
    </div>
</div>
@endsection
