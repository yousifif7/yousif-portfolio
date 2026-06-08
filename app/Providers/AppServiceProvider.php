<?php

namespace App\Providers;

use App\Models\About;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
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
        Paginator::defaultView('pagination::bootstrap-4');

        View::composer('site.*', function ($view) {
            $view->with('siteAbout', About::first());
            $view->with('siteSettings', Setting::all_keyed());
        });

        View::composer('admin.*', function ($view) {
            $view->with('siteSettings', Setting::all_keyed());
        });
    }
}
