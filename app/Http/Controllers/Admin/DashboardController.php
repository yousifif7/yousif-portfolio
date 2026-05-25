<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\Skill;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'projects' => Project::count(),
            'published_projects' => Project::published()->count(),
            'skills' => Skill::count(),
            'unread_messages' => ContactMessage::unread()->count(),
            'total_messages' => ContactMessage::count(),
            'total_views' => Project::sum('views'),
        ];

        $recentMessages = ContactMessage::latest()->limit(5)->get();
        $recentProjects = Project::latest()->limit(5)->get();
        $topProjects = Project::published()->orderByDesc('views')->limit(5)->get();

        return view('admin.dashboard.index', compact('stats', 'recentMessages', 'recentProjects', 'topProjects'));
    }
}
