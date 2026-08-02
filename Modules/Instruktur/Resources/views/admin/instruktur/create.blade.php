@extends('layouts.admin')

@section('title', 'Tambah Instruktur')
@section('page-context', 'Sumber Daya · Instruktur')
@section('page-title', 'Tambah instruktur')
@section('page-description', 'Membuat akun pengajar sekaligus profilnya, agar instruktur bisa langsung diberi kelas dan mengisi absensi.')

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
            <h2 class="bk-panel__title">Data instruktur</h2>
            <p class="bk-panel__subtitle">Nama akun dipakai untuk masuk; nama pengajar yang tampil di jadwal dan sertifikat.</p>
        </div>
    </div>

    <form class="bk-panel__body" method="POST" action="{{ route('admin.instruktur.store') }}">
        @csrf

        @include('instruktur::admin.instruktur.partials.form')

        <div class="bk-row" style="margin-top:1.5rem">
            <button type="submit" class="bk-btn bk-btn--pri">
                <i class="bi bi-check2" aria-hidden="true"></i> Simpan instruktur
            </button>
            <a href="{{ route('admin.instruktur.index') }}" class="bk-btn">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
            </a>
        </div>
    </form>
</section>
@endsection
