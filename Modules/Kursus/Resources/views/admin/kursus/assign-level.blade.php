@extends('layouts.admin')

@section('title', 'Assign Level Peserta')

@section('page-title', 'Assign Level Peserta')

@section('page-description', 'Tetapkan atau perbarui level hasil penempatan peserta untuk kelas yang dipilih.')

@section('content')
<div class="space-y-6">
    <section class="admin-panel admin-panel--hero">
        <span class="admin-eyebrow"><i class="bi bi-diagram-3-fill"></i>Penempatan Level</span>
        <div class="space-y-3">
            <h2 class="text-3xl font-semibold tracking-tight text-white">Assign atau update level peserta.</h2>
            <p class="max-w-2xl text-sm leading-6 text-slate-300">Gunakan form ini untuk menyelaraskan level peserta dengan hasil placement dan kebutuhan kelas yang sedang dikelola.</p>
        </div>
    </section>

    <section class="admin-panel max-w-3xl">
        <div class="admin-panel__header"><div><h3 class="admin-panel__title">Data Penempatan</h3><p class="admin-panel__subtitle">Pastikan level yang dipilih sesuai dengan hasil tes dan konteks kelas peserta.</p></div></div>
        <form method="POST" action="{{ route('admin.kursus.assignLevel', [$kursus->id, $pendaftaran->id]) }}" class="space-y-6 p-6 pt-0 sm:p-8 sm:pt-0">
            @csrf
            <div class="space-y-2"><label class="admin-label">Nama Peserta</label><div class="rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3 text-sm font-medium text-white">{{ $pendaftaran->peserta->user->name }}</div></div>
            <div class="space-y-2"><label class="admin-label" for="level_id">Level</label><select name="level_id" id="level_id" class="admin-input"><option value="">-- Pilih Level --</option>@foreach($levels as $level)<option value="{{ $level->id }}" {{ (string) old('level_id', $pendaftaran->level_id) === (string) $level->id ? 'selected' : '' }}>{{ $level->nama }}</option>@endforeach</select></div>
            <div class="flex flex-wrap justify-end gap-3"><button type="submit" class="admin-btn admin-btn-primary"><i class="bi bi-check2-circle"></i>Simpan Level</button><a href="{{ route('admin.kursus.peserta', $kursus->id) }}" class="admin-btn admin-btn-secondary"><i class="bi bi-arrow-left"></i>Batal</a></div>
        </form>
    </section>
</div>
@endsection
