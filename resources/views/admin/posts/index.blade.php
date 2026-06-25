@extends('admin.layouts.app')
@section('title', 'Blog Posts')
@section('page_title', 'Blog Posts')

@section('content')

@php $status = request('status', 'all'); @endphp

<div class="page-header">
    <div class="title">
        <h2>Blog Posts</h2>
        <div class="subtitle">Share thoughts, advice, and technical articles.</div>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Post</a>
</div>

<div class="filter-tabs">
    <a href="{{ route('admin.posts.index') }}" class="{{ $status === 'all' ? 'active' : '' }}">All</a>
    <a href="{{ route('admin.posts.index', ['status' => 'published']) }}" class="{{ $status === 'published' ? 'active' : '' }}">Published</a>
    <a href="{{ route('admin.posts.index', ['status' => 'draft']) }}" class="{{ $status === 'draft' ? 'active' : '' }}">Draft</a>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" class="search-form-inline">
            @if($status !== 'all')
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search posts...">
            <select name="sort" class="form-control">
                <option value="newest" @selected(request('sort', 'newest') === 'newest')>Recently updated</option>
                <option value="oldest" @selected(request('sort') === 'oldest')>Oldest first</option>
                <option value="most_views" @selected(request('sort') === 'most_views')>Most views</option>
                <option value="least_views" @selected(request('sort') === 'least_views')>Least views</option>
            </select>
            <button class="btn btn-primary">Search</button>
        </form>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Reading time</th>
                    <th>Views</th>
                    <th>Published</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr>
                        <td>
                            <img src="{{ $post->cover_image_url }}" alt="" style="width: 56px; height: 40px; object-fit: cover; border-radius: 6px;">
                        </td>
                        <td>
                            <strong>{{ $post->title }}</strong>
                            <div style="color: var(--gray-500); font-size: 0.82rem;">{{ Str::limit($post->excerpt, 70) }}</div>
                        </td>
                        <td>
                            @if($post->is_published)
                                <span class="badge badge-success">Published</span>
                            @else
                                <span class="badge badge-secondary">Draft</span>
                            @endif
                        </td>
                        <td>{{ $post->reading_time_minutes }} min</td>
                        <td>
                            <div class="views-cell" title="Total / unique views">
                                <i class="fas fa-eye"></i> {{ number_format($post->display_views) }}
                                <span class="views-sep">·</span>
                                <i class="fas fa-user"></i> {{ number_format($post->display_unique_views) }}
                            </div>
                        </td>
                        <td>
                            @if($post->published_at)
                                <span title="{{ $post->published_at->format('M j, Y g:i A') }}">{{ $post->published_at->format('M j, Y') }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="actions">
                            @if($post->is_published)
                                <a href="{{ route('blog.show', $post) }}" target="_blank" class="btn btn-outline btn-sm" title="View"><i class="fas fa-eye"></i></a>
                            @endif
                            <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary btn-sm" title="Edit"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" style="display:inline;" onsubmit="return confirm('Delete this post?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7"><div class="empty-state"><i class="fas fa-newspaper"></i><p>No posts yet. Write your first one!</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">{{ $posts->links() }}</div>

@endsection
