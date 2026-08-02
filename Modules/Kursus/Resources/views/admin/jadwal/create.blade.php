@extends('layouts.admin')

@section('title', 'Tambah Jadwal')
@section('page-context', 'Akademik · Kelas ' . $kursus->nama)
@section('page-title', 'Tambah pertemuan')
@section('page-description', 'Pertemuan yang tersimpan langsung dipakai untuk absensi dan risalah kelas ini.')

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
            <h2 class="bk-panel__title">Detail pertemuan</h2>
            <p class="bk-panel__subtitle">Waktu, lokasi, dan ruang menentukan apa yang dilihat peserta di jadwalnya.</p>
        </div>
    </div>

    <form class="bk-panel__body" method="POST" action="{{ route('admin.jadwal.store', $kursus) }}">
        @csrf

        @include('kursus::admin.jadwal.partials.form')

        <div class="bk-row" style="margin-top:1.5rem">
            <button type="submit" class="bk-btn bk-btn--pri">
                <i class="bi bi-check2" aria-hidden="true"></i> Simpan jadwal
            </button>
            <a href="{{ route('admin.jadwal.index', $kursus) }}" class="bk-btn">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
            </a>
        </div>
    </form>
</section>
@endsection
