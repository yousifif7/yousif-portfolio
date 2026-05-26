<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\Skill;
use App\Services\VisitorStatsService;

class DashboardController extends Controller
{
    public function index(VisitorStatsService $visitorStats)
    {
        $visits = $visitorStats->summary();

        $stats = [
            'projects' => Project::count(),
            'published_projects' => Project::published()->count(),
            'skills' => Skill::count(),
            'unread_messages' => ContactMessage::unread()->count(),
            'total_messages' => ContactMessage::count(),
            'total_views' => (int) Project::sum('views'),
            'total_unique_views' => (int) Project::sum('unique_views'),
            'visits_today' => $visits['today_visits'],
            'visits_today_unique' => $visits['today_unique'],
            'visits_week' => $visits['week_visits'],
            'visits_total' => $visits['total_visits'],
            'visits_total_unique' => $visits['total_unique'],
        ];

        $recentMessages = ContactMessage::latest()->limit(5)->get();
        $recentProjects = Project::latest()->limit(5)->get();
        $topProjects = Project::published()->orderByDesc('views')->limit(5)->get();

        return view('admin.dashboard.index', compact('stats', 'recentMessages', 'recentProjects', 'topProjects'));
    }
}
