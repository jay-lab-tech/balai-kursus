@extends('layouts.admin')

@section('title', 'Template Sertifikat Baru')
@section('page-context', 'Peserta · Sertifikat · Template')
@section('page-title', 'Template sertifikat baru')
@section('page-description', 'Template menentukan identitas lembaga, penandatangan, penomoran, dan gambar yang dipakai saat sertifikat PDF dibuat.')

@section('content')
@include('admin.certificate-templates.partials.form', [
    'template' => null,
    'action' => route('admin.templates.store'),
    'method' => 'POST',
    'submitLabel' => 'Simpan template',
])
@endsection
