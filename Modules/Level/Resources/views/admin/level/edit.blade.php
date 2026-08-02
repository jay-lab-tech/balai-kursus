@extends('layouts.admin')

@section('title', 'Ubah Level')
@section('page-context', 'Akademik · Level')
@section('page-title', 'Ubah ' . $level->nama)
@section('page-description', 'Perubahan rentang nilai berlaku untuk penempatan berikutnya, bukan yang sudah tercatat.')

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
            <h2 class="bk-panel__title">Informasi level</h2>
            <p class="bk-panel__subtitle">Rentang saat ini: {{ $level->rentang_nilai }}.</p>
        </div>
    </div>

    <form class="bk-panel__body" method="POST" action="{{ route('admin.level.update', $level) }}">
        @csrf
        @method('PUT')

        @include('level::admin.level.partials.form')

        <div class="bk-row" style="margin-top:1.5rem">
            <button type="submit" class="bk-btn bk-btn--pri">
                <i class="bi bi-check2" aria-hidden="true"></i> Simpan perubahan
            </button>
            <a href="{{ route('admin.level.index') }}" class="bk-btn">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
            </a>
        </div>
    </form>
</section>
@endsection
