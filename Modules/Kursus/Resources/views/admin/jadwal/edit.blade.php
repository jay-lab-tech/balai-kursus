@extends('layouts.admin')

@section('title', 'Ubah Jadwal')
@section('page-context', 'Akademik · Kelas ' . $kursus->nama)
@section('page-title', $jadwal->pertemuan_ke ? 'Ubah pertemuan ke-' . $jadwal->pertemuan_ke : 'Ubah pertemuan')
@section('page-description', 'Perubahan waktu atau ruang ikut terlihat pada absensi, risalah, dan jadwal peserta.')

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

    <form class="bk-panel__body" method="POST" action="{{ route('admin.jadwal.update', [$kursus, $jadwal]) }}">
        @csrf
        @method('PUT')

        @include('kursus::admin.jadwal.partials.form')

        <div class="bk-row" style="margin-top:1.5rem">
            <button type="submit" class="bk-btn bk-btn--pri">
                <i class="bi bi-check2" aria-hidden="true"></i> Simpan perubahan
            </button>
            <a href="{{ route('admin.jadwal.index', $kursus) }}" class="bk-btn">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
            </a>
        </div>
    </form>
</section>
@endsection
