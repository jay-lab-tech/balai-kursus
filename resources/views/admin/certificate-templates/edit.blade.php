@extends('layouts.admin')

@section('title', 'Edit Template Sertifikat')

@section('page-title', 'Edit Template Sertifikat')

@section('page-description', 'Perbarui aset visual, penandatangan, dan metadata resmi template sertifikat.')

@section('content')
@include('admin.certificate-templates.partials.form', [
    'template' => $template,
    'action' => route('admin.templates.update', $template),
    'method' => 'PUT',
    'submitLabel' => 'Simpan Perubahan',
])
@endsection
