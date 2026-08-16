<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\StudentRegistration;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
        $pendingCount = StudentRegistration::where('status', 'pending')->count();
        $view->with('pendingCount', $pendingCount);
        Paginator::useBootstrapFive();
    });
    }
}
