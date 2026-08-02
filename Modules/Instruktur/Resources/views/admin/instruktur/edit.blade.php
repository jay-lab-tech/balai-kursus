@extends('layouts.admin')

@section('title', 'Ubah Instruktur')
@section('page-context', 'Sumber Daya · Instruktur')
@section('page-title', 'Ubah ' . $instruktur->nama_instr)
@section('page-description', 'Kata sandi tidak diubah dari sini; instruktur mengaturnya sendiri lewat halaman profil.')

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
            <p class="bk-panel__subtitle">Mengampu {{ $instruktur->kursuses()->distinct()->count('kursuses.id') }} kelas saat ini.</p>
        </div>
    </div>

    <form class="bk-panel__body" method="POST" action="{{ route('admin.instruktur.update', $instruktur->id) }}">
        @csrf
        @method('PUT')

        @include('instruktur::admin.instruktur.partials.form')

        <div class="bk-row" style="margin-top:1.5rem">
            <button type="submit" class="bk-btn bk-btn--pri">
                <i class="bi bi-check2" aria-hidden="true"></i> Simpan perubahan
            </button>
            <a href="{{ route('admin.instruktur.index') }}" class="bk-btn">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
            </a>
        </div>
    </form>
</section>
@endsection
