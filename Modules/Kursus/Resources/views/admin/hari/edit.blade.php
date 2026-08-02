@extends('layouts.admin')

@section('title', 'Ubah Hari')
@section('page-context', 'Akademik · Jadwal')
@section('page-title', 'Ubah ' . $hari->nama)
@section('page-description', 'Perubahan langsung terlihat pada pilihan hari di form jadwal.')

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
            <h2 class="bk-panel__title">Informasi hari</h2>
            <p class="bk-panel__subtitle">Urutan menentukan posisi hari di setiap daftar pilihan.</p>
        </div>
    </div>

    <form class="bk-panel__body" method="POST" action="{{ route('admin.hari.update', $hari->id) }}">
        @csrf
        @method('PUT')

        @include('kursus::admin.hari.partials.form')

        <div class="bk-row" style="margin-top:1.5rem">
            <button type="submit" class="bk-btn bk-btn--pri">
                <i class="bi bi-check2" aria-hidden="true"></i> Simpan perubahan
            </button>
            <a href="{{ route('admin.hari.index') }}" class="bk-btn">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
            </a>
        </div>
    </form>
</section>
@endsection
