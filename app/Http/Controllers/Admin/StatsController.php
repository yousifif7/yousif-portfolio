<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\VisitorStatsService;

class StatsController extends Controller
{
    public function index(VisitorStatsService $stats)
    {
        $summary = $stats->summary();
        $daily = $stats->dailyVisits(30);
        $topPages = $stats->topPages(10);
        $topReferrers = $stats->topReferrers(10);
        $topProjects = $stats->topProjects(10);
        $topPosts = $stats->topPosts(10);
        $recentVisits = $stats->recentVisits(15);

        return view('admin.stats.index', compact(
            'summary',
            'daily',
            'topPages',
            'topReferrers',
            'topProjects',
            'topPosts',
            'recentVisits'
        ));
    }
}
