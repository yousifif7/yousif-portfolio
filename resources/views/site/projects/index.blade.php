@extends('site.layouts.app')

@section('title', 'Projects - ' . ($siteSettings['site_name'] ?? $siteAbout?->full_name ?? config('app.name')))

@section('content')

<section class="section" style="padding-top: 8rem;">
    <div class="container">
        <div class="section-header" style="margin-bottom: 3rem;">
            <div class="section-eyebrow">My Work</div>
            <h2 class="section-title">All Projects</h2>
            <p class="section-subtitle">Browse through everything I've built — filter by technology to find something specific.</p>
        </div>

        {{-- Search + filters --}}
        <form method="GET" action="{{ route('projects.index') }}" class="filters-bar">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search projects..." style="flex: 1; min-width: 200px; max-width: 400px;">
            <button type="submit" class="btn btn-primary">Search</button>
            @if(request('q') || request('skill') || request('category'))
                <a href="{{ route('projects.index') }}" class="filter-chip">Clear filters</a>
            @endif
        </form>

        @if($skills->isNotEmpty())
            <div class="filters-bar" style="background: transparent; padding: 0; box-shadow: none;">
                <a href="{{ route('projects.index') }}" class="filter-chip {{ ! request('skill') ? 'active' : '' }}">All</a>
                @foreach($skills as $skill)
                    <a href="{{ route('projects.index', ['skill' => $skill->slug]) }}"
                       class="filter-chip {{ request('skill') === $skill->slug ? 'active' : '' }}">
                        {{ $skill->name }}
                    </a>
                @endforeach
            </div>
        @endif

        @if($projects->isNotEmpty())
            <div class="grid grid-3">
                @foreach($projects as $project)
                    @include('site.partials.project-card', ['project' => $project])
                @endforeach
            </div>

            <div class="pagination-wrap">
                {{ $projects->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <h3>No projects found</h3>
                <p>Try adjusting your filters or search terms.</p>
            </div>
        @endif
    </div>
</section>

@endsection
