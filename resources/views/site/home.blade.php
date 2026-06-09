@extends('site.layouts.app')

@section('title', ($siteSettings['site_name'] ?? $about?->full_name ?? 'Portfolio') . ' - ' . ($about?->title ?? 'Developer'))
@section('description', $about?->short_bio ?? ($siteSettings['seo_meta_description'] ?? ''))

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@graph' => array_filter([
        [
            '@type' => 'WebSite',
            'name' => $siteSettings['site_name'] ?? $about?->full_name ?? config('app.name'),
            'url' => url('/'),
        ],
        $about ? array_filter([
            '@type' => 'Person',
            'name' => $about->full_name,
            'jobTitle' => $about->title,
            'description' => $about->short_bio,
            'url' => url('/'),
            'image' => $about->avatar_url,
            'email' => $about->email ? 'mailto:' . $about->email : null,
            'address' => $about->location ? ['@type' => 'PostalAddress', 'addressLocality' => $about->location] : null,
            'nationality' => $about->nationality ? ['@type' => 'Country', 'name' => $about->nationality] : null,
            'sameAs' => array_values(array_filter([
                $siteSettings['github_url'] ?? null,
                $siteSettings['linkedin_url'] ?? null,
                $siteSettings['twitter_url'] ?? null,
            ])),
        ]) : null,
    ]),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')

@php
    $heroGreeting = $siteSettings['hero_greeting'] ?? "Hello, I'm";
    $heroHeadline = $siteSettings['hero_headline'] ?? $about?->full_name ?? 'Your Name';
    $heroSubheadline = $siteSettings['hero_subheadline'] ?? $about?->tagline ?? 'Building things on the web.';
    $heroCtaText = $siteSettings['hero_cta_text'] ?? null;
    $heroCtaUrl = $siteSettings['hero_cta_url'] ?? null;
@endphp

{{-- ===== Hero ===== --}}
<section class="hero">
    <div class="container hero-inner">
        <div>
            <div class="hero-greeting">{{ $heroGreeting }}</div>
            <h1 class="hero-title">
                {{ $heroHeadline }}<br>
                <span class="accent">{{ $about?->title ?? 'Developer' }}</span>
            </h1>
            <p class="hero-subtitle">{{ $heroSubheadline }}</p>
            <p class="hero-bio">{{ $about?->short_bio }}</p>
            <div class="hero-buttons">
                @if($heroCtaText && $heroCtaUrl)
                    <a href="{{ $heroCtaUrl }}" class="btn btn-primary btn-lg"><i class="fas fa-arrow-right"></i> {{ $heroCtaText }}</a>
                @else
                    <a href="{{ route('projects.index') }}" class="btn btn-primary btn-lg"><i class="fas fa-briefcase"></i> View Projects</a>
                @endif
                <a href="{{ route('contact') }}" class="btn btn-outline btn-lg"><i class="fas fa-paper-plane"></i> Get In Touch</a>
                @if($about?->cv_url)
                    <a href="{{ $about->cv_url }}" target="_blank" class="btn btn-light btn-lg"><i class="fas fa-download"></i> Download CV</a>
                @endif
            </div>
        </div>

        <div class="hero-image">
            <div class="hero-image-wrapper">
                <img src="{{ $about?->avatar_url ?? 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($about?->full_name ?? 'YE') }}" alt="{{ $about?->full_name }}">
            </div>
            @if($about?->years_of_experience)
            <div class="floating-badge exp">
                <i class="fas fa-award"></i>
                <span><span class="num">{{ $about->years_of_experience }}+</span> Years Exp.</span>
            </div>
            @endif
            @if($allProjectsCount > 0)
            <div class="floating-badge projects">
                <i class="fas fa-rocket"></i>
                <span><span class="num">{{ $allProjectsCount }}+</span> Projects</span>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- ===== Stats strip ===== --}}
@if($about)
<section class="stats-strip">
    <div class="container">
        <div class="grid grid-4">
            <div class="stat">
                <div class="number">{{ $about->years_of_experience ?? 0 }}+</div>
                <div class="label">Years of Experience</div>
            </div>
            <div class="stat">
                <div class="number">{{ $allProjectsCount }}+</div>
                <div class="label">Projects Delivered</div>
            </div>
            <div class="stat">
                <div class="number">{{ $skills->flatten()->count() }}+</div>
                <div class="label">Technologies</div>
            </div>
            <div class="stat">
                <div class="number">{{ $services->count() }}</div>
                <div class="label">Services Offered</div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ===== Services ===== --}}
@if($services->isNotEmpty())
<section class="section section-alt" id="services">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">What I Do</div>
            <h2 class="section-title">Services</h2>
            <p class="section-subtitle">Solutions I provide to bring your ideas to life with clean code and solid architecture.</p>
        </div>
        <div class="grid grid-3">
            @foreach($services as $service)
                <div class="service-card">
                    <div class="icon-wrap"><i class="{{ $service->icon ?: 'fas fa-cog' }}"></i></div>
                    <h3>{{ $service->title }}</h3>
                    <p>{{ $service->description }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===== Skills ===== --}}
@if($skills->isNotEmpty())
<section class="section" id="skills">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">My Stack</div>
            <h2 class="section-title">Skills &amp; Technologies</h2>
            <p class="section-subtitle">The tools and technologies I work with on a daily basis.</p>
        </div>

        @foreach($skills as $category => $list)
            <div class="skill-group">
                <h3 class="skill-group-title">{{ $category ?: 'Other' }}</h3>
                <div class="skill-grid">
                    @foreach($list as $skill)
                        <div class="skill-pill">
                            <span class="skill-icon" style="color: {{ $skill->color ?: 'var(--primary)' }}">
                                <i class="{{ $skill->icon ?: 'fas fa-code' }}"></i>
                            </span>
                            <span class="skill-name">{{ $skill->name }}</span>
                            <div class="skill-bar">
                                <div class="fill" data-width="{{ $skill->proficiency }}" style="width: 0"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- ===== Featured projects ===== --}}
@if($featuredProjects->isNotEmpty())
<section class="section section-alt" id="projects">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">Portfolio</div>
            <h2 class="section-title">Featured Projects</h2>
            <p class="section-subtitle">A selection of my recent work — from CRMs to APIs to full web apps.</p>
        </div>

        <div class="grid grid-3">
            @foreach($featuredProjects as $project)
                @include('site.partials.project-card', ['project' => $project])
            @endforeach
        </div>

        <div style="text-align: center; margin-top: 3rem;">
            <a href="{{ route('projects.index') }}" class="btn btn-primary btn-lg">
                View All Projects <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ===== Experience timeline ===== --}}
@if($experiences->isNotEmpty())
<section class="section" id="experience">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">Career</div>
            <h2 class="section-title">Experience</h2>
            <p class="section-subtitle">My professional journey so far.</p>
        </div>

        <div class="timeline">
            @foreach($experiences as $exp)
                <div class="timeline-item">
                    <div class="timeline-period">{{ $exp->period }}</div>
                    <h3>{{ $exp->position }}</h3>
                    <div class="company">{{ $exp->company }}@if($exp->location) — {{ $exp->location }}@endif</div>
                    @if($exp->description)
                        <p>{{ $exp->description }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===== Dynamic sections ===== --}}
@foreach($sections as $section)
    <section class="section {{ $loop->even ? 'section-alt' : '' }} dynamic-section" id="{{ $section->slug }}">
        <div class="container">
            <div class="section-header">
                @if($section->icon)
                    <div style="font-size: 2.5rem; color: var(--primary); margin-bottom: 0.5rem;"><i class="{{ $section->icon }}"></i></div>
                @endif
                <h2 class="section-title">{{ $section->title }}</h2>
                @if($section->subtitle)
                    <p class="section-subtitle">{{ $section->subtitle }}</p>
                @endif
            </div>
            <div class="content" style="max-width: 800px; margin: 0 auto;">
                {!! nl2br(e($section->content)) !!}
            </div>
            @if($section->image)
                <div style="max-width: 800px; margin: 2rem auto 0;">
                    <img src="{{ $section->image_url }}" alt="{{ $section->title }}" style="border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                </div>
            @endif
        </div>
    </section>
@endforeach

{{-- ===== CTA ===== --}}
<section class="section section-alt">
    <div class="container">
        <div style="background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 4rem 2rem; border-radius: 24px; text-align: center;">
            <h2 style="font-size: 2.2rem; margin-bottom: 1rem;">Have a project in mind?</h2>
            <p style="opacity: 0.9; font-size: 1.1rem; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                Let's build something great together. I'm always open to discussing new opportunities.
            </p>
            <a href="{{ route('contact') }}" class="btn btn-light btn-lg"><i class="fas fa-paper-plane"></i> Let's Talk</a>
        </div>
    </div>
</section>

@endsection
