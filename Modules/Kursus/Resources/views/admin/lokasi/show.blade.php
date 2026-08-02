@extends('layouts.admin')

@section('title', 'Detail Lokasi')
@section('page-context', 'Akademik · Lokasi & Ruang')
@section('page-title', $lokasi->nama)
@section('page-description', 'Rujukan alamat dan kontak untuk penjadwalan pertemuan.')

@section('content')

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Ringkasan lokasi</h2>
            <p class="bk-panel__subtitle">Data pokok yang paling sering dicari saat koordinasi.</p>
        </div>
        <div class="bk-row">
            <a href="{{ route('admin.lokasi.edit', $lokasi->id) }}" class="bk-btn bk-btn--pri bk-btn--sm">
                <i class="bi bi-pencil" aria-hidden="true"></i> Ubah
            </a>
            <a href="{{ route('admin.lokasi.index') }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
            </a>
        </div>
    </div>

    <div class="bk-panel__body">
        <dl class="bk-kv">
            <div>
                <dt>Kota</dt>
                <dd>{{ $lokasi->kota ?: '—' }}</dd>
            </div>
            <div>
                <dt>Provinsi</dt>
                <dd>{{ $lokasi->provinsi ?: '—' }}</dd>
            </div>
            <div>
                <dt>Telepon</dt>
                <dd>{{ $lokasi->no_telp ?: '—' }}</dd>
            </div>
        </dl>
    </div>
</section>

<section class="bk-panel" style="margin-top:1.5rem">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Alamat lengkap</h2>
            <p class="bk-panel__subtitle">Dipakai apa adanya pada surat dan informasi ke peserta.</p>
        </div>
    </div>
    <div class="bk-panel__body">
        <p>{{ $lokasi->alamat }}</p>
    </div>
</section>

@if ($lokasi->keterangan)
    <section class="bk-panel" style="margin-top:1.5rem">
        <div class="bk-panel__head">
            <div>
                <h2 class="bk-panel__title">Keterangan</h2>
                <p class="bk-panel__subtitle">Patokan arah, akses parkir, atau catatan operasional lain.</p>
            </div>
        </div>
        <div class="bk-panel__body">
            <p>{{ $lokasi->keterangan }}</p>
        </div>
    </section>
@endif
@endsection
