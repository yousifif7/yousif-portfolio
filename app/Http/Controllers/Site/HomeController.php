<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Section;
use App\Models\Service;
use App\Models\Skill;

class HomeController extends Controller
{
    public function index()
    {
        $about = About::first();
        $featuredProjects = Project::published()
            ->featured()
            ->ordered()
            ->with('skills', 'images')
            ->limit(6)
            ->get();

        $allProjectsCount = Project::published()->count();
        $skills = Skill::active()->ordered()->get()->groupBy('category');
        $services = Service::active()->ordered()->get();
        $experiences = Experience::orderByDesc('start_date')->get();
        $sections = Section::active()->ordered()->get();

        return view('site.home', compact(
            'about',
            'featuredProjects',
            'allProjectsCount',
            'skills',
            'services',
            'experiences',
            'sections'
        ));
    }
}
