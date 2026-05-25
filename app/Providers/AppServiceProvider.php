<?php

namespace App\Providers;

use App\Models\About;
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
        // Share the About profile globally with all site views, so the layout can use it
        View::composer('site.*', function ($view) {
            $about = About::first();
            $view->with('siteAbout', $about);
        });
    }
}
