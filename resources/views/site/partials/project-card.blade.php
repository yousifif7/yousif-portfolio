<div class="project-card">
    <div class="cover">
        <img src="{{ $project->cover_image_url }}" alt="{{ $project->title }}" loading="lazy">
        @if($project->is_featured)
            <span class="badge-featured">Featured</span>
        @endif
    </div>
    <div class="body">
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
