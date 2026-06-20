<article class="post-card">
    <a href="{{ route('blog.show', $post) }}" class="post-card-cover">
        @include('site.partials.optimized-image', [
            'src' => $post->cover_image_url,
            'fallback' => $post->cover_image ? \App\Support\PublicUpload::url($post->cover_image, asset('images/project-placeholder.svg')) : asset('images/project-placeholder.svg'),
            'srcset' => $post->cover_image_srcset,
            'alt' => $post->title,
            'width' => 480,
            'height' => 280,
            'sizes' => '(max-width: 768px) 100vw, 33vw',
            'lazy' => true,
        ])
    </a>
    <div class="post-card-body">
        <div class="post-card-meta">
            @if($post->published_at)
                <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('M j, Y') }}</time>
            @endif
            <span class="post-card-meta-sep">·</span>
            <span>{{ $post->reading_time_minutes }} min read</span>
            @if($post->views > 0)
                <span class="post-card-meta-sep">·</span>
                <span><i class="fas fa-eye"></i> {{ number_format($post->views) }}</span>
            @endif
        </div>
        <h3><a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a></h3>
        <p class="post-card-excerpt">{{ $post->excerpt }}</p>
        <a href="{{ route('blog.show', $post) }}" class="post-card-link">Read more <i class="fas fa-arrow-right"></i></a>
    </div>
</article>
