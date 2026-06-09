@extends('admin.layouts.app')
@section('title', 'Development Offerings')
@section('page_title', 'Development Offerings')

@section('content')

<div class="page-header">
    <div class="title">
        <h2>Hire Page Offerings</h2>
        <div class="subtitle">Laravel development types shown on the Hire Me page.</div>
    </div>
    <a href="{{ route('admin.offerings.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Offering</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Icon</th><th>Title</th><th>Description</th><th>Status</th><th>Order</th><th></th></tr></thead>
            <tbody>
                @forelse($offerings as $offering)
                    <tr>
                        <td style="font-size: 1.4rem; color: var(--primary);"><i class="{{ $offering->icon ?: 'fas fa-code' }}"></i></td>
                        <td><strong>{{ $offering->title }}</strong></td>
                        <td><span style="color: var(--gray-600); font-size: 0.88rem;">{{ Str::limit($offering->description, 100) }}</span></td>
                        <td>@if($offering->is_active)<span class="badge badge-success">Active</span>@else<span class="badge badge-secondary">Hidden</span>@endif</td>
                        <td>{{ $offering->sort_order }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.offerings.edit', $offering) }}" class="btn btn-primary btn-sm"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.offerings.destroy', $offering) }}" style="display:inline;" onsubmit="return confirm('Delete this offering?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty-state"><i class="fas fa-laptop-code"></i><p>No offerings yet.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">{{ $offerings->links() }}</div>

@endsection
