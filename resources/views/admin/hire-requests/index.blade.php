@extends('admin.layouts.app')
@section('title', 'Hire Requests')
@section('page_title', 'Hire Requests')

@section('content')

<div class="filter-tabs">
    <a href="{{ route('admin.hire-requests.index') }}" class="{{ $filter === 'all' ? 'active' : '' }}">All</a>
    <a href="{{ route('admin.hire-requests.index', ['filter' => 'unread']) }}" class="{{ $filter === 'unread' ? 'active' : '' }}">Unread</a>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" class="search-form-inline">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search requests...">
            <button class="btn btn-primary">Search</button>
        </form>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Status</th><th>From</th><th>WhatsApp</th><th>Offerings</th><th>Date</th><th></th></tr></thead>
            <tbody>
                @forelse($requests as $req)
                    <tr style="{{ ! $req->is_read ? 'background: rgba(99,102,241,0.04);' : '' }}">
                        <td>
                            @if(! $req->is_read) <span class="badge badge-warning">Unread</span>
                            @else <span class="badge badge-secondary">Read</span> @endif
                        </td>
                        <td>
                            <strong>{{ $req->name }}</strong>
                            <div style="color: var(--gray-500); font-size: 0.82rem;">{{ $req->email }}</div>
                        </td>
                        <td>{{ $req->whatsapp_country_code }} {{ $req->whatsapp_number }}</td>
                        <td><span class="badge badge-primary">{{ count($req->offerings ?? []) }} selected</span></td>
                        <td><span title="{{ $req->created_at }}">{{ $req->created_at->diffForHumans() }}</span></td>
                        <td class="actions">
                            <a href="{{ route('admin.hire-requests.show', $req) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                            <form method="POST" action="{{ route('admin.hire-requests.destroy', $req) }}" style="display:inline;" onsubmit="return confirm('Delete this request?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty-state"><i class="fas fa-handshake"></i><p>No hire requests yet.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">{{ $requests->links() }}</div>

@endsection
