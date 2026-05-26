<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackPageVisit
{
    private const BOT_KEYWORDS = [
        'bot', 'crawl', 'spider', 'slurp', 'mediapartners', 'facebookexternalhit',
        'preview', 'fetcher', 'monitor', 'curl', 'wget', 'python-requests',
        'go-http-client', 'headlesschrome', 'lighthouse', 'pingdom', 'uptimerobot',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldTrack($request, $response)) {
            $this->record($request);
        }

        return $response;
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET')) {
            return false;
        }

        if ($response->getStatusCode() >= 400) {
            return false;
        }

        if (Auth::check() && Auth::user()->is_admin) {
            return false;
        }

        $path = $request->path();
        if (str_starts_with($path, 'admin') || str_starts_with($path, 'storage') || $path === 'up') {
            return false;
        }

        if (! $request->acceptsHtml()) {
            return false;
        }

        return ! $this->isBot((string) $request->userAgent());
    }

    private function isBot(string $userAgent): bool
    {
        if ($userAgent === '') {
            return true;
        }

        $lower = strtolower($userAgent);
        foreach (self::BOT_KEYWORDS as $keyword) {
            if (str_contains($lower, $keyword)) {
                return true;
            }
        }

        return false;
    }

    private function record(Request $request): void
    {
        try {
            PageVisit::create([
                'url' => mb_substr($request->fullUrl(), 0, 2048),
                'path' => mb_substr('/'.ltrim($request->path(), '/'), 0, 1024),
                'route_name' => optional($request->route())->getName(),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'session_id' => $request->hasSession() ? $request->session()->getId() : null,
                'user_agent' => $request->userAgent(),
                'referrer' => $request->headers->get('referer'),
                'visited_at' => now(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
