@extends('layouts.admin')

@section('title', 'Ubah Kelas')
@section('page-context', 'Akademik · Kelas Program')
@section('page-title', 'Ubah ' . $kursus->nama)
@section('page-description', 'Menurunkan kuota di bawah jumlah peserta terdaftar akan ditolak agar data pendaftaran tetap utuh.')

@section('content')

@if ($errors->any())
    <div class="bk-note bk-note--buruk">
        <i class="bi bi-exclamation-triangle-fill bk-note__icon" aria-hidden="true"></i>
        <span>Ada yang perlu diperbaiki: {{ $errors->first() }}</span>
    </div>
@endif

<form method="POST" action="{{ route('admin.kursus.update', $kursus) }}">
    @csrf
    @method('PUT')

    @include('kursus::admin.kursus.partials.form')

    <div class="bk-row" style="margin-top:1.5rem">
        <button type="submit" class="bk-btn bk-btn--pri">
            <i class="bi bi-check2" aria-hidden="true"></i> Simpan perubahan
        </button>
        <a href="{{ route('admin.kursus.peserta', $kursus) }}" class="bk-btn">
            <i class="bi bi-people" aria-hidden="true"></i> Peserta kelas
        </a>
        <a href="{{ route('admin.kursus.index') }}" class="bk-btn">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
        </a>
    </div>
</form>
@endsection
