<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $projects = Project::published()
            ->ordered()
            ->select('slug', 'updated_at')
            ->get();

        $content = view('site.sitemap', compact('projects'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
