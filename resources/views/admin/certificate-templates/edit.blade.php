@extends('layouts.admin')

@section('title', 'Ubah Template Sertifikat')
@section('page-context', 'Peserta · Sertifikat · Template')
@section('page-title', 'Ubah ' . $template->name)
@section('page-description', 'Perubahan di sini hanya berlaku untuk sertifikat yang dibuat setelahnya — sertifikat yang sudah terbit menyimpan salinan datanya sendiri.')

@section('content')
@include('admin.certificate-templates.partials.form', [
    'template' => $template,
    'action' => route('admin.templates.update', $template),
    'method' => 'PUT',
    'submitLabel' => 'Simpan perubahan',
])
@endsection
