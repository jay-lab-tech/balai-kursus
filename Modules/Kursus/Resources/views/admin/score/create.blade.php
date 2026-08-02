@extends('layouts.admin')

@section('title', 'Input Tes Penempatan')
@section('page-context', 'Peserta · Nilai')
@section('page-title', 'Input hasil tes penempatan')
@section('page-description', 'Setelah nilai disimpan, sistem langsung menentukan level peserta dan mencarikan kelas yang kuotanya masih tersisa.')

@section('content')

@if ($errors->any())
    <div class="bk-note bk-note--buruk">
        <i class="bi bi-exclamation-triangle-fill bk-note__icon" aria-hidden="true"></i>
        <span>Ada yang perlu diperbaiki: {{ $errors->first() }}</span>
    </div>
@endif

<form method="POST" action="{{ route('admin.score.store') }}">
    @csrf

    @include('kursus::admin.score.partials.form')

    <div class="bk-row" style="margin-top:1.5rem">
        <button type="submit" class="bk-btn bk-btn--pri">
            <i class="bi bi-check2" aria-hidden="true"></i> Simpan hasil tes
        </button>
        <a href="{{ route('admin.score.index') }}" class="bk-btn">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
        </a>
    </div>
</form>
@endsection
