@extends('layouts.admin')

@section('title', 'Tambah Peserta')
@section('page-context', 'Peserta')
@section('page-title', 'Tambah peserta')
@section('page-description', 'Membuat akun peserta sekaligus profilnya. Peserta bisa langsung masuk memakai email dan kata sandi ini.')

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
            <h2 class="bk-panel__title">Data peserta</h2>
            <p class="bk-panel__subtitle">Email dan nomor peserta harus unik; keduanya dipakai sebagai penanda utama.</p>
        </div>
    </div>

    <form class="bk-panel__body" method="POST" action="{{ route('admin.peserta.store') }}">
        @csrf

        @include('peserta::admin.peserta.partials.form')

        <div class="bk-row" style="margin-top:1.5rem">
            <button type="submit" class="bk-btn bk-btn--pri">
                <i class="bi bi-check2" aria-hidden="true"></i> Simpan peserta
            </button>
            <a href="{{ route('admin.peserta.index') }}" class="bk-btn">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
            </a>
        </div>
    </form>
</section>
@endsection
