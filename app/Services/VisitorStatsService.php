<?php

namespace App\Services;

use App\Models\PageVisit;
use App\Models\Post;
use App\Models\Project;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class VisitorStatsService
{
    public function summary(): array
    {
        $today = Carbon::today();
        $weekStart = Carbon::today()->subDays(6);
        $monthStart = Carbon::today()->subDays(29);
        $postMetrics = Post::query()
            ->withViewCounts()
            ->get(['id', 'views', 'unique_views']);

        return [
            'total_visits' => PageVisit::count(),
            'total_unique' => PageVisit::distinct('ip_address')->count('ip_address'),
            'today_visits' => PageVisit::where('visited_at', '>=', $today)->count(),
            'today_unique' => PageVisit::where('visited_at', '>=', $today)->distinct('ip_address')->count('ip_address'),
            'week_visits' => PageVisit::where('visited_at', '>=', $weekStart)->count(),
            'week_unique' => PageVisit::where('visited_at', '>=', $weekStart)->distinct('ip_address')->count('ip_address'),
            'month_visits' => PageVisit::where('visited_at', '>=', $monthStart)->count(),
            'month_unique' => PageVisit::where('visited_at', '>=', $monthStart)->distinct('ip_address')->count('ip_address'),
            'total_project_views' => (int) Project::sum('views'),
            'total_unique_project_views' => (int) Project::sum('unique_views'),
            'total_post_views' => (int) $postMetrics->sum(fn (Post $post) => $post->display_views),
            'total_unique_post_views' => (int) $postMetrics->sum(fn (Post $post) => $post->display_unique_views),
        ];
    }

    public function dailyVisits(int $days = 30): array
    {
        $start = Carbon::today()->subDays($days - 1);

        $rows = PageVisit::selectRaw('DATE_FORMAT(visited_at, "%Y-%m-%d") as day, COUNT(*) as visits, COUNT(DISTINCT ip_address) as unique_visitors')
            ->where('visited_at', '>=', $start)
            ->groupByRaw('DATE_FORMAT(visited_at, "%Y-%m-%d")')
            ->orderByRaw('DATE_FORMAT(visited_at, "%Y-%m-%d")')
            ->get()
            ->keyBy('day');

        $labels = [];
        $visits = [];
        $unique = [];

        foreach (CarbonPeriod::create($start, Carbon::today()) as $date) {
            $key = $date->toDateString();
            $labels[] = $date->format('M j');
            $row = $rows->get($key);
            $visits[] = $row ? (int) $row->visits : 0;
            $unique[] = $row ? (int) $row->unique_visitors : 0;
        }

        return compact('labels', 'visits', 'unique');
    }

    public function topPages(int $limit = 10): Collection
    {
        return PageVisit::selectRaw('path, route_name, COUNT(*) as visits, COUNT(DISTINCT ip_address) as unique_visitors')
            ->groupBy('path', 'route_name')
            ->orderByDesc('visits')
            ->limit($limit)
            ->get();
    }

    public function topReferrers(int $limit = 10): Collection
    {
        return PageVisit::selectRaw('referrer, COUNT(*) as visits')
            ->whereNotNull('referrer')
            ->where('referrer', '!=', '')
            ->groupBy('referrer')
            ->orderByDesc('visits')
            ->limit($limit)
            ->get();
    }

    public function topProjects(int $limit = 10): Collection
    {
        return Project::orderByDesc('unique_views')
            ->orderByDesc('views')
            ->limit($limit)
            ->get(['id', 'title', 'slug', 'category', 'views', 'unique_views', 'is_published']);
    }

    public function topPosts(int $limit = 10): Collection
    {
        return Post::withViewCounts()
            ->orderByDesc('unique_views')
            ->orderByDesc('views')
            ->limit($limit)
            ->get(['id', 'title', 'slug', 'views', 'unique_views', 'is_published', 'published_at']);
    }

    public function recentVisits(int $limit = 15): Collection
    {
        return PageVisit::latest('visited_at')->limit($limit)->get();
    }
}
