<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\Showtime;
use App\Observers\ShowtimeObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Eloquent\Relations\Relation;

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

        // Morph map cho tất cả các model
        Relation::morphMap([
            'age_limit' => \App\Models\AgeLimit::class,
            'attribute' => \App\Models\Attribute::class,
            'attribute_value' => \App\Models\AttributeValue::class,
            'banner' => \App\Models\Banner::class,
            'booking' => \App\Models\Booking::class,
            'booking_item' => \App\Models\BookingItem::class,
            'cinema' => \App\Models\Cinema::class,
            'city' => \App\Models\City::class,
            'combo' => \App\Models\Combo::class,
            'combo_package_item' => \App\Models\ComboPackageItem::class,
            'config' => \App\Models\Config::class,
            'country' => \App\Models\Country::class,
            'customer_rank' => \App\Models\CustomerRank::class,
            'customer_rank_promotion' => \App\Models\CustomerRankPromotion::class,
            'genre' => \App\Models\Genre::class,
            'movie' => \App\Models\Movie::class,
            'movie_genre' => \App\Models\MovieGenre::class,
            'notification' => \App\Models\Notification::class,
            'password_reset' => \App\Models\PasswordReset::class,
            'payment' => \App\Models\Payment::class,
            'payment_method' => \App\Models\PaymentMethod::class,
            'point' => \App\Models\Point::class,
            'point_history' => \App\Models\PointHistory::class,
            'product' => \App\Models\Product::class,
            'product_category' => \App\Models\ProductCategory::class,
            'product_variant' => \App\Models\ProductVariant::class,
            'product_variant_option' => \App\Models\ProductVariantOption::class,
            'promotion' => \App\Models\Promotion::class,
            'review' => \App\Models\Review::class,
            'role' => Role::class,
            'room' => \App\Models\Room::class,
            'room_seat_configuration' => \App\Models\RoomSeatConfiguration::class,
            'room_seat_type_constraint' => \App\Models\RoomSeatTypeConstraint::class,
            'showtime' => Showtime::class,
            'showtime_seat_state' => \App\Models\ShowtimeSeatState::class,
            'slider' => \App\Models\Slider::class,
            'ticket' => \App\Models\Ticket::class,
            'user' => \App\Models\User::class,
        ]);
    }
}