@extends('site.layouts.app')
@section('title', 'Page Not Found')
@section('robots', 'noindex, nofollow')
@section('content')
<section class="error-page">
    <div class="error-page-inner">
        <div class="error-page-code">404</div>
        <h1 style="color: var(--dark); margin-bottom: 1rem;">Page Not Found</h1>
        <p style="color: var(--gray-600); margin-bottom: 1.5rem;">The page you're looking for doesn't exist or has been moved.</p>
        <a href="{{ route('home') }}" class="btn btn-primary"><i class="fas fa-home"></i> Back to home</a>
    </div>
</section>
@endsection
