<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RejectHoneypotBots
{
    private const ROUTE_MESSAGES = [
        'contact.store' => 'Thanks for reaching out! Your message has been sent, I will get back to you soon.',
        'hire.store' => 'Thank you! Your hire request has been submitted. I will review it and get back to you soon.',
        'reviews.store' => 'Thank you for your review! It will appear on the site once approved.',
    ];

    public function handle(Request $request, Closure $next, string $flashKey = 'success', string $redirectRoute = 'back', string $fragment = ''): Response
    {
        if ($request->filled('website')) {
            Log::info('Honeypot triggered on public form.', [
                'ip' => $request->ip(),
                'route' => $request->route()?->getName(),
            ]);

            $routeName = $request->route()?->getName();
            $message = self::ROUTE_MESSAGES[$routeName] ?? 'Thank you! Your submission has been received.';

            if ($redirectRoute === 'back') {
                return redirect()->back()->with($flashKey, $message);
            }

            $url = route($redirectRoute);

            if ($fragment !== '') {
                $url .= '#'.$fragment;
            }

            return redirect($url)->with($flashKey, $message);
        }

        return $next($request);
    }
}
