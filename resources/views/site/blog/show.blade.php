@extends('site.layouts.app')

@section('title', ($post->meta_title ?: $post->title) . ' - ' . ($siteSettings['site_name'] ?? $siteAbout?->full_name ?? config('app.name')))
@section('description', $post->meta_description ?: $post->excerpt)
@section('og_type', 'article')
@section('og_image', $post->cover_image ? $post->cover_image_url : null)
@section('canonical', route('blog.show', $post))

@push('preload')
    @if($post->cover_image)
        <link rel="preload" as="image" href="{{ $post->cover_image_url }}" type="image/webp" fetchpriority="high">
    @endif
@endpush

@push('scripts')
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $post->title,
    'description' => $post->excerpt,
    'url' => route('blog.show', $post),
    'image' => $post->cover_image ? $post->cover_image_url : null,
    'datePublished' => $post->published_at?->toIso8601String(),
    'dateModified' => $post->updated_at->toIso8601String(),
    'author' => [
        '@type' => 'Person',
        'name' => $siteAbout?->full_name ?? config('app.name'),
        'url' => url('/'),
    ],
    'publisher' => [
        '@type' => 'Person',
        'name' => $siteAbout?->full_name ?? config('app.name'),
    ],
    'timeRequired' => 'PT'.$post->reading_time_minutes.'M',
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('blog.index')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $post->title],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')

<article class="section page-hero-spacing">
    <div class="container container-md">
        <a href="{{ route('blog.index') }}" class="blog-back-link">
            <i class="fas fa-arrow-left"></i> Back to blog
        </a>

        <header class="blog-post-header">
            <div class="post-card-meta">
                @if($post->published_at)
                    <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('F j, Y') }}</time>
                @endif
                <span class="post-card-meta-sep">·</span>
                <span>{{ $post->reading_time_minutes }} min read</span>
                <span class="post-card-meta-sep">·</span>
                <span><i class="fas fa-eye"></i> {{ number_format($post->views) }} views</span>
            </div>
            <h1 class="blog-post-title">{{ $post->title }}</h1>
            <p class="blog-post-excerpt">{{ $post->excerpt }}</p>
        </header>

        @if($post->cover_image)
            <div class="blog-post-cover">
                @include('site.partials.optimized-image', [
                    'src' => $post->cover_image_url,
                    'fallback' => \App\Support\PublicUpload::url($post->cover_image, asset('images/project-placeholder.svg')),
                    'srcset' => $post->cover_image_srcset,
                    'alt' => $post->title,
                    'width' => 960,
                    'height' => 540,
                    'sizes' => '(max-width: 768px) 100vw, 960px',
                    'priority' => true,
                    'lazy' => false,
                ])
            </div>
        @endif

        <div class="blog-post-content project-content prose">
            {!! $post->rendered_body !!}
        </div>

        <div class="blog-post-cta">
            <div class="blog-post-cta-inner">
                <div>
                    <h2>Need help with a Laravel project?</h2>
                    <p>Let's talk about your idea and build something great together.</p>
                </div>
                <a href="{{ route('hire') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-handshake"></i> Hire Me
                </a>
            </div>
        </div>

        @if($recentPosts->isNotEmpty())
            <div class="blog-recent">
                <h2>More articles</h2>
                <div class="blog-grid blog-grid-compact">
                    @foreach($recentPosts as $recent)
                        @include('site.partials.post-card', ['post' => $recent])
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</article>

@endsection
