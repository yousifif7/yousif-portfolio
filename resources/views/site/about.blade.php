@extends('site.layouts.app')

@section('title', 'About - ' . ($about?->full_name ?? config('app.name')))

@section('content')

<section class="section" style="padding-top: 8rem;">
    <div class="container">
        <div class="section-header" style="margin-bottom: 3rem;">
            <div class="section-eyebrow">Get to know me</div>
            <h2 class="section-title">About Me</h2>
        </div>

        <div class="about-grid">
            <div>
                <div class="about-image-wrap">
                    <img src="{{ $about?->avatar_url ?? 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($about?->full_name ?? 'YE') }}" alt="{{ $about?->full_name }}">
                </div>

                <ul class="info-list">
                    @if($about?->full_name)
                        <li><span class="label">Name:</span><span class="value">{{ $about->full_name }}</span></li>
                    @endif
                    @if($about?->title)
                        <li><span class="label">Title:</span><span class="value">{{ $about->title }}</span></li>
                    @endif
                    @if($about?->years_of_experience)
                        <li><span class="label">Experience:</span><span class="value">{{ $about->years_of_experience }} years</span></li>
                    @endif
                    @if($about?->location)
                        <li><span class="label">Location:</span><span class="value">{{ $about->location }}</span></li>
                    @endif
                    @if($about?->nationality)
                        <li><span class="label">Nationality:</span><span class="value">{{ $about->nationality }}</span></li>
                    @endif
                    @if($about?->education_degree)
                        <li><span class="label">Education:</span><span class="value">{{ $about->education_degree }}</span></li>
                    @endif
                    @if($about?->education_university)
                        <li><span class="label">University:</span><span class="value">{{ $about->education_university }}</span></li>
                    @endif
                    @if($about?->email)
                        <li><span class="label">Email:</span><span class="value"><a href="mailto:{{ $about->email }}">{{ $about->email }}</a></span></li>
                    @endif
                </ul>

                @if($about?->cv_url)
                    <a href="{{ $about->cv_url }}" target="_blank" class="btn btn-primary btn-lg" style="margin-top: 1.5rem; width: 100%;">
                        <i class="fas fa-download"></i> Download My CV
                    </a>
                @endif
            </div>

            <div>
                <h2 style="font-size: 2rem; color: var(--dark); margin-bottom: 1.25rem;">{{ $about?->title }}</h2>
                <div style="color: var(--gray-600); font-size: 1.05rem; line-height: 1.8; white-space: pre-wrap;">{!! nl2br(e($about?->long_bio ?? $about?->short_bio)) !!}</div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem; flex-wrap: wrap;">
                    <a href="{{ route('contact') }}" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Contact Me</a>
                    <a href="{{ route('projects.index') }}" class="btn btn-outline"><i class="fas fa-briefcase"></i> See My Work</a>
                </div>
            </div>
        </div>
    </div>
</section>

@if($skills->isNotEmpty())
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">My Stack</div>
            <h2 class="section-title">Skills</h2>
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

@if($experiences->isNotEmpty())
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">My Journey</div>
            <h2 class="section-title">Experience</h2>
        </div>
        <div class="timeline">
            @foreach($experiences as $exp)
                <div class="timeline-item">
                    <div class="timeline-period">{{ $exp->period }}</div>
                    <h3>{{ $exp->position }}</h3>
                    <div class="company">{{ $exp->company }}@if($exp->location) — {{ $exp->location }}@endif</div>
                    @if($exp->description)<p>{{ $exp->description }}</p>@endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@foreach($sections as $section)
    <section class="section {{ $loop->even ? 'section-alt' : '' }} dynamic-section">
        <div class="container">
            <div class="section-header">
                @if($section->icon)
                    <div style="font-size: 2.5rem; color: var(--primary); margin-bottom: 0.5rem;"><i class="{{ $section->icon }}"></i></div>
                @endif
                <h2 class="section-title">{{ $section->title }}</h2>
                @if($section->subtitle)<p class="section-subtitle">{{ $section->subtitle }}</p>@endif
            </div>
            <div class="content" style="max-width: 800px; margin: 0 auto;">{!! nl2br(e($section->content)) !!}</div>
        </div>
    </section>
@endforeach

@endsection
