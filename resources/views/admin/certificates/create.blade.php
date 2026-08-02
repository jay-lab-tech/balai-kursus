@extends('layouts.admin')

@section('title', 'Draft Sertifikat Baru')
@section('page-context', 'Peserta · Sertifikat')
@section('page-title', 'Draft sertifikat baru')
@section('page-description', 'Pilih kelas dan pesertanya. Sertifikat disimpan sebagai draft dulu — peserta baru bisa mengunduhnya setelah diterbitkan.')

@section('content')
@include('admin.certificates.partials.form', [
    'certificate' => null,
    'action' => route('admin.certificates.store'),
    'method' => 'POST',
    'submitLabel' => 'Simpan draft',
])
@endsection

@section('scripts')
@include('admin.certificates.partials.peserta-loader')
@endsection
