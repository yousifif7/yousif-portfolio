@extends('admin.layouts.app')

@section('title', 'Projects')
@section('page_title', 'Projects')

@section('content')

@php
    $status = request('status', 'all');
    $filterParams = request()->except(['status', 'page']);
    $hasFilters = request()->filled('q')
        || request('status', 'all') !== 'all'
        || request()->filled('sort') && request('sort') !== 'newest'
        || request()->filled('date_from')
        || request()->filled('date_to')
        || request()->filled('min_views');
@endphp

<div class="page-header">
    <div class="title">
        <h2>All Projects</h2>
        <div class="subtitle">Manage your portfolio projects.</div>
    </div>
    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Project</a>
</div>

<div class="filter-tabs">
    <a href="{{ route('admin.projects.index', $filterParams) }}" class="{{ $status === 'all' ? 'active' : '' }}">All</a>
    <a href="{{ route('admin.projects.index', array_merge($filterParams, ['status' => 'published'])) }}" class="{{ $status === 'published' ? 'active' : '' }}">Published</a>
    <a href="{{ route('admin.projects.index', array_merge($filterParams, ['status' => 'draft'])) }}" class="{{ $status === 'draft' ? 'active' : '' }}">Draft</a>
    <a href="{{ route('admin.projects.index', array_merge($filterParams, ['status' => 'featured'])) }}" class="{{ $status === 'featured' ? 'active' : '' }}">Featured</a>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" class="projects-filters">
            @if($status !== 'all')
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search projects...">
            <select name="sort" class="form-control">
                <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest first</option>
                <option value="oldest" @selected(request('sort') === 'oldest')>Oldest first</option>
                <option value="most_views" @selected(request('sort') === 'most_views')>Most views</option>
                <option value="least_views" @selected(request('sort') === 'least_views')>Least views</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control" title="Completed from">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control" title="Completed to">
            <input type="number" name="min_views" value="{{ request('min_views') }}" class="form-control" min="0" placeholder="Min views">
            <button type="submit" class="btn btn-primary">Apply</button>
            @if($hasFilters)
                <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>
    </div>

    <div class="table-wrap">
        <table class="table projects-table">
            <thead>
                <tr>
                    <th class="col-cover">Cover</th>
                    <th class="col-project">Project</th>
                    <th class="col-category">Category</th>
                    <th class="col-skills">Skills</th>
                    <th class="col-status">Status</th>
                    <th class="col-views">Views</th>
                    <th class="col-date">Completed</th>
                    <th class="col-actions"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                    <tr>
                        <td class="col-cover">
                            <img src="{{ $project->cover_image_url }}" alt="" class="project-cover-thumb">
                        </td>
                        <td class="col-project">
                            <span class="project-title" title="{{ $project->title }}">{{ $project->title }}</span>
                            @if($project->is_featured)
                                <span class="badge badge-warning">Featured</span>
                            @endif
                            @if($project->short_description)
                                <span class="project-desc" title="{{ $project->short_description }}">{{ Str::limit($project->short_description, 50) }}</span>
                            @endif
                        </td>
                        <td class="col-category">{{ $project->category ?? '—' }}</td>
                        <td class="col-skills">
                            <div class="skills-cell">
                                @foreach($project->skills->take(2) as $skill)
                                    <span class="badge badge-info">{{ $skill->name }}</span>
                                @endforeach
                                @if($project->skills->count() > 2)
                                    <span class="badge badge-secondary">+{{ $project->skills->count() - 2 }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="col-status">
                            @if($project->is_published)
                                <span class="badge badge-success">Published</span>
                            @else
                                <span class="badge badge-secondary">Draft</span>
                            @endif
                        </td>
                        <td class="col-views">
                            <div class="views-cell" title="Total / unique views">
                                <i class="fas fa-eye"></i> {{ number_format($project->views) }}
                                <span class="views-sep">·</span>
                                <i class="fas fa-user"></i> {{ number_format($project->unique_views) }}
                            </div>
                        </td>
                        <td class="col-date">
                            @if($project->completed_at)
                                <span title="{{ $project->completed_at->format('M j, Y') }}">{{ $project->completed_at->format('M Y') }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="col-actions">
                            <div class="actions">
                                <a href="{{ route('projects.show', $project) }}" target="_blank" class="btn btn-outline btn-sm" title="Preview"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-primary btn-sm" title="Edit"><i class="fas fa-pen"></i></a>
                                <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" style="display: inline;" onsubmit="return confirm('Delete this project? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8"><div class="empty-state"><i class="fas fa-folder-open"></i><p>No projects found. Try adjusting your filters or create your first one!</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">{{ $projects->links() }}</div>

@endsection
