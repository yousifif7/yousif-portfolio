<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostView;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');

        $posts = Post::published()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('title', 'like', "%$search%")
                        ->orWhere('excerpt', 'like', "%$search%")
                        ->orWhere('body', 'like', "%$search%");
                });
            })
            ->ordered()
            ->paginate(9)
            ->withQueryString();

        return view('site.blog.index', compact('posts'));
    }

    public function show(Post $post, Request $request)
    {
        abort_unless($post->is_published, 404);

        $this->recordView($post, $request);

        $recentPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->ordered()
            ->limit(3)
            ->get();

        return view('site.blog.show', compact('post', 'recentPosts'));
    }

    public function feed(): Response
    {
        $posts = Cache::remember('blog.feed', 3600, function () {
            return Post::published()
                ->ordered()
                ->limit(20)
                ->get();
        });

        $siteName = \App\Models\Setting::get('site_name')
            ?? \App\Models\About::first()?->full_name
            ?? config('app.name');

        $content = view('site.blog.feed', compact('posts', 'siteName'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }

    private function recordView(Post $post, Request $request): void
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return;
        }

        $post->increment('views');

        $ip = $request->ip();
        $today = now()->toDateString();

        $alreadyViewedToday = PostView::where('post_id', $post->id)
            ->where('ip_address', $ip)
            ->where('viewed_on', $today)
            ->exists();

        if ($alreadyViewedToday) {
            return;
        }

        try {
            PostView::create([
                'post_id' => $post->id,
                'ip_address' => $ip,
                'session_id' => $request->hasSession() ? $request->session()->getId() : null,
                'user_agent' => $request->userAgent(),
                'referrer' => $request->headers->get('referer'),
                'viewed_on' => $today,
                'viewed_at' => now(),
            ]);

            $post->increment('unique_views');
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
