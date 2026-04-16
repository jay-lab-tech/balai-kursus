@extends('layouts.admin')

@section('title', 'Buat Template Sertifikat')

@section('page-title', 'Buat Template Sertifikat')

@section('page-description', 'Masukkan metadata resmi dan path aset untuk template sertifikat yang akan dipakai sistem.')

@section('content')
@include('admin.certificate-templates.partials.form', [
    'template' => null,
    'action' => route('admin.templates.store'),
    'method' => 'POST',
    'submitLabel' => 'Simpan Template',
])
@endsection
