@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')

<div class="stats-grid">
    <div class="stat-card">
        <div class="icon primary"><i class="fas fa-briefcase"></i></div>
        <div class="info">
            <div class="label">Total Projects</div>
            <div class="value">{{ $stats['projects'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="icon success"><i class="fas fa-check-circle"></i></div>
        <div class="info">
            <div class="label">Published</div>
            <div class="value">{{ $stats['published_projects'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="icon info"><i class="fas fa-code"></i></div>
        <div class="info">
            <div class="label">Skills</div>
            <div class="value">{{ $stats['skills'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="icon warning"><i class="fas fa-envelope"></i></div>
        <div class="info">
            <div class="label">Unread Messages</div>
            <div class="value">{{ $stats['unread_messages'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="icon danger"><i class="fas fa-eye"></i></div>
        <div class="info">
            <div class="label">Total Views</div>
            <div class="value">{{ $stats['total_views'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="icon primary"><i class="fas fa-inbox"></i></div>
        <div class="info">
            <div class="label">Total Messages</div>
            <div class="value">{{ $stats['total_messages'] }}</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-envelope-open"></i> Recent Messages</h2>
            <a href="{{ route('admin.messages.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="card-body">
            @forelse($recentMessages as $msg)
                <div style="padding: 0.8rem 0; border-bottom: 1px solid var(--gray-200); display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 600; color: var(--dark);">{{ $msg->name }}</div>
                        <div style="color: var(--gray-500); font-size: 0.85rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $msg->subject }}</div>
                    </div>
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        @if(! $msg->is_read)
                            <span class="badge badge-warning">New</span>
                        @endif
                        <a href="{{ route('admin.messages.show', $msg) }}" class="btn btn-outline btn-sm">View</a>
                    </div>
                </div>
            @empty
                <div class="empty-state"><i class="fas fa-inbox"></i><p>No messages yet</p></div>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-briefcase"></i> Recent Projects</h2>
            <a href="{{ route('admin.projects.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="card-body">
            @forelse($recentProjects as $proj)
                <div style="padding: 0.8rem 0; border-bottom: 1px solid var(--gray-200); display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 600; color: var(--dark);">{{ $proj->title }}</div>
                        <div style="color: var(--gray-500); font-size: 0.85rem;">{{ $proj->category ?? '—' }} • {{ $proj->views }} views</div>
                    </div>
                    <div style="display: flex; gap: 0.4rem;">
                        @if($proj->is_published)
                            <span class="badge badge-success">Published</span>
                        @else
                            <span class="badge badge-secondary">Draft</span>
                        @endif
                        <a href="{{ route('admin.projects.edit', $proj) }}" class="btn btn-outline btn-sm">Edit</a>
                    </div>
                </div>
            @empty
                <div class="empty-state"><i class="fas fa-folder"></i><p>No projects yet</p></div>
            @endforelse
        </div>
    </div>
</div>

@if($topProjects->isNotEmpty())
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header"><h2><i class="fas fa-fire"></i> Most Viewed Projects</h2></div>
    <div class="card-body">
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>#</th><th>Title</th><th>Category</th><th>Views</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @foreach($topProjects as $i => $p)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $p->title }}</strong></td>
                            <td>{{ $p->category ?? '—' }}</td>
                            <td>{{ $p->views }}</td>
                            <td><span class="badge {{ $p->is_published ? 'badge-success' : 'badge-secondary' }}">{{ $p->is_published ? 'Published' : 'Draft' }}</span></td>
                            <td class="actions"><a href="{{ route('admin.projects.edit', $p) }}" class="btn btn-outline btn-sm">Edit</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection
