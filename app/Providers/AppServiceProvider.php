<?php

namespace App\Providers;

use App\Models\About;
use App\Models\Setting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('contact-form', fn (Request $request) => Limit::perHour(5)->by($request->ip()));
        RateLimiter::for('hire-form', fn (Request $request) => Limit::perHour(5)->by($request->ip()));
        RateLimiter::for('review-form', fn (Request $request) => Limit::perHour(3)->by($request->ip()));

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Paginator::defaultView('pagination::bootstrap-4');

        View::composer('site.*', function ($view) {
            $view->with('siteAbout', Cache::rememberForever('about.current', fn () => About::first()));
            $view->with('siteSettings', Setting::all_keyed());
        });

        View::composer('admin.*', function ($view) {
            $view->with('siteSettings', Setting::all_keyed());
        });
    }
}
