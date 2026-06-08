<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'cover_image',
        'client',
        'category',
        'live_url',
        'github_url',
        'completed_at',
        'is_featured',
        'is_published',
        'sort_order',
        'views',
        'unique_views',
    ];

    protected $casts = [
        'completed_at' => 'date',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'sort_order' => 'integer',
        'views' => 'integer',
        'unique_views' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Project $project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title).'-'.Str::random(5);
            }
        });
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'project_skill');
    }

    public function viewLogs(): HasMany
    {
        return $this->hasMany(ProjectView::class);
    }

    public function getCoverImageUrlAttribute(): string
    {
        return \App\Support\PublicUpload::url($this->cover_image, asset('images/project-placeholder.svg'));
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('completed_at');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function relatedProjects(int $limit = 3): Collection
    {
        $skillIds = $this->skills->pluck('id');
        $keywords = $this->matchKeywords();

        $candidates = static::published()
            ->where('id', '!=', $this->id)
            ->with('skills', 'images')
            ->get();

        if ($candidates->isEmpty()) {
            return collect();
        }

        return $candidates
            ->map(fn (Project $candidate) => [
                'project' => $candidate,
                'score' => $this->relatednessScore($candidate, $skillIds, $keywords),
            ])
            ->sort(function (array $a, array $b) {
                if ($a['score'] !== $b['score']) {
                    return $b['score'] <=> $a['score'];
                }

                return $b['project']->views <=> $a['project']->views;
            })
            ->take($limit)
            ->pluck('project')
            ->values();
    }

    private function relatednessScore(Project $candidate, Collection $skillIds, Collection $keywords): int
    {
        $score = $candidate->skills->pluck('id')->intersect($skillIds)->count() * 10;

        if ($this->category && $candidate->category === $this->category) {
            $score += 15;
        }

        $score += $candidate->matchKeywords()->intersect($keywords)->count() * 3;

        return $score;
    }

    private function matchKeywords(): Collection
    {
        $text = implode(' ', array_filter([
            $this->title,
            $this->short_description,
            $this->category,
        ]));

        return $this->tokenizeKeywords($text);
    }

    private function tokenizeKeywords(string $text): Collection
    {
        static $stopWords = [
            'the', 'and', 'for', 'with', 'this', 'that', 'from', 'are', 'was',
            'has', 'have', 'been', 'will', 'can', 'all', 'any', 'not', 'but',
            'you', 'our', 'its', 'via', 'using', 'used', 'also', 'into',
        ];

        return collect(preg_split('/\s+/', strtolower($text)))
            ->map(fn ($word) => preg_replace('/[^a-z0-9]/', '', $word))
            ->filter(fn ($word) => strlen($word) >= 3 && ! in_array($word, $stopWords, true))
            ->unique()
            ->values();
    }
}
