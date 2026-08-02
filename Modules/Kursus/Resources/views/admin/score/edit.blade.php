@extends('layouts.admin')

@section('title', 'Ubah Tes Penempatan')
@section('page-context', 'Peserta · Nilai')
@section('page-title', 'Ubah hasil tes penempatan')
@section('page-description', 'Mengubah nilai akhir akan menjalankan ulang penempatan, sehingga level dan kelas peserta bisa ikut berpindah.')

@section('content')

@if ($errors->any())
    <div class="bk-note bk-note--buruk">
        <i class="bi bi-exclamation-triangle-fill bk-note__icon" aria-hidden="true"></i>
        <span>Ada yang perlu diperbaiki: {{ $errors->first() }}</span>
    </div>
@endif

<form method="POST" action="{{ route('admin.score.update', $score) }}">
    @csrf
    @method('PUT')

    @include('kursus::admin.score.partials.form')

    <div class="bk-row" style="margin-top:1.5rem">
        <button type="submit" class="bk-btn bk-btn--pri">
            <i class="bi bi-check2" aria-hidden="true"></i> Simpan perubahan
        </button>
        <a href="{{ route('admin.score.show', $score) }}" class="bk-btn">
            <i class="bi bi-eye" aria-hidden="true"></i> Lihat detail
        </a>
        <a href="{{ route('admin.score.index') }}" class="bk-btn">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
        </a>
    </div>
</form>
@endsection
