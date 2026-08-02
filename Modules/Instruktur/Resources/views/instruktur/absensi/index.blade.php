@extends('instruktur::layouts.master')

@section('title', 'Kursus saya')
@section('page-context', 'Instruktur · Kursus')
@section('page-description', 'Seluruh kelas yang ditugaskan kepada Anda.')

@section('content')

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Kelas yang diampu</h2>
            <p class="bk-panel__subtitle">{{ $kursus->count() }} kelas aktif.</p>
        </div>
        <a href="{{ route('instruktur.dashboard') }}" class="bk-btn bk-btn--sm">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Ringkasan
        </a>
    </div>

    @if ($kursus->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-inbox" aria-hidden="true"></i></span>
            <h3>Belum ada kursus</h3>
            <p>Hubungi admin untuk mendapatkan kelas yang perlu Anda ampu.</p>
        </div>
    @else
        <table class="bk-table">
            <thead>
                <tr>
                    <th class="r">No</th>
                    <th>Kelas</th>
                    <th>Program</th>
                    <th class="r nw">Peserta</th>
                    <th class="r nw">Pertemuan</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kursus as $urutan => $k)
                    <tr>
                        <td class="r">{{ $urutan + 1 }}</td>
                        <td><b>{{ $k->nama }}</b></td>
                        <td>{{ $k->program->nama ?? '—' }}</td>
                        <td class="r nw"><span class="bk-num">{{ $k->peserta_count }}</span></td>
                        <td class="r nw"><span class="bk-num">{{ $k->risalah_count }}</span></td>
                        <td class="r nw">
                            <a href="{{ route('instruktur.kursus.show', $k) }}" class="bk-btn bk-btn--sm">
                                Kelola <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</section>
@endsection
