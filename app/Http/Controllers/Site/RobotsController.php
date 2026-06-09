<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        $sitemapUrl = route('sitemap');

        $content = implode("\n", [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /storage',
            'Allow: /',
            '',
            "Sitemap: {$sitemapUrl}",
        ]);

        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
