@extends('site.layouts.app')

@section('title', 'Blog - ' . ($siteSettings['site_name'] ?? $siteAbout?->full_name ?? config('app.name')))
@section('description', 'Thoughts, advice, and technical articles on Laravel development, web apps, and building software.')
@section('canonical', route('blog.index'))

@if(request('q'))
    @section('robots', 'noindex, follow')
@endif

@push('head')
    <link rel="alternate" type="application/rss+xml" title="Blog RSS Feed" href="{{ route('blog.feed') }}">
@endpush

@push('scripts')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog'],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')

<section class="section page-hero-spacing">
    <div class="container">
        <div class="section-header" style="margin-bottom: 2.5rem;">
            <div class="section-eyebrow">Writing</div>
            <h1 class="section-title">Blog</h1>
            <p class="section-subtitle">Thoughts on Laravel, backend development, and lessons from building real projects.</p>
        </div>

        <form method="GET" action="{{ route('blog.index') }}" class="filters-bar">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search posts...">
            <button type="submit" class="btn btn-primary">Search</button>
            @if(request('q'))
                <a href="{{ route('blog.index') }}" class="filter-chip">Clear</a>
            @endif
            <a href="{{ route('blog.feed') }}" class="filter-chip" title="RSS Feed"><i class="fas fa-rss"></i> RSS</a>
        </form>

        @if($posts->isNotEmpty())
            <div class="blog-grid">
                @foreach($posts as $post)
                    @include('site.partials.post-card', ['post' => $post])
                @endforeach
            </div>

            <div class="pagination-wrap">
                {{ $posts->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-newspaper"></i>
                <h3>No posts yet</h3>
                <p>Check back soon — new articles are on the way.</p>
            </div>
        @endif
    </div>
</section>

@endsection
