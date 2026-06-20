<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;
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

        $posts = Post::published()
            ->ordered()
            ->select('slug', 'updated_at', 'published_at')
            ->get();

        $content = view('site.sitemap', compact('projects', 'posts'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
