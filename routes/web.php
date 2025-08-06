<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ComboController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\PointController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\SeatController;
use App\Http\Controllers\TicketPrintController;
use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Client\VnpayController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\AgeLimitController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\ShowtimeController;
use App\Http\Controllers\Admin\AdminRoomController;
use App\Http\Controllers\Admin\AdminSeatController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Admin\AdminMovieController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\CustomerRankController;
use App\Http\Controllers\Admin\PointHistoryController;
use App\Http\Controllers\Admin\AdminSeatTypeController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\AdminAttributeController;
use App\Http\Controllers\Client\ClientPaymentController;
use App\Http\Controllers\Client\PaymentSuccessController;
use App\Http\Controllers\Admin\AdminAttributeValueController;
use App\Http\Controllers\Admin\AdminProductVariantController;
use App\Http\Controllers\Admin\CustomerRankPromotionController;
use App\Http\Controllers\Admin\AdminProductCategoriesController;


Route::get('/', [HomeController::class, 'index'])->name('client.home');
Route::get('/movies', [HomeController::class, 'movies'])->name('client.movies');
Route::get('/movies/{id}', [HomeController::class, 'show'])->name('movies.show');
Route::get('/movies/{id}/ticket-booking', [HomeController::class, 'ticketBooking'])->name('client.movies.ticketBooking');

// Review routes
Route::middleware('auth')->group(function () {
    Route::post('/movies/{movie}/reviews', [App\Http\Controllers\Client\ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/movies/{movie}/reviews/check', [App\Http\Controllers\Client\ReviewController::class, 'checkUserCanReview'])->name('reviews.check');
});

Route::get('/movies/{movie}/reviews', [App\Http\Controllers\Client\ReviewController::class, 'getReviews'])->name('reviews.get');
Route::get('/showtimes/{showtimeId}/seat-map', [SeatController::class, 'showSeatMap'])->name('client.seats.map');
Route::post('/showtimes/{showtimeId}/reserve', [SeatController::class, 'reserveSeat'])->name('client.seats.reserve');
Route::get('/api/seats/status/{showtimeId}', [SeatController::class, 'getSeatStatus']);
Route::post('/apply-promotion-auto', [App\Http\Controllers\Client\HomeController::class, 'applyDiscountCodeAutomatically'])->name('client.applyPromotionAuto');

Route::post('/checkout/vnpay', [VnpayController::class, 'redirectToVnpay'])->name('checkout.vnpay');
Route::get('/checkout/confirmation', [CheckoutController::class, 'showConfirmation'])->name('checkout.confirmation');


Route::get('/checkout/vnpay_return', [VnpayController::class, 'vnpayReturn'])->name('vnpay.return');

Route::post('/checkout/preview', [CheckoutController::class, 'previewBooking'])->name('checkout.preview');


Route::get('/payment-success', [PaymentSuccessController::class, 'show'])->name('client.success');

Route::get('/payment-failed', function () {
    return 'Thanh toán thất bại!';
})->name('client.failed');

// Route::middleware('guest')->group(function () {
//     Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
//         ->name('password.request');

//     Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
//         ->name('password.email');

//     Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
//         ->name('password.reset');

//     Route::put('reset-password', [NewPasswordController::class, 'store'])
//         ->name('password.reset.submit');
// });

Route::post('/apply-promotion', [App\Http\Controllers\Client\HomeController::class, 'applyDiscountCode'])->name('client.applyPromotion');


Route::post('/apply-points', [App\Http\Controllers\Client\HomeController::class, 'applyPoints'])->name('client.applyPoints');
Route::get('/available-promotions', [App\Http\Controllers\Client\HomeController::class, 'getAvailablePromotions'])->name('client.getAvailablePromotions');
Route::get('/user-rank', [App\Http\Controllers\Client\HomeController::class, 'getUserRank'])->name('client.getUserRank');
Route::get('/user-points', [App\Http\Controllers\Client\HomeController::class, 'getUserPoints'])->name('client.getUserPoints');
Route::get('/user-point-history', [App\Http\Controllers\Client\HomeController::class, 'getUserPointHistory'])->name('client.getUserPointHistory');


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('my-bookings', [BookingController::class, 'myBookings'])->name('client.bookings.index');
    Route::get('my-bookings/show/{id}', [BookingController::class, 'myBookingsShow'])->name('client.bookings.show');
});

Route::middleware(['auth'])->group(function (){
Route::get('/profile', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'profile'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin,staff'])->group(function () {



    Route::prefix('admin')->name('admin.')->group(function () {
    // Seat routes from HEAD
    Route::post('seats/edit-bulk', [AdminSeatController::class, 'editBulk'])->name('seats.edit-bulk');
    Route::post('seats/update-bulk', [AdminSeatController::class, 'updateBulk'])->name('seats.update-bulk');
    Route::delete('seats/bulk-delete', [AdminSeatController::class, 'deleteBulk'])->name('seats.deleteBulk');
    Route::post('seats/store-new', [AdminSeatController::class, 'storeNew'])->name('seats.store-new');
    Route::post('seats/add-single', [AdminSeatController::class, 'addSingleSeat'])->name('seats.add-single');
    
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('product-categories/trash', [AdminProductCategoriesController::class, 'trash'])->name('product-categories.trash');
    Route::post('product-categories/{id}/restore', [AdminProductCategoriesController::class, 'restore'])->name('product-categories.restore');
    Route::delete('product-categories/{id}/force-delete', [AdminProductCategoriesController::class, 'forceDelete'])->name('product-categories.forceDelete');
    Route::resource('product-categories', AdminProductCategoriesController::class);

    Route::resource('seats', AdminSeatController::class);
    Route::resource('attributes', AdminAttributeController::class);
    Route::resource('attribute-values', AdminAttributeValueController::class);
    Route::resource('product-variants', AdminProductVariantController::class);
    Route::resource('products', AdminProductController::class);

    Route::get('product-categories/trash', [AdminProductCategoriesController::class, 'trash'])->name('product-categories.trash');
    Route::post('product-categories/{id}/restore', [AdminProductCategoriesController::class, 'restore'])->name('product-categories.restore');
    Route::delete('product-categories/{id}/force-delete', [AdminProductCategoriesController::class, 'forceDelete'])->name('product-categories.forceDelete');
    Route::resource('product-categories', AdminProductCategoriesController::class);

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
    
    // Cập nhật trạng thái suất chiếu
    Route::post('/showtimes/update-statuses', [ShowtimeController::class, 'updateStatuses'])->name('showtimes.updateStatuses');
    Route::post('/showtimes/{id}/update-status', [ShowtimeController::class, 'updateSingleStatus'])->name('showtimes.updateSingleStatus');
    Route::post('/showtimes/{id}/update-status-manual', [ShowtimeController::class, 'updateStatus'])->name('showtimes.updateStatus');

    //Room
    Route::resource('rooms', AdminRoomController::class);
    Route::patch('rooms/{id}/update-percentages', [AdminRoomController::class, 'updateSeatPercentages'])->name('rooms.updatePercentages');
    Route::post('rooms/{room}/update-seat-percentages', [AdminRoomController::class, 'updateSeatPercentages'])->name('rooms.update-seat-percentages');
    Route::post('rooms/{room}/update-capacity', [AdminRoomController::class, 'updateCapacity'])->name('rooms.update-capacity');

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
    Route::patch('cities/{id}/restore', [CityController::class, 'restore'])->name('cities.restore');
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

    // QR code scanning routes (chỉ staff và admin)
    Route::middleware(['auth', 'role:admin,staff'])->group(function () {
        Route::prefix('api/qr')->group(function () {
            Route::post('/scan', [QrCodeController::class, 'scanQr'])->name('qr.scan');
            Route::post('/check-status', [QrCodeController::class, 'checkTicketStatus'])->name('qr.check');
            Route::post('/scan-ticket', [QrCodeController::class, 'scanTicketByCode'])->name('qr.scanTicket');
        });
        // Route in vé riêng theo ticket_code
        // Route::get('/tickets/{ticket_code}/print', [TicketPrintController::class, 'printTicket'])->name('tickets.print');
        // Route in chung đồ ăn, đồ uống theo booking_code
        Route::get('admin/bookings/{booking_code}/print', [BookingController::class, 'print'])->name('bookings.print');
        // Trang quét QR code cho nhân viên
        Route::get('/qr-scanner', function () {
            return view('admin.Qrcode-scanner'); // Nếu bạn đổi tên view thành qr-scanner thì sửa lại ở đây
        })->name('qr.scanner');
    });


});

// Routes quản lý seat-type từ origin/Giang
Route::prefix('admin/seat-type')->name('seat-type.')->group(function () {
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


Route::delete('admin/movies/bulk-delete', [AdminMovieController::class, 'bulkDelete'])->name('admin.movies.bulkDelete');
// Route import ghế từ Excel
Route::post('admin/seats/import', [App\Http\Controllers\Admin\AdminSeatController::class, 'importExcel'])->name('admin.seats.import');
Route::delete('admin/genres/bulk-delete', [\App\Http\Controllers\Admin\GenreController::class, 'bulkDelete'])->name('admin.genres.bulkDelete');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('movies', \App\Http\Controllers\Admin\AdminMovieController::class);
    Route::resource('directors', \App\Http\Controllers\Admin\AdminDirectorController::class);
    Route::resource('actors', \App\Http\Controllers\Admin\AdminActorController::class);
});

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

Route::prefix('admin/customers-rank')->name('customers-rank.')->group(function () {
    Route::get('deleted', [CustomerRankController::class, 'deleted'])->name('deleted');
    Route::get('deleted/{id}', [CustomerRankController::class, 'deletedShow'])->name('deleted.show');
    Route::delete('{customerRank}/soft-delete', [CustomerRankController::class, 'softDelete'])->name('softDelete');
    Route::get('deleted/detail/{id}', [CustomerRankController::class, 'deletedShow'])->name('deleted-detail');
    Route::post('deleted/{id}/restore', [CustomerRankController::class, 'restore'])->name('restore');
    Route::delete('deleted/{id}/force-delete', [CustomerRankController::class, 'forceDelete'])->name('forceDelete');    
});
Route::resource('admin/customers-rank', CustomerRankController::class);

Route::prefix('admin/roles')->name('roles.')->group(function () {
    Route::get('deleted', [RoleController::class, 'deleted'])->name('deleted');
    Route::get('deleted/{id}', [RoleController::class, 'deletedShow'])->name('deleted.show');
    Route::get('deleted/detail/{id}', [RoleController::class, 'deletedShow'])->name('deleted-detail');
    Route::post('deleted/{id}/restore', [RoleController::class, 'restore'])->name('restore');
    Route::delete('deleted/{id}/force-delete', [RoleController::class, 'forceDelete'])->name('forceDelete');
    Route::delete('{role}/soft-delete', [RoleController::class, 'softDelete'])->name('softDelete');
});
Route::resource('admin/roles', RoleController::class);

Route::prefix('admin/payment_methods')->group(function () {
    Route::get('/', [PaymentMethodController::class, 'index'])->name('payment_methods.index');    
    Route::get('/{id}', [PaymentMethodController::class, 'show'])->name('payment_methods.show');   
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
    Route::post('/admin/combos/check-duplicate', [App\Http\Controllers\Admin\ComboController::class, 'checkDuplicate'])->name('admin.combos.checkDuplicate');
    Route::get('products/{id}/variants', [AdminProductController::class, 'getVariants'])->name('products.variants');
});

});

// Test route for barcode
Route::get('/test-barcode-api', function () {
    $barcodeService = new \App\Services\BarcodeService();
    $barcode = $barcodeService->generateBarcode('BK1754063915');
    
    return response()->json([
        'barcode' => $barcode,
        'booking_code' => 'BK1754063915'
    ]);
});

// Admin Reviews Routes
Route::prefix('admin/reviews')->name('admin.reviews.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminReviewController::class, 'index'])->name('index');
    Route::get('/content-filter', [App\Http\Controllers\Admin\AdminReviewController::class, 'contentFilterSettings'])->name('content-filter');
    Route::post('/add-sensitive-word', [App\Http\Controllers\Admin\AdminReviewController::class, 'addSensitiveWord'])->name('add-sensitive-word');
    Route::delete('/remove-sensitive-word', [App\Http\Controllers\Admin\AdminReviewController::class, 'removeSensitiveWord'])->name('remove-sensitive-word');
    Route::post('/{review}/recheck', [App\Http\Controllers\Admin\AdminReviewController::class, 'recheckReview'])->name('recheck');
    Route::post('/{review}/force-approve', [App\Http\Controllers\Admin\AdminReviewController::class, 'forceApprove'])->name('force-approve');
    Route::get('/{review}', [App\Http\Controllers\Admin\AdminReviewController::class, 'show'])->name('show');
    Route::patch('/{review}/status', [App\Http\Controllers\Admin\AdminReviewController::class, 'updateStatus'])->name('update-status');
    Route::post('/bulk-status', [App\Http\Controllers\Admin\AdminReviewController::class, 'bulkUpdateStatus'])->name('bulk-status');
    Route::delete('/{review}', [App\Http\Controllers\Admin\AdminReviewController::class, 'destroy'])->name('destroy');
    Route::delete('/bulk-delete', [App\Http\Controllers\Admin\AdminReviewController::class, 'bulkDelete'])->name('bulk-delete');
    Route::get('/recalculate-ratings/all', [App\Http\Controllers\Admin\AdminReviewController::class, 'recalculateAllRatings'])->name('recalculate-all');
    Route::get('/reset-stats/all', [App\Http\Controllers\Admin\AdminReviewController::class, 'resetAllStats'])->name('reset-stats');
});

require __DIR__.'/auth.php';