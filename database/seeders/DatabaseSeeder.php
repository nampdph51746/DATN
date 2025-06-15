<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\PasswordReset;
use App\Models\Country;
use App\Models\AgeLimit;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\MovieGenre;
use App\Models\Review;
use App\Models\City;
use App\Models\Cinema;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\SeatType;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\ShowtimeSeatState;
use App\Models\PaymentMethod;
use App\Models\Promotion;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductVariant;
use App\Models\ProductVariantOption;
use App\Models\ComboPackageItem;
use App\Models\BookingItem;
use App\Models\CustomerRank;
use App\Models\Point;
use App\Models\PointHistory;
use App\Models\CustomerRankPromotion;
use App\Models\Payment;
use App\Models\Banner;
use App\Models\Slider;
use App\Models\Notification;
use App\Models\Config;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Bảng không có khóa ngoại
        Role::factory()->count(5)->create();
        Country::factory()->count(10)->create();
        AgeLimit::factory()->count(5)->create();
        Genre::factory()->count(10)->create();
        ProductCategory::factory()->count(5)->create();
        Attribute::factory()->count(5)->create();
        CustomerRank::factory()->count(5)->create();
        PaymentMethod::factory()->count(5)->create();
        Promotion::factory()->count(10)->create();
        RoomType::factory()->count(5)->create();
        SeatType::factory()->count(5)->create();
        Config::factory()->count(10)->create();

        // Bảng có khóa ngoại
        City::factory()->count(10)->create();
        AttributeValue::factory()->count(20)->create();
        Product::factory()->count(20)->create();
        User::factory()->count(20)->create();
        Movie::factory()->count(20)->create();
        Cinema::factory()->count(10)->create();

        // Bảng phụ thuộc tiếp theo
        Room::factory()->count(20)->create();
        MovieGenre::factory()->count(30)->create();
        Review::factory()->count(50)->create();
        ProductVariant::factory()->count(50)->create();
        Point::factory()->count(20)->create();
        CustomerRankPromotion::factory()->count(10)->create();

        // Bảng phụ thuộc sâu hơn
        Seat::factory()->count(100)->create();
        ProductVariantOption::factory()->count(50)->create();
        ComboPackageItem::factory()->count(20)->create();
        Showtime::factory()->count(50)->create();
        Booking::factory()->count(50)->create();
        PasswordReset::factory()->count(10)->create();

        // Bảng cuối
        ShowtimeSeatState::factory()->count(200)->create();
        Ticket::factory()->count(100)->create();
        BookingItem::factory()->count(50)->create();
        PointHistory::factory()->count(50)->create();
        Payment::factory()->count(50)->create();
        Banner::factory()->count(10)->create();
        Slider::factory()->count(10)->create();
        Notification::factory()->count(50)->create();
    }
}