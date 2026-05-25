<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Experience;
use App\Models\Section;
use App\Models\Service;
use App\Models\Skill;

class AboutController extends Controller
{
    public function show()
    {
        $about = About::first();
        $skills = Skill::active()->ordered()->get()->groupBy('category');
        $experiences = Experience::orderByDesc('start_date')->get();
        $services = Service::active()->ordered()->get();
        $sections = Section::active()->ordered()->get();

        return view('site.about', compact('about', 'skills', 'experiences', 'services', 'sections'));
    }
}
