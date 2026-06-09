@extends('site.layouts.app')
@section('title', 'Forbidden')
@section('content')
<section class="error-page">
    <div class="error-page-inner">
        <div class="error-page-code">403</div>
        <h1 style="color: var(--dark); margin-bottom: 1rem;">Access Forbidden</h1>
        <p style="color: var(--gray-600); margin-bottom: 1.5rem;">{{ $exception->getMessage() ?: "You don't have permission to access this page." }}</p>
        <a href="{{ route('home') }}" class="btn btn-primary"><i class="fas fa-home"></i> Back to home</a>
    </div>
</section>
@endsection
