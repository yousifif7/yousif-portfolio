@extends('admin.layouts.app')

@section('title', 'Projects')
@section('page_title', 'Projects')

@section('content')

<div class="page-header">
    <div class="title">
        <h2>All Projects</h2>
        <div class="subtitle">Manage your portfolio projects.</div>
    </div>
    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Project</a>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" style="display: flex; gap: 0.5rem; flex: 1;">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search projects..." style="max-width: 300px;">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Skills</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                    <tr>
                        <td><img src="{{ $project->cover_image_url }}" style="width: 56px; height: 40px; object-fit: cover; border-radius: 6px;"></td>
                        <td>
                            <strong>{{ $project->title }}</strong>
                            @if($project->is_featured) <span class="badge badge-warning">Featured</span> @endif
                            <div style="font-size: 0.82rem; color: var(--gray-500);">{{ Str::limit($project->short_description, 60) }}</div>
                        </td>
                        <td>{{ $project->category ?? '—' }}</td>
                        <td>
                            @foreach($project->skills->take(3) as $skill)
                                <span class="badge badge-info">{{ $skill->name }}</span>
                            @endforeach
                            @if($project->skills->count() > 3)
                                <span class="badge badge-secondary">+{{ $project->skills->count() - 3 }}</span>
                            @endif
                        </td>
                        <td>
                            @if($project->is_published)
                                <span class="badge badge-success">Published</span>
                            @else
                                <span class="badge badge-secondary">Draft</span>
                            @endif
                        </td>
                        <td>
                            <div title="Total views"><i class="fas fa-eye" style="color: var(--gray-500);"></i> {{ number_format($project->views) }}</div>
                            <div title="Unique views" style="font-size: 0.8rem; color: var(--gray-500);"><i class="fas fa-user"></i> {{ number_format($project->unique_views) }}</div>
                        </td>
                        <td class="actions">
                            <a href="{{ route('projects.show', $project) }}" target="_blank" class="btn btn-outline btn-sm" title="Preview"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-primary btn-sm"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" style="display: inline;" onsubmit="return confirm('Delete this project? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7"><div class="empty-state"><i class="fas fa-folder-open"></i><p>No projects yet. Create your first one!</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">{{ $projects->links() }}</div>

@endsection
