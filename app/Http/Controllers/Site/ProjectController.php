<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectView;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $skillFilter = $request->query('skill');
        $categoryFilter = $request->query('category');
        $search = $request->query('q');

        $projects = Project::published()
            ->with('skills', 'images')
            ->when($skillFilter, function ($q) use ($skillFilter) {
                $q->whereHas('skills', fn ($s) => $s->where('skills.slug', $skillFilter));
            })
            ->when($categoryFilter, fn ($q) => $q->where('category', $categoryFilter))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('title', 'like', "%$search%")
                          ->orWhere('short_description', 'like', "%$search%")
                          ->orWhere('description', 'like', "%$search%");
                });
            })
            ->ordered()
            ->paginate(9)
            ->withQueryString();

        $skills = Skill::active()->ordered()->get();
        $categories = Project::published()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('site.projects.index', compact('projects', 'skills', 'categories'));
    }

    public function show(Project $project, Request $request)
    {
        abort_unless($project->is_published, 404);

        $this->recordView($project, $request);
        $project->load('skills', 'images');

        $related = $project->relatedProjects(3);

        return view('site.projects.show', compact('project', 'related'));
    }

    private function recordView(Project $project, Request $request): void
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return;
        }

        $project->increment('views');

        $ip = $request->ip();
        $today = now()->toDateString();

        $alreadyViewedToday = ProjectView::where('project_id', $project->id)
            ->where('ip_address', $ip)
            ->where('viewed_on', $today)
            ->exists();

        if ($alreadyViewedToday) {
            return;
        }

        try {
            ProjectView::create([
                'project_id' => $project->id,
                'ip_address' => $ip,
                'session_id' => $request->hasSession() ? $request->session()->getId() : null,
                'user_agent' => $request->userAgent(),
                'referrer' => $request->headers->get('referer'),
                'viewed_on' => $today,
                'viewed_at' => now(),
            ]);

            $project->increment('unique_views');
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
