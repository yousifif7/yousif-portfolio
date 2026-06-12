@extends('admin.layouts.app')

@section('title', 'Post-Deploy Maintenance')
@section('page_title', 'Post-Deploy Maintenance')

@section('content')

<div class="page-header">
    <div class="title">
        <h2>Post-Deploy Tasks</h2>
        <div class="subtitle">Run cache clears and image optimization after uploading files to the live server.</div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <p style="margin-bottom: 1.25rem; color: var(--gray-600);">
            This runs the following commands securely in order:
        </p>
        <ul style="margin: 0 0 1.5rem 1.25rem; color: var(--gray-700);">
            <li><code>php artisan images:optimize</code></li>
            <li><code>php artisan view:clear</code></li>
            <li><code>php artisan cache:clear</code></li>
            <li><code>php artisan config:clear</code></li>
        </ul>

        <form method="POST" action="{{ route('admin.maintenance.post-deploy') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-play"></i> Run post-deploy tasks
            </button>
        </form>
    </div>
</div>

@if(! empty($ran))
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h2>Command output</h2>
        </div>
        <div class="card-body">
            @foreach($results as $result)
                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                        @if($result['success'])
                            <span class="badge badge-success">OK</span>
                        @else
                            <span class="badge badge-danger">Failed</span>
                        @endif
                        <strong><code>{{ $result['command'] }}</code></strong>
                    </div>
                    <pre style="background: var(--gray-100); padding: 1rem; border-radius: 8px; overflow-x: auto; font-size: 0.85rem; white-space: pre-wrap; margin: 0;">{{ $result['output'] }}</pre>
                </div>
            @endforeach
        </div>
    </div>
@endif

@endsection
