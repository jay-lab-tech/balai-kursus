@extends('layouts.admin')

@section('title', 'Template Sertifikat')
@section('page-context', 'Peserta · Sertifikat')
@section('page-title', 'Template sertifikat')
@section('page-description', 'Template menentukan identitas lembaga, penandatangan, penomoran, dan gambar yang tercetak di PDF sertifikat.')

@section('content')

@if (session('success'))
    <div class="bk-note bk-note--baik">
        <i class="bi bi-check-circle-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="bk-note bk-note--buruk">
        <i class="bi bi-exclamation-octagon-fill bk-note__icon" aria-hidden="true"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if ($templates->isNotEmpty() && ! $adaTemplateAktif)
    <div class="bk-note bk-note--perlu">
        <i class="bi bi-exclamation-triangle-fill bk-note__icon" aria-hidden="true"></i>
        <span>Tidak ada template yang berstatus aktif. Selama itu, draft sertifikat baru tidak bisa dibuat — aktifkan salah satu lewat tombol Ubah.</span>
    </div>
@endif

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Daftar template</h2>
            <p class="bk-panel__subtitle">{{ $templates->total() }} template tersimpan. Hanya satu yang bisa aktif dalam satu waktu.</p>
        </div>

        <div class="bk-row">
            <a href="{{ route('admin.certificates.index') }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Daftar sertifikat
            </a>
            <a href="{{ route('admin.templates.create') }}" class="bk-btn bk-btn--pri bk-btn--sm">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Template baru
            </a>
        </div>
    </div>

    @if ($templates->isEmpty())
        <div class="bk-empty">
            <span class="bk-empty__icon"><i class="bi bi-card-image" aria-hidden="true"></i></span>
            <h3>Belum ada template</h3>
            <p>Sertifikat baru hanya bisa dibuat kalau ada satu template aktif. Buat template pertama untuk memulai.</p>
            <a href="{{ route('admin.templates.create') }}" class="bk-btn bk-btn--pri">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> Template baru
            </a>
        </div>
    @else
        <table class="bk-table is-padat">
            <thead>
                <tr>
                    <th>Template</th>
                    <th>Penandatangan</th>
                    <th>Kode nomor</th>
                    <th class="r nw">Dipakai</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($templates as $template)
                    <tr>
                        <td>
                            <b>{{ $template->name }}</b>
                            @if ($template->is_active)
                                <span class="bk-tag">Aktif</span>
                            @endif
                            <br>
                            <span class="bk-muted">{{ $template->institution_name }} — {{ $template->unit_name }}, {{ $template->city }}</span>
                        </td>
                        <td>
                            {{ $template->signer_name }}<br>
                            <span class="bk-muted">{{ $template->signer_title }} · NIP {{ $template->signer_nip }}</span>
                        </td>
                        <td><span class="bk-code">{{ $template->certificate_prefix }}</span></td>
                        <td class="r nw">
                            @if ($template->certificates_count > 0)
                                <b class="bk-num">{{ $template->certificates_count }}</b> sertifikat
                            @else
                                <span class="bk-muted">Belum dipakai</span>
                            @endif
                        </td>
                        <td class="r nw">
                            <a href="{{ route('admin.templates.edit', $template) }}" class="bk-iconbtn" title="Ubah template">
                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                <span class="bk-sr">Ubah {{ $template->name }}</span>
                            </a>

                            {{-- Template aktif dan template yang sudah dipakai sertifikat
                                 ditolak oleh controller; tombolnya ikut disembunyikan
                                 supaya tidak menawarkan aksi yang pasti gagal. --}}
                            @if (! $template->is_active && $template->certificates_count === 0)
                                <form method="POST" action="{{ route('admin.templates.destroy', $template) }}"
                                      style="display:inline"
                                      onsubmit="return confirm('Hapus template {{ $template->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bk-iconbtn bk-iconbtn--danger" title="Hapus template">
                                        <i class="bi bi-trash3" aria-hidden="true"></i>
                                        <span class="bk-sr">Hapus {{ $template->name }}</span>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($templates->hasPages())
            <div class="bk-panel__foot">{{ $templates->links() }}</div>
        @endif
    @endif
</section>
@endsection
