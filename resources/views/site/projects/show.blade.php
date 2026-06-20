@extends('site.layouts.app')

@section('title', $project->title . ' - ' . ($siteSettings['site_name'] ?? $siteAbout?->full_name ?? config('app.name')))
@section('description', $project->short_description)
@section('og_type', 'article')
@section('og_image', $project->cover_image_url)
@section('canonical', route('projects.show', $project))

@push('preload')
    <link rel="preload" as="image" href="{{ $project->cover_image_url }}" type="image/webp" fetchpriority="high">
@endpush

@push('scripts')
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@@context' => 'https://schema.org',
    '@type' => 'CreativeWork',
    'name' => $project->title,
    'description' => $project->short_description,
    'url' => route('projects.show', $project),
    'image' => $project->cover_image_url,
    'dateCreated' => $project->completed_at?->toIso8601String(),
    'author' => [
        '@type' => 'Person',
        'name' => $siteAbout?->full_name ?? config('app.name'),
        'url' => url('/'),
    ],
    'keywords' => $project->skills->pluck('name')->implode(', ') ?: null,
    'genre' => $project->category,
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Projects', 'item' => route('projects.index')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $project->title],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')

<section class="project-hero">
    <div class="container">
        <a href="{{ route('projects.index') }}" style="color: var(--primary); margin-bottom: 1.5rem; display: inline-flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-arrow-left"></i> Back to all projects
        </a>

        <h1 class="page-inner-title">{{ $project->title }}</h1>

        <div class="project-meta">
            @if($project->client)
                <span><i class="fas fa-user-tie"></i> Client: {{ $project->client }}</span>
            @endif
            @if($project->category)
                <span><i class="fas fa-tag"></i> {{ $project->category }}</span>
            @endif
            @if($project->completed_at)
                <span><i class="fas fa-calendar"></i> {{ $project->completed_at->format('M Y') }}</span>
            @endif
            <span><i class="fas fa-eye"></i> {{ $project->views }} views</span>
        </div>

        <p class="lead">
            {{ $project->short_description }}
        </p>

        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            @if($project->live_url)
                <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="btn btn-primary"><i class="fas fa-external-link-alt"></i> View Live</a>
            @endif
            @if($project->github_url)
                <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="btn btn-outline"><i class="fab fa-github"></i> Source Code</a>
            @endif
        </div>
    </div>
</section>

<section class="section" style="padding-top: 2rem;">
    <div class="container container-md">
        {{-- Cover image --}}
        <div class="project-cover">
            @include('site.partials.optimized-image', [
                'src' => $project->cover_image_url,
                'fallback' => \App\Support\PublicUpload::url($project->cover_image, asset('images/project-placeholder.svg')),
                'srcset' => $project->cover_image_srcset,
                'alt' => $project->title,
                'width' => 960,
                'height' => 600,
                'sizes' => '(max-width: 768px) 100vw, 960px',
                'priority' => true,
                'lazy' => false,
            ])
        </div>

        {{-- Tech stack --}}
        @if($project->skills->isNotEmpty())
            <div style="margin-bottom: 2rem;">
                <h3 style="color: var(--dark); margin-bottom: 1rem; font-size: 1.1rem;">Technologies Used</h3>
                <div class="tech-stack">
                    @foreach($project->skills as $skill)
                        <span class="tech-badge" style="padding: 0.5rem 1rem; font-size: 0.9rem; background: {{ $skill->color ? $skill->color . '22' : 'var(--gray-100)' }}; color: {{ $skill->color ?: 'var(--gray-700)' }}">
                            @if($skill->icon)<i class="{{ $skill->icon }}"></i>@endif {{ $skill->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        @if($project->hasCaseStudy())
            <div class="case-study">
                <h2 class="case-study-title">Case Study</h2>
                <div class="case-study-grid">
                    @if($project->problem)
                        <div class="case-study-block">
                            <div class="case-study-label"><i class="fas fa-exclamation-circle"></i> The Problem</div>
                            <div class="case-study-body">{!! nl2br(e($project->problem)) !!}</div>
                        </div>
                    @endif
                    @if($project->solution)
                        <div class="case-study-block">
                            <div class="case-study-label"><i class="fas fa-lightbulb"></i> The Solution</div>
                            <div class="case-study-body">{!! nl2br(e($project->solution)) !!}</div>
                        </div>
                    @endif
                    @if($project->result)
                        <div class="case-study-block case-study-result">
                            <div class="case-study-label"><i class="fas fa-chart-line"></i> The Result</div>
                            <div class="case-study-body">{!! nl2br(e($project->result)) !!}</div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Description --}}
        <div class="project-content">
            <h2>About this project</h2>
            <div>{!! nl2br(e($project->description)) !!}</div>
        </div>

        {{-- Gallery --}}
        @if($project->images->isNotEmpty())
            <div style="margin-top: 2.5rem;">
                <h2 style="color: var(--dark); margin-bottom: 1.25rem;">Gallery</h2>
                <div class="project-gallery">
                    @foreach($project->images as $image)
                        <a href="{{ $image->url }}" target="_blank">
                            <img src="{{ $image->url }}" alt="{{ $image->caption ?? $project->title }}" loading="lazy">
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Hire CTA --}}
        <div class="project-hire-cta">
            <div class="project-hire-cta-inner">
                <div>
                    <h2>Want something like this?</h2>
                    <p>Tell me about your project and let's build it together.</p>
                </div>
                <a href="{{ route('hire', ['project' => $project->title]) }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-handshake"></i> Hire Me for a Similar Project
                </a>
            </div>
        </div>

        {{-- Related --}}
        @if($related->isNotEmpty())
            <div style="margin-top: 4rem;">
                <h2 style="color: var(--dark); margin-bottom: 1.5rem;">Related projects</h2>
                <div class="grid grid-3">
                    @foreach($related as $rel)
                        @include('site.partials.project-card', ['project' => $rel])
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

@endsection
