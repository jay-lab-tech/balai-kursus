@extends('layouts.admin')

@section('title', 'Tambah Kelas')
@section('page-context', 'Akademik · Kelas Program')
@section('page-title', 'Tambah kelas')
@section('page-description', 'Kelas yang berstatus buka langsung muncul di halaman program dan bisa didaftari peserta.')

@section('content')

@if ($errors->any())
    <div class="bk-note bk-note--buruk">
        <i class="bi bi-exclamation-triangle-fill bk-note__icon" aria-hidden="true"></i>
        <span>Ada yang perlu diperbaiki: {{ $errors->first() }}</span>
    </div>
@endif

<form method="POST" action="{{ route('admin.kursus.store') }}">
    @csrf

    @include('kursus::admin.kursus.partials.form')

    <div class="bk-row" style="margin-top:1.5rem">
        <button type="submit" class="bk-btn bk-btn--pri">
            <i class="bi bi-check2" aria-hidden="true"></i> Simpan kelas
        </button>
        <a href="{{ route('admin.kursus.index') }}" class="bk-btn">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
        </a>
    </div>
</form>
@endsection
