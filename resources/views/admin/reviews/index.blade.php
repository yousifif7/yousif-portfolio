@extends('admin.layouts.app')
@section('title', 'Reviews')
@section('page_title', 'Client Reviews')

@section('content')

<div class="filter-tabs">
    <a href="{{ route('admin.reviews.index') }}" class="{{ $filter === 'all' ? 'active' : '' }}">All</a>
    <a href="{{ route('admin.reviews.index', ['filter' => 'pending']) }}" class="{{ $filter === 'pending' ? 'active' : '' }}">Pending</a>
    <a href="{{ route('admin.reviews.index', ['filter' => 'approved']) }}" class="{{ $filter === 'approved' ? 'active' : '' }}">Approved</a>
    <a href="{{ route('admin.reviews.index', ['filter' => 'rejected']) }}" class="{{ $filter === 'rejected' ? 'active' : '' }}">Rejected</a>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" class="search-form-inline">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search reviews...">
            <button class="btn btn-primary">Search</button>
        </form>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Status</th><th>Author</th><th>Rating</th><th>Review</th><th>Date</th><th></th></tr></thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr style="{{ $review->status === 'pending' ? 'background: rgba(99,102,241,0.04);' : '' }}">
                        <td>
                            @if($review->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($review->status === 'approved')
                                <span class="badge badge-success">Approved</span>
                            @else
                                <span class="badge badge-secondary">Rejected</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $review->name }}</strong>
                            <div style="color: var(--gray-500); font-size: 0.82rem;">{{ $review->email }}</div>
                            @if($review->company)
                                <div style="color: var(--gray-500); font-size: 0.82rem;">{{ $review->role ? $review->role . ' · ' : '' }}{{ $review->company }}</div>
                            @endif
                        </td>
                        <td>
                            <span style="color: #f59e0b;">
                                @for($i = 1; $i <= $review->rating; $i++)<i class="fas fa-star"></i>@endfor
                            </span>
                        </td>
                        <td style="max-width: 320px;">{{ Str::limit($review->content, 120) }}</td>
                        <td><span title="{{ $review->created_at }}">{{ $review->created_at->diffForHumans() }}</span></td>
                        <td class="actions">
                            @if($review->status === 'pending')
                                <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-success btn-sm" title="Approve"><i class="fas fa-check"></i></button>
                                </form>
                                <form method="POST" action="{{ route('admin.reviews.reject', $review) }}" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-secondary btn-sm" title="Reject"><i class="fas fa-times"></i></button>
                                </form>
                            @elseif($review->status === 'rejected')
                                <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-success btn-sm" title="Approve"><i class="fas fa-check"></i></button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.reviews.reject', $review) }}" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-secondary btn-sm" title="Unpublish"><i class="fas fa-eye-slash"></i></button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" style="display:inline;" onsubmit="return confirm('Delete this review?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty-state"><i class="fas fa-star"></i><p>No reviews yet.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">{{ $reviews->links() }}</div>

@endsection
