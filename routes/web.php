<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ComboController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\PointController;
use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\AgeLimitController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\ShowtimeController;
use App\Http\Controllers\Admin\AdminRoomController;
use App\Http\Controllers\Admin\AdminSeatController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\CustomerRankController;
use App\Http\Controllers\Admin\PointHistoryController;
use App\Http\Controllers\Admin\AdminSeatTypeController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\AdminAttributeController;
use App\Http\Controllers\Admin\AdminAttributeValueController;
use App\Http\Controllers\Admin\AdminProductVariantController;
use App\Http\Controllers\Admin\CustomerRankPromotionController;

use App\Http\Controllers\Admin\AdminProductCategoriesController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Seat routes from HEAD
    Route::get('seats/edit-bulk', [AdminSeatController::class, 'editBulk'])->name('seats.editBulk');
    Route::put('seats/update-bulk', [AdminSeatController::class, 'updateBulk'])->name('seats.bulkUpdate');

    Route::resource('seats', AdminSeatController::class);

    Route::get('product-categories/trash', [AdminProductCategoriesController::class, 'trash'])->name('product-categories.trash');
    Route::post('product-categories/{id}/restore', [AdminProductCategoriesController::class, 'restore'])->name('product-categories.restore');
    Route::delete('product-categories/{id}/force-delete', [AdminProductCategoriesController::class, 'forceDelete'])->name('product-categories.forceDelete');
    Route::resource('product-categories', AdminProductCategoriesController::class);

    Route::resource('seats', AdminSeatController::class);
    Route::resource('attributes', AdminAttributeController::class);
    Route::resource('attribute-values', AdminAttributeValueController::class);
    Route::resource('product-variants', AdminProductVariantController::class);
    Route::resource('products', AdminProductController::class);

    // Room-types routes from HEAD
    Route::delete('room-types/{id}/deactivate', [RoomTypeController::class, 'deactivate'])->name('room-types.deactivate');
    Route::resource('room-types', RoomTypeController::class)->except(['destroy']);

    // Showtimes routes from HEAD
    Route::delete('showtimes/{id}/deactivate', [ShowtimeController::class, 'deactivate'])->name('showtimes.deactivate');
    Route::resource('showtimes', ShowtimeController::class)->except(['destroy']);

    // Movies routes from HEAD
    Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
    Route::get('/movies/create', [MovieController::class, 'create'])->name('movies.create');
    Route::post('/movies', [MovieController::class, 'store'])->name('movies.store');
    Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');
    Route::get('/movies/{id}/edit', [MovieController::class, 'edit'])->name('movies.edit');
    Route::put('/movies/{id}', [MovieController::class, 'update'])->name('movies.update');
    Route::delete('/movies/{id}', [MovieController::class, 'destroy'])->name('movies.destroy');

    // Tạo suất chiếu tự động (HEAD)
    Route::post('/showtimes', [ShowtimeController::class, 'storeAuto'])->name('showtimes.storeAuto');

    //Room
    Route::resource('rooms', AdminRoomController::class);

    //Son
    Route::get('countries', [CountryController::class, 'index'])->name('countries.index');
    Route::get('countries-add', [CountryController::class, 'create'])->name('countries.create');
    Route::get('countries-edit', [CountryController::class, 'edit'])->name('countries.edit');
    Route::get('countries/trash', [CountryController::class, 'trash'])->name('countries.trash');
    Route::post('countries/{id}/restore', [CountryController::class, 'restore'])->name('countries.restore');
    Route::delete('countries/{id}/force-delete', [CountryController::class, 'forceDelete'])->name('countries.forceDelete');
    Route::resource('countries', CountryController::class);
    Route::get('cities', [CityController::class, 'index'])->name('index');
    Route::get('cities-add', [CityController::class, 'create'])->name('create');
    Route::get('cities-edit', [CityController::class, 'edit'])->name('edit');
    Route::get('cities/trash', [CityController::class, 'trash'])->name('cities.trash');
    Route::PATCH('cities/{id}/restore', [CityController::class, 'restore'])->name('cities.restore');
    Route::delete('cities/{id}/force-delete', [CityController::class, 'forceDelete'])->name('cities.forceDelete');
    Route::resource('cities', CityController::class);
    Route::get('cinemas', [CinemaController::class, 'index'])->name('index');
    Route::get('cinemas-add', [CinemaController::class, 'create'])->name('create');
    Route::get('cinemas-edit', [CinemaController::class, 'edit'])->name('edit');
    Route::get('cinemas-detail', [CinemaController::class, 'show'])->name('cinemas.show');
    Route::get('cinemas/trash', [CinemaController::class, 'trash'])->name('cinemas.trash');
    Route::patch('cinemas/{id}/restore', [CinemaController::class, 'restore'])->name('cinemas.restore');
    Route::delete('cinemas/{id}/force-delete', [CinemaController::class, 'forceDelete'])->name('cinemas.forceDelete');
    Route::resource('cinemas', CinemaController::class);
});

// Routes quản lý seat-type từ origin/Giang
Route::prefix('Admin/seat-type')->name('seat-type.')->group(function () {
    Route::get('/', [AdminSeatTypeController::class, 'index'])->name('index'); // Danh sách loại ghế
    Route::get('create', [AdminSeatTypeController::class, 'create'])->name('create'); // Form tạo loại ghế
    Route::post('store', [AdminSeatTypeController::class, 'store'])->name('store'); // Lưu loại ghế mới
    Route::get('edit/{id}', [AdminSeatTypeController::class, 'edit'])->name('edit'); // Form chỉnh sửa loại ghế
    Route::put('update/{id}', [AdminSeatTypeController::class, 'update'])->name('update'); // Cập nhật loại ghế
    Route::delete('{id}', [AdminSeatTypeController::class, 'destroy'])->name('destroy'); // Xóa mềm loại ghế
    Route::get('trash', [AdminSeatTypeController::class, 'trash'])->name('trash'); // Danh sách thùng rác
    Route::patch('{id}/restore', [AdminSeatTypeController::class, 'restore'])->name('restore'); // Khôi phục loại ghế
    Route::delete('{id}/force-delete', [AdminSeatTypeController::class, 'forceDelete'])->name('force-delete'); // Xóa vĩnh viễn loại ghế
});

// Route::delete('admin/movies/bulk-delete', [AdminMovieController::class, 'bulkDelete'])->name('admin.movies.bulkDelete');
Route::delete('admin/genres/bulk-delete', [\App\Http\Controllers\Admin\GenreController::class, 'bulkDelete'])->name('admin.genres.bulkDelete');

Route::prefix('admin')->name('admin.')->group(function () {
   Route::resource('genres', App\Http\Controllers\Admin\GenreController::class);
});

Route::prefix('admin/age-limits')->name('admin.age_limits.')->group(function () {
    Route::get('/', [AgeLimitController::class, 'index'])->name('index');
    Route::get('/create', [AgeLimitController::class, 'create'])->name('create');
    Route::post('/', [AgeLimitController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [AgeLimitController::class, 'edit'])->name('edit');
    Route::put('/{id}', [AgeLimitController::class, 'update'])->name('update');
    Route::delete('/{id}', [AgeLimitController::class, 'destroy'])->name('destroy');
});
Route::get('admin/age-limits', [AgeLimitController::class, 'index'])->name('admin.age_limits.index');
Route::post('admin/age-limits/bulk-delete', [AgeLimitController::class, 'bulkDelete'])->name('admin.age_limits.bulkDelete');

Route::resource('admin/users', UserController::class);

Route::prefix('customers-rank')->name('customers-rank.')->group(function () {
    Route::get('deleted', [CustomerRankController::class, 'deleted'])->name('deleted');
    Route::get('deleted/{id}', [CustomerRankController::class, 'deletedShow'])->name('deleted.show');
    Route::delete('{customerRank}/soft-delete', [CustomerRankController::class, 'softDelete'])->name('softDelete');
    Route::get('deleted/detail/{id}', [CustomerRankController::class, 'deletedShow'])->name('deleted-detail');
    Route::post('deleted/{id}/restore', [CustomerRankController::class, 'restore'])->name('restore');
    Route::delete('deleted/{id}/force-delete', [CustomerRankController::class, 'forceDelete'])->name('forceDelete');    
});
Route::resource('admin/customers-rank', CustomerRankController::class);

Route::prefix('roles')->name('roles.')->group(function () {
    Route::get('deleted', [RoleController::class, 'deleted'])->name('deleted');
    Route::get('deleted/{id}', [RoleController::class, 'deletedShow'])->name('deleted.show');
    Route::get('deleted/detail/{id}', [RoleController::class, 'deletedShow'])->name('deleted-detail');
    Route::post('deleted/{id}/restore', [RoleController::class, 'restore'])->name('restore');
    Route::delete('deleted/{id}/force-delete', [RoleController::class, 'forceDelete'])->name('forceDelete');
    Route::delete('{role}/soft-delete', [RoleController::class, 'softDelete'])->name('softDelete');
});
Route::resource('admin/roles', RoleController::class);

Route::prefix('admin/payment_methods')->group(function () {
    Route::get('/', [PaymentMethodController::class, 'index'])->name('payment_methods.index');    // danh sách + tìm kiếm lọc
    Route::get('/{id}', [PaymentMethodController::class, 'show'])->name('payment_methods.show');   // xem chi tiết
    Route::get('/{paymentMethod}/edit-status', [PaymentMethodController::class, 'editStatus'])->name('payment_methods.editStatus');
    Route::put('/{paymentMethod}/update-status', [PaymentMethodController::class, 'updateStatus'])->name('payment_methods.updateStatus');
});

Route::get('admin/bookings', [BookingController::class, 'index'])->name('admin.bookings.index');
Route::get('admin/bookingShow/{id}', [BookingController::class, 'show'])->name('admin.bookings.show');
Route::get('admin/bookings/{booking}/edit-status', [BookingController::class, 'editStatus'])->name('admin.bookings.editStatus');
Route::put('admin/bookings/{booking}/update-status', [BookingController::class, 'updateStatus'])->name('admin.bookings.updateStatus');



Route::get('admin/payments', [PaymentController::class, 'index'])->name('admin.payments.index');
Route::get('admin/payments/{payment}', [PaymentController::class, 'show'])->name('admin.payments.show');
Route::get('admin/payments/{payment}/edit-status', [PaymentController::class, 'editStatus'])->name('admin.payments.editStatus');
Route::put('admin/payments/{payment}/update-status', [PaymentController::class, 'updateStatus'])->name('admin.payments.updateStatus');

Route::get('admin/tickets', [TicketController::class, 'index'])->name('tickets.index');
Route::get('admin/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');

Route::get('admin/promotions/trashed', [PromotionController::class, 'trashed'])->name('promotions.trashed');
Route::post('admin/promotions/restore/{id}', [PromotionController::class, 'restore'])->name('promotions.restore');
Route::delete('admin/promotions/force-delete/{id}', [PromotionController::class, 'forceDelete'])->name('promotions.forceDelete');
Route::resource('admin/promotions', PromotionController::class)->names('promotions');

Route::get('admin/customer_rank_promotions', [CustomerRankPromotionController::class, 'index'])->name('customer_rank_promotions.index');
Route::get('admin/customer_rank_promotions/create', [CustomerRankPromotionController::class, 'create'])->name('customer_rank_promotions.create');
Route::post('admin/customer_rank_promotions', [CustomerRankPromotionController::class, 'store'])->name('customer_rank_promotions.store');
Route::get('admin/customer_rank_promotions/{customer_rank_id}/{promotion_id}', [CustomerRankPromotionController::class, 'show'])->name('customer_rank_promotions.show');
Route::get('admin/customer_rank_promotions/{customer_rank_id}/{promotion_id}/edit', [CustomerRankPromotionController::class, 'edit'])->name('customer_rank_promotions.edit');
Route::put('admin/customer_rank_promotions/{customer_rank_id}/{promotion_id}', [CustomerRankPromotionController::class, 'update'])->name('customer_rank_promotions.update');
Route::delete('admin/customer_rank_promotions/{customer_rank_id}/{promotion_id}', [CustomerRankPromotionController::class, 'destroy'])->name('customer_rank_promotions.destroy');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('points', PointController::class)->only(['index', 'show']);
    Route::resource('point_history', PointHistoryController::class)->only(['index', 'show']);
    Route::patch('point_history/toggle/{id}', [PointHistoryController::class, 'toggle'])->name('point_history.toggle');
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('combos', ComboController::class)->names('combos');

    Route::get('products/{id}/variants', [AdminProductController::class, 'getVariants'])->name('products.variants');
});