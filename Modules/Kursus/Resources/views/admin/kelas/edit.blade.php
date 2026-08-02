@extends('layouts.admin')

@section('title', 'Ubah Kelas')
@section('page-context', 'Akademik · Lokasi & Ruang')
@section('page-title', 'Ubah ' . $kela->nama)
@section('page-description', 'Perubahan kapasitas dan fasilitas langsung dipakai oleh jadwal yang menunjuk ruang ini.')

@section('content')

@if ($errors->any())
    <div class="bk-note bk-note--buruk">
        <i class="bi bi-exclamation-triangle-fill bk-note__icon" aria-hidden="true"></i>
        <span>Ada yang perlu diperbaiki: {{ $errors->first() }}</span>
    </div>
@endif

<section class="bk-panel">
    <div class="bk-panel__head">
        <div>
            <h2 class="bk-panel__title">Informasi ruang kelas</h2>
            <p class="bk-panel__subtitle">Kapasitas yang realistis mencegah kelas kelebihan peserta.</p>
        </div>
        <a href="{{ route('admin.kelas.show', $kela->id) }}" class="bk-btn bk-btn--sm">
            <i class="bi bi-eye" aria-hidden="true"></i> Lihat detail
        </a>
    </div>

    <form class="bk-panel__body" method="POST" action="{{ route('admin.kelas.update', $kela->id) }}">
        @csrf
        @method('PUT')

        @include('kursus::admin.kelas.partials.form')

        <div class="bk-row" style="margin-top:1.5rem">
            <button type="submit" class="bk-btn bk-btn--pri">
                <i class="bi bi-check2" aria-hidden="true"></i> Simpan perubahan
            </button>
            <a href="{{ route('admin.kelas.index') }}" class="bk-btn">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
            </a>
        </div>
    </form>
</section>
@endsection
