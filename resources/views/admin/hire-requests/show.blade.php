@extends('admin.layouts.app')
@section('title', 'Hire Request')
@section('page_title', 'Hire Request Details')

@section('content')

<div class="page-header">
    <div class="title">
        <h2>{{ $hireRequest->name }}</h2>
        <div class="subtitle">Submitted {{ $hireRequest->created_at->format('M j, Y \a\t g:i A') }}</div>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <form method="POST" action="{{ route('admin.hire-requests.toggleRead', $hireRequest) }}">
            @csrf
            <button class="btn btn-secondary btn-sm">
                <i class="fas fa-envelope{{ $hireRequest->is_read ? '-open' : '' }}"></i>
                Mark as {{ $hireRequest->is_read ? 'unread' : 'read' }}
            </button>
        </form>
        <a href="{{ route('admin.hire-requests.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-header"><strong>Contact</strong></div>
        <div style="padding: 1.25rem;">
            <p><strong>Email:</strong> <a href="mailto:{{ $hireRequest->email }}">{{ $hireRequest->email }}</a></p>
            <p><strong>WhatsApp:</strong>
                <a href="{{ $hireRequest->whatsapp_url }}" target="_blank">
                    {{ $hireRequest->whatsapp_country_code }} {{ $hireRequest->whatsapp_number }}
                </a>
            </p>
            <p><strong>IP:</strong> {{ $hireRequest->ip_address ?? '—' }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><strong>Preferences</strong></div>
        <div style="padding: 1.25rem;">
            <p><strong>Engagement:</strong>
                @forelse($hireRequest->engagement_models ?? [] as $key)
                    <span class="badge badge-primary">{{ config('hire.engagement_models.'.$key, $key) }}</span>
                @empty
                    <span style="color: var(--gray-500);">Not specified</span>
                @endforelse
            </p>
            <p style="margin-top: 0.75rem;"><strong>Project Phase:</strong>
                @forelse($hireRequest->project_phases ?? [] as $key)
                    <span class="badge badge-secondary">{{ config('hire.project_phases.'.$key, $key) }}</span>
                @empty
                    <span style="color: var(--gray-500);">Not specified</span>
                @endforelse
            </p>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header"><strong>Interested In</strong></div>
    <div style="padding: 1.25rem;">
        @forelse($hireRequest->offerings ?? [] as $id)
            <span class="badge badge-success" style="margin: 0.2rem;">{{ $offeringMap[$id] ?? 'Offering #'.$id }}</span>
        @empty
            <span style="color: var(--gray-500);">None selected</span>
        @endforelse
    </div>
</div>

@if($hireRequest->message)
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header"><strong>Project Details</strong></div>
    <div style="padding: 1.25rem; white-space: pre-wrap; line-height: 1.7;">{{ $hireRequest->message }}</div>
</div>
@endif

@if($hireRequest->attachment_path)
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header"><strong>Attachment</strong></div>
    <div style="padding: 1.25rem;">
        <a href="{{ \App\Support\PublicUpload::url($hireRequest->attachment_path) }}" target="_blank" class="btn btn-primary btn-sm">
            <i class="fas fa-download"></i> Download File
        </a>
    </div>
</div>
@endif

@endsection
