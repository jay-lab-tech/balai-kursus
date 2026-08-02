@extends('layouts.admin')

@section('title', 'Detail Kelas')
@section('page-context', 'Akademik · Lokasi & Ruang')
@section('page-title', $kela->nama)
@section('page-description', 'Rujukan kapasitas dan fasilitas sebelum ruang ini dipakai pada jadwal.')

@section('content')

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Ringkasan ruang</h2>
            <p class="bk-panel__subtitle">Data pokok yang menentukan cocok tidaknya ruang untuk sebuah kelas.</p>
        </div>
        <div class="bk-row">
            <a href="{{ route('admin.kelas.edit', $kela->id) }}" class="bk-btn bk-btn--pri bk-btn--sm">
                <i class="bi bi-pencil" aria-hidden="true"></i> Ubah
            </a>
            <a href="{{ route('admin.kelas.index') }}" class="bk-btn bk-btn--sm">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
            </a>
        </div>
    </div>

    <div class="bk-panel__body">
        <dl class="bk-kv">
            <div>
                <dt>Nama ruang</dt>
                <dd>{{ $kela->nama }}</dd>
            </div>
            <div>
                <dt>Kapasitas</dt>
                <dd>{{ $kela->kapasitas }} orang</dd>
            </div>
            <div>
                <dt>Ditambahkan</dt>
                <dd>{{ $kela->created_at?->translatedFormat('j F Y') ?: '—' }}</dd>
            </div>
        </dl>
    </div>
</section>

<section class="bk-panel" style="margin-top:1.5rem">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Fasilitas</h2>
            <p class="bk-panel__subtitle">Peralatan dan sarana yang tersedia di ruang ini.</p>
        </div>
    </div>
    <div class="bk-panel__body">
        <p>{{ $kela->fasilitas }}</p>
    </div>
</section>

@if ($kela->keterangan)
    <section class="bk-panel" style="margin-top:1.5rem">
        <div class="bk-panel__head">
            <div>
                <h2 class="bk-panel__title">Keterangan</h2>
                <p class="bk-panel__subtitle">Catatan kondisi ruang atau aturan pemakaian.</p>
            </div>
        </div>
        <div class="bk-panel__body">
            <p>{{ $kela->keterangan }}</p>
        </div>
    </section>
@endif
@endsection
