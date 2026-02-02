@extends('layouts.app-bootstrap')

@section('title', 'Risalah')

@section('content')
    <div class="container-fluid py-4">
        @yield('content')
    </div>
@endsection

@section('styles')
    {{-- module-specific styles can go here --}}
@endsection

@section('scripts')
    {{-- module-specific scripts can go here --}}
@endsection
