<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\Showtime;
use App\Models\Booking;
use App\Observers\ShowtimeObserver;
use App\Observers\BookingObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Event;
use App\Events\BookingConfirmed;
use App\Listeners\SendBookingConfirmationEmail;

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
        
        // Đăng ký Observer cho Booking
        Booking::observe(BookingObserver::class);
        
        // Đăng ký Event và Listener
        Event::listen(BookingConfirmed::class, SendBookingConfirmationEmail::class);
    }
}