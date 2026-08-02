@extends('layouts.admin')

@section('title', 'Tambah Lokasi')
@section('page-context', 'Akademik · Lokasi & Ruang')
@section('page-title', 'Tambah lokasi')
@section('page-description', 'Lokasi adalah gedung atau tempat penyelenggaraan yang dipilih saat menyusun jadwal pertemuan.')

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
            <h2 class="bk-panel__title">Informasi lokasi</h2>
            <p class="bk-panel__subtitle">Alamat yang lengkap membantu peserta menemukan tempat kursus.</p>
        </div>
    </div>

    <form class="bk-panel__body" method="POST" action="{{ route('admin.lokasi.store') }}">
        @csrf

        @include('kursus::admin.lokasi.partials.form')

        <div class="bk-row" style="margin-top:1.5rem">
            <button type="submit" class="bk-btn bk-btn--pri">
                <i class="bi bi-check2" aria-hidden="true"></i> Simpan lokasi
            </button>
            <a href="{{ route('admin.lokasi.index') }}" class="bk-btn">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
            </a>
        </div>
    </form>
</section>
@endsection
