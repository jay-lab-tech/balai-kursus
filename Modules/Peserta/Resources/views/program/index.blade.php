@extends('peserta::layouts.student')

@section('title', 'Program tersedia')
@section('page-context', 'Peserta · Program')
@section('page-description', 'Anda mendaftar ke program dulu; level dan kelas ditentukan setelah tes penempatan.')

@section('content')

<div class="bk-panel__head" style="border:0;padding-left:0;padding-right:0">
    <div>
        <h1 class="bk-panel__title">Daftar program</h1>
        <p class="bk-panel__subtitle">
            Pilih program, ikuti tes penempatan, lalu admin memasukkan hasilnya. Penempatan kelas menyusul.
        </p>
    </div>
    <a href="{{ route('peserta.pendaftaran.index') }}" class="bk-btn bk-btn--sm">
        <i class="bi bi-clipboard-check" aria-hidden="true"></i> Pendaftaran saya
    </a>
</div>

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

@if ($programs->isEmpty())
    <section class="bk-panel">
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-collection" aria-hidden="true"></i></span>
            <h3>Belum ada program</h3>
            <p>Admin belum membuka program apa pun. Coba lagi nanti.</p>
        </div>
    </section>
@else
    <div class="bk-duo bk-duo--even">
        @foreach ($programs as $program)
            @php
                $pendaftaran = $registrations->get($program->id);
                $perLevel = $program->kursuses
                    ->filter(fn ($kursus) => $kursus->level)
                    ->groupBy(fn ($kursus) => $kursus->level->nama);
                // masihMenerima() memakai daftar status yang sama dengan scope di model.
                $terbuka = $program->kursuses->filter(fn ($kursus) => $kursus->masihMenerima())->count();
            @endphp

            <section class="bk-panel">
                <div class="bk-panel__head">
                    <div>
                        <p class="bk-eyebrow">Program</p>
                        <h2 class="bk-panel__title">{{ $program->nama }}</h2>
                    </div>
                    @if ($pendaftaran)
                        <span class="bk-tag {{ $pendaftaran->status_pendaftaran === \App\Models\Pendaftaran::STATUS_DIBATALKAN ? 'bk-tag--gagal' : 'bk-tag--info' }}">
                            {{ $pendaftaran->label_status }}
                        </span>
                    @elseif ($terbuka > 0)
                        <span class="bk-tag">Menerima peserta</span>
                    @else
                        <span class="bk-tag bk-tag--diam">Belum ada kelas terbuka</span>
                    @endif
                </div>

                <dl class="bk-kv">
                    <div><dt>Level</dt><dd class="bk-num">{{ $perLevel->count() }}</dd></div>
                    <div><dt>Kelas</dt><dd class="bk-num">{{ $program->kursuses->count() }}</dd></div>
                    <div><dt>Masih menerima</dt><dd class="bk-num">{{ $terbuka }}</dd></div>
                </dl>

                @forelse ($perLevel as $namaLevel => $kelas)
                    <div class="bk-row">
                        <span class="bk-row__sp">
                            <b>{{ $namaLevel }}</b><br>
                            <span class="bk-muted">{{ $kelas->count() }} kelas pada level ini</span>
                        </span>
                        @php $siap = $kelas->filter(fn ($kursus) => $kursus->masihMenerima())->count(); @endphp
                        <span class="bk-tag {{ $siap > 0 ? '' : 'bk-tag--diam' }}">
                            {{ $siap }} masih bisa diisi
                        </span>
                    </div>
                @empty
                    <div class="bk-panel__body">
                        <p class="bk-hint" style="padding:0">Level dan kelas belum dikonfigurasi untuk program ini.</p>
                    </div>
                @endforelse

                @if ($pendaftaran)
                    <div class="bk-panel__body">
                        <div class="bk-note bk-note--baik">
                            <i class="bi bi-info-circle-fill bk-note__icon" aria-hidden="true"></i>
                            <span>
                                Anda sudah terdaftar di program ini.
                                @if ($pendaftaran->level)
                                    Level <b>{{ $pendaftaran->level->nama }}</b>@if ($pendaftaran->kursus), kelas <b>{{ $pendaftaran->kursus->nama }}</b>@endif.
                                @else
                                    {{ $pendaftaran->petunjuk }}
                                @endif
                            </span>
                        </div>
                    </div>
                @endif

                <div class="bk-panel__foot">
                    <a href="{{ route('peserta.program.show', $program) }}" class="bk-linkbtn">
                        Lihat rincian level <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>
                    @if ($pendaftaran)
                        <a href="{{ route('peserta.pendaftaran.index') }}" class="bk-btn bk-btn--sm">
                            <i class="bi bi-clipboard-check" aria-hidden="true"></i> Lihat status
                        </a>
                    @else
                        <form action="{{ route('peserta.program.daftar', $program) }}" method="POST">
                            @csrf
                            <button type="submit" class="bk-btn bk-btn--pri bk-btn--sm">
                                <i class="bi bi-check2-circle" aria-hidden="true"></i> Daftar program
                            </button>
                        </form>
                    @endif
                </div>
            </section>
        @endforeach
    </div>
@endif
@endsection
