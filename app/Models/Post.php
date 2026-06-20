<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'cover_image',
        'meta_title',
        'meta_description',
        'is_published',
        'published_at',
        'reading_time_minutes',
        'views',
        'unique_views',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'reading_time_minutes' => 'integer',
        'views' => 'integer',
        'unique_views' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title).'-'.Str::random(5);
            }

            $words = str_word_count(strip_tags($post->body ?? ''));
            $post->reading_time_minutes = max(1, (int) ceil($words / 200));

            if ($post->is_published && ! $post->published_at) {
                $post->published_at = now();
            }
        });

        static::saved(fn () => Cache::forget('blog.feed'));
        static::deleted(fn () => Cache::forget('blog.feed'));
    }

    public function viewLogs(): HasMany
    {
        return $this->hasMany(PostView::class);
    }

    public function getCoverImageUrlAttribute(): string
    {
        if (! $this->cover_image) {
            return asset('images/project-placeholder.svg');
        }

        return \App\Support\ImageOptimizer::url($this->cover_image, 960, asset('images/project-placeholder.svg'))
            ?? \App\Support\PublicUpload::url($this->cover_image, asset('images/project-placeholder.svg'));
    }

    public function getCoverImageSrcsetAttribute(): ?string
    {
        return $this->cover_image
            ? \App\Support\ImageOptimizer::srcset($this->cover_image, [480, 720, 960])
            : null;
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderByDesc('published_at')->orderByDesc('id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getRenderedBodyAttribute(): string
    {
        $body = $this->body ?? '';

        if ($body === '' || $body === strip_tags($body)) {
            return nl2br(e($body));
        }

        return $body;
    }
}
