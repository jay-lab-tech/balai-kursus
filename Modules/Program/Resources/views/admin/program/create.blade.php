@extends('layouts.admin')

@section('title', 'Tambah Program')
@section('page-context', 'Akademik · Program')
@section('page-title', 'Tambah program')
@section('page-description', 'Program adalah payung yang menaungi level dan kelas; beri nama yang mudah dikenali peserta.')

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
            <h2 class="bk-panel__title">Informasi program</h2>
            <p class="bk-panel__subtitle">Pilih warna yang cukup kontras supaya program mudah dibedakan sekilas.</p>
        </div>
    </div>

    <form class="bk-panel__body" method="POST" action="{{ route('admin.program.store') }}">
        @csrf

        @include('program::admin.program.partials.form')

        <div class="bk-row" style="margin-top:1.5rem">
            <button type="submit" class="bk-btn bk-btn--pri">
                <i class="bi bi-check2" aria-hidden="true"></i> Simpan program
            </button>
            <a href="{{ route('admin.program.index') }}" class="bk-btn">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
            </a>
        </div>
    </form>
</section>
@endsection
