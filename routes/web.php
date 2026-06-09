<?php

use App\Http\Controllers\Admin\AboutController as AdminAboutController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DevelopmentOfferingController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\HireRequestController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\StatsController;
use App\Http\Controllers\Site\AboutController;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\HireController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\ProjectController;
use App\Http\Controllers\Site\ReviewController;
use App\Http\Controllers\Site\RobotsController;
use App\Http\Controllers\Site\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/reviews', fn () => redirect()->route('home')->withFragment('reviews'));
Route::get('/about', [AboutController::class, 'show'])->name('about');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/hire', [HireController::class, 'show'])->name('hire');
Route::post('/hire', [HireController::class, 'store'])->name('hire.store');
Route::get('/robots.txt', RobotsController::class)->name('robots');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

/*
|--------------------------------------------------------------------------
| Admin auth (guests)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Admin protected routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.alt');

        // Visitor stats
        Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');

        // Site settings
        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

        // Profile / About
        Route::get('/about', [AdminAboutController::class, 'edit'])->name('about.edit');
        Route::put('/about', [AdminAboutController::class, 'update'])->name('about.update');

        // Project image deletion - must be BEFORE the resource so 'images' doesn't match as a project ID
        Route::delete('/projects/images/{image}', [AdminProjectController::class, 'deleteImage'])
            ->name('projects.images.destroy');

        // Projects CRUD
        Route::resource('projects', AdminProjectController::class)->except('show');

        // Skills CRUD
        Route::resource('skills', SkillController::class)->except('show');

        // Experiences CRUD
        Route::resource('experiences', ExperienceController::class)->except('show');

        // Services CRUD
        Route::resource('services', ServiceController::class)->except('show');

        // Development offerings (Hire page)
        Route::resource('offerings', DevelopmentOfferingController::class)->except('show');

        // Sections CRUD
        Route::resource('sections', SectionController::class)->except('show');

        // Reviews
        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('/reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
        Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

        // Hire requests
        Route::get('/hire-requests', [HireRequestController::class, 'index'])->name('hire-requests.index');
        Route::get('/hire-requests/{hireRequest}', [HireRequestController::class, 'show'])->name('hire-requests.show');
        Route::post('/hire-requests/{hireRequest}/toggle-read', [HireRequestController::class, 'toggleRead'])->name('hire-requests.toggleRead');
        Route::delete('/hire-requests/{hireRequest}', [HireRequestController::class, 'destroy'])->name('hire-requests.destroy');

        // Contact messages
        Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{message}', [MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{message}/reply', [MessageController::class, 'reply'])->name('messages.reply');
        Route::post('/messages/{message}/toggle-read', [MessageController::class, 'toggleRead'])->name('messages.toggleRead');
        Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    });
});
