@extends('site.layouts.app')
@section('title', 'Page Not Found')
@section('robots', 'noindex, nofollow')
@section('content')
<section style="min-height: 70vh; display: flex; align-items: center; justify-content: center; padding-top: 100px;">
    <div style="text-align: center; max-width: 500px; padding: 2rem;">
        <div style="font-size: 6rem; font-weight: 800; background: linear-gradient(135deg, #6366f1, #06b6d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">404</div>
        <h1 style="color: var(--dark); margin-bottom: 1rem;">Page Not Found</h1>
        <p style="color: var(--gray-600); margin-bottom: 1.5rem;">The page you're looking for doesn't exist or has been moved.</p>
        <a href="{{ route('home') }}" class="btn btn-primary"><i class="fas fa-home"></i> Back to home</a>
    </div>
</section>
@endsection
