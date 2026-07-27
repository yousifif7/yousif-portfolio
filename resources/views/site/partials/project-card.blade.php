<div class="project-card">
    <div class="cover">
        @include('site.partials.optimized-image', [
            'src' => $project->cover_image_url,
            'fallback' => \App\Support\PublicUpload::url($project->cover_image, asset('images/project-placeholder.svg')),
            'srcset' => $project->cover_image_srcset,
            'alt' => $project->title,
            'width' => 480,
            'height' => 300,
            'sizes' => '(max-width: 768px) 100vw, 33vw',
            'lazy' => true,
        ])
        @if($project->is_featured)
            <span class="badge-featured">Featured</span>
        @endif
    </div>
    <div class="body">
        @if($project->category || $project->completed_at)
            <div class="project-card-meta">
                @if($project->category)
                    <span><i class="fas fa-layer-group"></i> {{ $project->category }}</span>
                @endif
                @if($project->completed_at)
                    <span><i class="fas fa-calendar"></i> {{ $project->completed_at->format('M Y') }}</span>
                @endif
            </div>
        @endif
        <h3>{{ $project->title }}</h3>
        <p class="desc">{{ $project->short_description }}</p>
        @if($project->skills->isNotEmpty())
            <div class="tech-stack">
                @foreach($project->skills->take(4) as $skill)
                    <span class="tech-badge" style="background: {{ $skill->color ? $skill->color . '22' : 'var(--gray-100)' }}; color: {{ $skill->color ?: 'var(--gray-700)' }}">
                        {{ $skill->name }}
                    </span>
                @endforeach
                @if($project->skills->count() > 4)
                    <span class="tech-badge">+{{ $project->skills->count() - 4 }}</span>
                @endif
            </div>
        @endif
        <div class="actions">
            <a href="{{ route('projects.show', $project) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Details</a>
            @if($project->live_url)
                <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm"><i class="fas fa-external-link-alt"></i> Live</a>
            @endif
            @if($project->github_url)
                <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm"><i class="fab fa-github"></i></a>
            @endif
        </div>
    </div>
</div>
