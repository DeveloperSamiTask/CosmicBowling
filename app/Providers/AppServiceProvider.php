<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // <--- ESTA LÍNEA ES LA QUE FALTA
use App\Models\Admin\Categories;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
   public function boot(): void
    {
        View::composer('frontend.*', function ($view) {
            $view->with('categories', Categories::with('subcategories')->get());
            $view->with('title', 'Cosmic Bowling'); // Inyecta el título también
        });
    }
}
