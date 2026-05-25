@extends('site.layouts.app')
@section('title', 'Forbidden')
@section('content')
<section style="min-height: 70vh; display: flex; align-items: center; justify-content: center; padding-top: 100px;">
    <div style="text-align: center; max-width: 500px; padding: 2rem;">
        <div style="font-size: 6rem; font-weight: 800; background: linear-gradient(135deg, #6366f1, #06b6d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">403</div>
        <h1 style="color: var(--dark); margin-bottom: 1rem;">Access Forbidden</h1>
        <p style="color: var(--gray-600); margin-bottom: 1.5rem;">{{ $exception->getMessage() ?: "You don't have permission to access this page." }}</p>
        <a href="{{ route('home') }}" class="btn btn-primary"><i class="fas fa-home"></i> Back to home</a>
    </div>
</section>
@endsection
