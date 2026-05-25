@extends('admin.layouts.app')
@section('title', 'Services')
@section('page_title', 'Services')

@section('content')

<div class="page-header">
    <div class="title">
        <h2>Services I Offer</h2>
        <div class="subtitle">Services shown on the home page.</div>
    </div>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Service</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Icon</th><th>Title</th><th>Description</th><th>Status</th><th>Order</th><th></th></tr></thead>
            <tbody>
                @forelse($services as $service)
                    <tr>
                        <td style="font-size: 1.4rem; color: var(--primary);">@if($service->icon)<i class="{{ $service->icon }}"></i>@else <i class="fas fa-cog text-muted"></i>@endif</td>
                        <td><strong>{{ $service->title }}</strong></td>
                        <td><span style="color: var(--gray-600); font-size: 0.88rem;">{{ Str::limit($service->description, 100) }}</span></td>
                        <td>@if($service->is_active)<span class="badge badge-success">Active</span>@else<span class="badge badge-secondary">Hidden</span>@endif</td>
                        <td>{{ $service->sort_order }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-primary btn-sm"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.services.destroy', $service) }}" style="display:inline;" onsubmit="return confirm('Delete this service?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty-state"><i class="fas fa-cogs"></i><p>No services yet.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">{{ $services->links() }}</div>

@endsection
