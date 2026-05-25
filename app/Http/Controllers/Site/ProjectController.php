<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;

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

    public function show(Project $project)
    {
        abort_unless($project->is_published, 404);

        $project->increment('views');
        $project->load('skills', 'images');

        $related = Project::published()
            ->where('id', '!=', $project->id)
            ->whereHas('skills', fn ($q) => $q->whereIn('skills.id', $project->skills->pluck('id')))
            ->limit(3)
            ->get();

        return view('site.projects.show', compact('project', 'related'));
    }
}
