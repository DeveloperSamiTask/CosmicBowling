<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->mapApiRoutes();
        $this->mapFrontendRoutes();
        $this->mapAdminRoutes();
        $this->mapLoyaltyRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace('App\Http\Controllers\Api')
            ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes are typically protected by authentication.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::middleware(['web'])
            ->prefix('be')
            ->namespace('App\Http\Controllers\Admin')
            ->group(base_path('routes/admin.php'));
    }

    protected function mapLoyaltyRoutes()
    {
        Route::middleware(['web'])
            ->prefix('be')
            ->group(base_path('routes/loyalty.php'));
    }

    /**
     * Define the "frontend" routes for the application.
     *
     * These routes are typically public facing.
     *
     * @return void
     */
    protected function mapFrontendRoutes()
    {
        Route::middleware('web')
            ->namespace('App\Http\Controllers\Frontend')
            ->group(base_path('routes/frontend.php'));
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
