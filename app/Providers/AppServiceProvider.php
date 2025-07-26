<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\Showtime;
use App\Observers\ShowtimeObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;

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
        Paginator::useBootstrapFive();
        app(PermissionRegistrar::class)->setRoleClass(Role::class);
        
        // Đăng ký Observer cho Showtime
        Showtime::observe(ShowtimeObserver::class);
    }
}