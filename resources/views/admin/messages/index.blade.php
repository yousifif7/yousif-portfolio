@extends('admin.layouts.app')
@section('title', 'Messages')
@section('page_title', 'Contact Messages')

@section('content')

<div class="filter-tabs">
    <a href="{{ route('admin.messages.index') }}" class="{{ $filter === 'all' ? 'active' : '' }}">All</a>
    <a href="{{ route('admin.messages.index', ['filter' => 'unread']) }}" class="{{ $filter === 'unread' ? 'active' : '' }}">Unread</a>
    <a href="{{ route('admin.messages.index', ['filter' => 'unreplied']) }}" class="{{ $filter === 'unreplied' ? 'active' : '' }}">Unreplied</a>
    <a href="{{ route('admin.messages.index', ['filter' => 'replied']) }}" class="{{ $filter === 'replied' ? 'active' : '' }}">Replied</a>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" class="search-form-inline">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search messages...">
            <button class="btn btn-primary">Search</button>
        </form>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Status</th><th>From</th><th>Subject</th><th>Date</th><th></th></tr></thead>
            <tbody>
                @forelse($messages as $msg)
                    <tr style="{{ ! $msg->is_read ? 'background: rgba(99,102,241,0.04);' : '' }}">
                        <td>
                            @if(! $msg->is_read) <span class="badge badge-warning">Unread</span>
                            @else <span class="badge badge-secondary">Read</span> @endif
                            @if($msg->is_replied) <span class="badge badge-success">Replied</span> @endif
                        </td>
                        <td>
                            <strong>{{ $msg->name }}</strong>
                            <div style="color: var(--gray-500); font-size: 0.82rem;">{{ $msg->email }}</div>
                        </td>
                        <td><a href="{{ route('admin.messages.show', $msg) }}" style="color: var(--dark); font-weight: 500;">{{ Str::limit($msg->subject, 70) }}</a></td>
                        <td><span title="{{ $msg->created_at }}">{{ $msg->created_at->diffForHumans() }}</span></td>
                        <td class="actions">
                            <a href="{{ route('admin.messages.show', $msg) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                            <form method="POST" action="{{ route('admin.messages.destroy', $msg) }}" style="display:inline;" onsubmit="return confirm('Delete this message?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="fas fa-inbox"></i><p>No messages.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">{{ $messages->links() }}</div>

@endsection
