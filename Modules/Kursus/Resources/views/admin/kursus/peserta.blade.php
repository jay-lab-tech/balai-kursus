@extends('layouts.admin')

@section('title', 'Peserta Kelas')
@section('page-context', 'Akademik · Kelas ' . $kursus->nama)
@section('page-title', 'Peserta kelas')
@section('page-description', ($kursus->program->nama ?? 'Program') . ' · ' . ($kursus->level->nama ?? 'Level') . ' — peserta yang sudah ditempatkan beserta level dan status pendaftarannya.')

@section('content')

@if (session('success'))
    <div class="bk-note bk-note--baik">
        <i class="bi bi-check-circle-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Daftar peserta</h2>
            <p class="bk-panel__subtitle">{{ $peserta->total() }} pendaftaran tercatat, termasuk yang dibatalkan. Kuota kelas {{ $kursus->kuota }} kursi.</p>
        </div>
        <div class="bk-row">
            <a href="{{ route('admin.kursus.edit', $kursus) }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-pencil" aria-hidden="true"></i> Ubah kelas
            </a>
            <a href="{{ route('admin.kursus.index') }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Daftar kelas
            </a>
        </div>
    </div>

    @if ($peserta->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-people" aria-hidden="true"></i></span>
            <h3>Belum ada peserta di kelas ini</h3>
            <p>Peserta muncul di sini setelah penempatan dari hasil tes selesai diproses.</p>
            <a href="{{ route('admin.score.index') }}" class="bk-btn bk-btn--pri">
                <i class="bi bi-clipboard-check" aria-hidden="true"></i> Lihat nilai tes
            </a>
        </div>
    @else
        <table class="bk-table">
            <thead>
                <tr>
                    <th>Peserta</th>
                    <th>Level</th>
                    <th class="r nw">Nilai tes</th>
                    <th>Status</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($peserta as $item)
                    <tr>
                        <td>
                            <b>{{ $item->peserta->user->name ?? '—' }}</b><br>
                            <span class="bk-muted">{{ $item->participant_email_snapshot ?? $item->peserta->user->email ?? '—' }}</span>
                        </td>
                        <td>{{ $item->level->nama ?? 'Belum ada level' }}</td>
                        <td class="r nw">{{ $item->placementScore?->final_score ?? '—' }}</td>
                        <td>
                            @php
                                // Pil tanpa pengubah sudah berwarna sage — dipakai untuk keadaan beres.
                                $rupa = match ($item->status_pendaftaran) {
                                    'aktif', 'selesai' => '',
                                    'menunggu_pembayaran' => 'bk-tag--perlu',
                                    'menunggu_tes', 'menunggu_penempatan' => 'bk-tag--jalan',
                                    default => 'bk-tag--diam',
                                };
                            @endphp
                            <span class="bk-tag {{ $rupa }}">{{ ucfirst(str_replace('_', ' ', $item->status_pendaftaran)) }}</span>
                        </td>
                        <td class="r nw">
                            <a href="{{ route('admin.kursus.assignLevelForm', [$kursus->id, $item->id]) }}" class="bk-iconbtn"
                               title="Ubah level {{ $item->peserta->user->name ?? 'peserta' }}">
                                <i class="bi bi-diagram-3" aria-hidden="true"></i>
                                <span class="bk-sr">Ubah level</span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($peserta->hasPages())
            <div class="bk-panel__foot">{{ $peserta->links() }}</div>
        @endif
    @endif
</section>
@endsection
