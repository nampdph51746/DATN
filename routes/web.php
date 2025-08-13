<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
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

// ========== CLIENT ROUTES ==========
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

Route::post('/apply-promotion', [App\Http\Controllers\Client\HomeController::class, 'applyDiscountCode'])->name('client.applyPromotion');
Route::post('/apply-points', [App\Http\Controllers\Client\HomeController::class, 'applyPoints'])->name('client.applyPoints');
Route::get('/available-promotions', [App\Http\Controllers\Client\HomeController::class, 'getAvailablePromotions'])->name('client.getAvailablePromotions');
Route::get('/user-rank', [App\Http\Controllers\Client\HomeController::class, 'getUserRank'])->name('client.getUserRank');
Route::get('/user-points', [App\Http\Controllers\Client\HomeController::class, 'getUserPoints'])->name('client.getUserPoints');
Route::get('/user-point-history', [App\Http\Controllers\Client\HomeController::class, 'getUserPointHistory'])->name('client.getUserPointHistory');

// Client Bookings
Route::middleware('auth')->group(function () {
    Route::get('my-bookings', [BookingController::class, 'myBookings'])->name('client.bookings.index');
    Route::get('my-bookings/show/{id}', [BookingController::class, 'myBookingsShow'])->name('client.bookings.show');
});

// Profile routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'profile'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ========== ADMIN ROUTES ==========
Route::middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ===== BOOKINGS ROUTES =====
    // ===== TICKETS ROUTES =====
    Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/create', [TicketController::class, 'create'])->name('tickets.create'); // <-- Thêm dòng này
    Route::post('tickets', [TicketController::class, 'store'])->name('tickets.store'); // Nếu có chức năng lưu
        Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::get('bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
        Route::put('bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
        Route::get('bookings/{booking}/print', [BookingController::class, 'print'])->name('bookings.print');

        // ===== SEAT ROUTES =====
        Route::post('seats/edit-bulk', [AdminSeatController::class, 'editBulk'])->name('seats.edit-bulk');
        Route::post('seats/update-bulk', [AdminSeatController::class, 'updateBulk'])->name('seats.update-bulk');
        Route::delete('seats/bulk-delete', [AdminSeatController::class, 'deleteBulk'])->name('seats.deleteBulk');
        Route::post('seats/store-new', [AdminSeatController::class, 'storeNew'])->name('seats.store-new');
        Route::post('seats/add-single', [AdminSeatController::class, 'addSingleSeat'])->name('seats.add-single');
        Route::post('seats/import', [AdminSeatController::class, 'importExcel'])->name('seats.import');
        Route::resource('seats', AdminSeatController::class);

        // ===== PRODUCT CATEGORIES =====
        Route::get('product-categories/trash', [AdminProductCategoriesController::class, 'trash'])->name('product-categories.trash');
        Route::post('product-categories/{id}/restore', [AdminProductCategoriesController::class, 'restore'])->name('product-categories.restore');
        Route::delete('product-categories/{id}/force-delete', [AdminProductCategoriesController::class, 'forceDelete'])->name('product-categories.forceDelete');
        Route::resource('product-categories', AdminProductCategoriesController::class);

        // ===== PRODUCT MANAGEMENT =====
        Route::resource('attributes', AdminAttributeController::class);
        Route::resource('attribute-values', AdminAttributeValueController::class);
        Route::resource('product-variants', AdminProductVariantController::class);
        Route::get('products/{id}/variants', [AdminProductController::class, 'getVariants'])->name('products.variants');
        Route::resource('products', AdminProductController::class);

        // ===== ROOM TYPES =====
        Route::delete('room-types/{id}/deactivate', [RoomTypeController::class, 'deactivate'])->name('room-types.deactivate');
        Route::resource('room-types', RoomTypeController::class)->except(['destroy']);

        // ===== SHOWTIMES =====
        Route::delete('showtimes/{id}/deactivate', [ShowtimeController::class, 'deactivate'])->name('showtimes.deactivate');
        Route::post('showtimes', [ShowtimeController::class, 'storeAuto'])->name('showtimes.storeAuto');
        Route::post('showtimes/update-statuses', [ShowtimeController::class, 'updateStatuses'])->name('showtimes.updateStatuses');
        Route::post('showtimes/{id}/update-status', [ShowtimeController::class, 'updateSingleStatus'])->name('showtimes.updateSingleStatus');
        Route::post('showtimes/{id}/update-status-manual', [ShowtimeController::class, 'updateStatus'])->name('showtimes.updateStatus');
        Route::resource('showtimes', ShowtimeController::class)->except(['destroy']);

        // ===== MOVIES =====
        Route::delete('movies/bulk-delete', [AdminMovieController::class, 'bulkDelete'])->name('movies.bulkDelete');
        Route::prefix('movies')->name('movies.')->group(function () {
            Route::get('/', [AdminMovieController::class, 'index'])->name('index');
            Route::get('/create', [AdminMovieController::class, 'create'])->name('create');
            Route::post('/', [AdminMovieController::class, 'store'])->name('store');
            Route::get('/{id}', [AdminMovieController::class, 'show'])->name('show')->where('id', '[0-9]+');
            Route::get('/{id}/edit', [AdminMovieController::class, 'edit'])->name('edit')->where('id', '[0-9]+');
            Route::put('/{id}', [AdminMovieController::class, 'update'])->name('update')->where('id', '[0-9]+');
            Route::delete('/{id}', [AdminMovieController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
        });

        // ===== DIRECTORS & ACTORS =====
        Route::delete('directors/bulk-delete', [App\Http\Controllers\Admin\AdminDirectorController::class, 'bulkDelete'])->name('directors.bulkDelete');
        Route::resource('directors', App\Http\Controllers\Admin\AdminDirectorController::class);
        Route::resource('actors', App\Http\Controllers\Admin\AdminActorController::class);

        // ===== GENRES =====
        Route::delete('genres/bulk-delete', [App\Http\Controllers\Admin\GenreController::class, 'bulkDelete'])->name('genres.bulkDelete');
        Route::resource('genres', App\Http\Controllers\Admin\GenreController::class);

        // ===== ROOMS =====
        Route::patch('rooms/{id}/update-percentages', [AdminRoomController::class, 'updateSeatPercentages'])->name('rooms.updatePercentages');
        Route::post('rooms/{room}/update-seat-percentages', [AdminRoomController::class, 'updateSeatPercentages'])->name('rooms.update-seat-percentages');
        Route::post('rooms/{room}/update-capacity', [AdminRoomController::class, 'updateCapacity'])->name('rooms.update-capacity');
        Route::resource('rooms', AdminRoomController::class);

        // ===== LOCATIONS =====
        Route::get('countries/trash', [CountryController::class, 'trash'])->name('countries.trash');
        Route::post('countries/{id}/restore', [CountryController::class, 'restore'])->name('countries.restore');
        Route::delete('countries/{id}/force-delete', [CountryController::class, 'forceDelete'])->name('countries.forceDelete');
        Route::resource('countries', CountryController::class);

        // ===== CITIES ROUTES (Cập nhật) =====
        Route::prefix('cities')->name('cities.')->group(function () {
            // Main CRUD routes
            Route::get('/', [CityController::class, 'index'])->name('index');
            Route::get('/create', [CityController::class, 'create'])->name('create');
            Route::post('/', [CityController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [CityController::class, 'edit'])->name('edit')->where('id', '[0-9]+');
            Route::put('/{id}', [CityController::class, 'update'])->name('update')->where('id', '[0-9]+');
            Route::delete('/{id}', [CityController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
            
            // Bulk actions
            Route::delete('/bulk-delete', [CityController::class, 'bulkDelete'])->name('bulkDelete');
            Route::post('/bulk-restore', [CityController::class, 'bulkRestore'])->name('bulkRestore');
            Route::delete('/bulk-force-delete', [CityController::class, 'bulkForceDelete'])->name('bulkForceDelete');
            
            // Trash routes
            Route::get('/trash', [CityController::class, 'trash'])->name('trash');
            Route::patch('/trash/{id}/restore', [CityController::class, 'restore'])->name('restore')->where('id', '[0-9]+');
            Route::delete('/trash/{id}/force-delete', [CityController::class, 'forceDelete'])->name('forceDelete')->where('id', '[0-9]+');
        });

        Route::get('cinemas/trash', [CinemaController::class, 'trash'])->name('cinemas.trash');
        Route::patch('cinemas/{id}/restore', [CinemaController::class, 'restore'])->name('cinemas.restore');
        Route::delete('cinemas/{id}/force-delete', [CinemaController::class, 'forceDelete'])->name('cinemas.forceDelete');
        Route::resource('cinemas', CinemaController::class);

        // ===== PAYMENTS =====
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
        Route::get('payments/{payment}/edit-status', [PaymentController::class, 'editStatus'])->name('payments.editStatus');
        Route::put('payments/{payment}/update-status', [PaymentController::class, 'updateStatus'])->name('payments.updateStatus');

        // ===== TICKETS =====
        Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');
        Route::get('tickets/create', [TicketController::class, 'create'])->name('tickets.create'); // <-- Thêm dòng này
        Route::post('tickets', [TicketController::class, 'store'])->name('tickets.store'); // Nếu có chức năng lưu
        Route::get('tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');

        // ===== PROMOTIONS =====
        Route::get('promotions/trashed', [PromotionController::class, 'trashed'])->name('promotions.trashed');
        Route::post('promotions/restore/{id}', [PromotionController::class, 'restore'])->name('promotions.restore');
        Route::delete('promotions/force-delete/{id}', [PromotionController::class, 'forceDelete'])->name('promotions.forceDelete');
        Route::resource('promotions', PromotionController::class);

        // ===== POINTS =====
        Route::resource('points', PointController::class)->only(['index', 'show']);
        Route::resource('point_history', PointHistoryController::class)->only(['index', 'show']);
        Route::patch('point_history/toggle/{id}', [PointHistoryController::class, 'toggle'])->name('point_history.toggle');

        // ===== COMBOS =====
        Route::post('combos/check-duplicate', [ComboController::class, 'checkDuplicate'])->name('combos.checkDuplicate');
        Route::resource('combos', ComboController::class)->names('combos');

        // ===== QR CODE SCANNING =====
        Route::prefix('api/qr')->group(function () {
            Route::post('/scan', [QrCodeController::class, 'scanQr'])->name('qr.scan');
            Route::post('/check-status', [QrCodeController::class, 'checkTicketStatus'])->name('qr.check');
            Route::post('/scan-ticket', [QrCodeController::class, 'scanTicketByCode'])->name('qr.scanTicket');
        });
        Route::get('/qr-scanner', function () {
            return view('admin.Qrcode-scanner');
        })->name('qr.scanner');

        // ===== REVIEWS =====
        Route::prefix('reviews')->name('reviews.')->group(function () {
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
    });
});

// ===== SEAT TYPES (Outside admin prefix) =====
Route::prefix('admin/seat-type')->name('seat-type.')->middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::get('/', [AdminSeatTypeController::class, 'index'])->name('index');
    Route::get('create', [AdminSeatTypeController::class, 'create'])->name('create');
    Route::post('store', [AdminSeatTypeController::class, 'store'])->name('store');
    Route::get('edit/{id}', [AdminSeatTypeController::class, 'edit'])->name('edit');
    Route::put('update/{id}', [AdminSeatTypeController::class, 'update'])->name('update');
    Route::delete('{id}', [AdminSeatTypeController::class, 'destroy'])->name('destroy');
    Route::get('trash', [AdminSeatTypeController::class, 'trash'])->name('trash');
    Route::patch('{id}/restore', [AdminSeatTypeController::class, 'restore'])->name('restore');
    Route::delete('{id}/force-delete', [AdminSeatTypeController::class, 'forceDelete'])->name('force-delete');
});

// ===== AGE LIMITS =====
Route::prefix('admin/age-limits')->name('admin.age_limits.')->middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::get('/', [AgeLimitController::class, 'index'])->name('index');
    Route::get('/create', [AgeLimitController::class, 'create'])->name('create');
    Route::post('/', [AgeLimitController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [AgeLimitController::class, 'edit'])->name('edit');
    Route::put('/{id}', [AgeLimitController::class, 'update'])->name('update');
    Route::delete('/{id}', [AgeLimitController::class, 'destroy'])->name('destroy');
    Route::post('/bulk-delete', [AgeLimitController::class, 'bulkDelete'])->name('bulkDelete');
});

// ===== USERS =====
Route::resource('admin/users', UserController::class)->middleware(['auth', 'role:admin,staff']);

// ===== CUSTOMER RANKS =====
Route::prefix('admin/customers-rank')->name('customers-rank.')->middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::get('deleted', [CustomerRankController::class, 'deleted'])->name('deleted');
    Route::get('deleted/{id}', [CustomerRankController::class, 'deletedShow'])->name('deleted.show');
    Route::delete('{customerRank}/soft-delete', [CustomerRankController::class, 'softDelete'])->name('softDelete');
    Route::get('deleted/detail/{id}', [CustomerRankController::class, 'deletedShow'])->name('deleted-detail');
    Route::post('deleted/{id}/restore', [CustomerRankController::class, 'restore'])->name('restore');
    Route::delete('deleted/{id}/force-delete', [CustomerRankController::class, 'forceDelete'])->name('forceDelete');
});
Route::resource('admin/customers-rank', CustomerRankController::class)->middleware(['auth', 'role:admin,staff']);

// ===== ROLES =====
Route::prefix('admin/roles')->name('roles.')->middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::get('deleted', [RoleController::class, 'deleted'])->name('deleted');
    Route::get('deleted/{id}', [RoleController::class, 'deletedShow'])->name('deleted.show');
    Route::get('deleted/detail/{id}', [RoleController::class, 'deletedShow'])->name('deleted-detail');
    Route::post('deleted/{id}/restore', [RoleController::class, 'restore'])->name('restore');
    Route::delete('deleted/{id}/force-delete', [RoleController::class, 'forceDelete'])->name('forceDelete');
    Route::delete('{role}/soft-delete', [RoleController::class, 'softDelete'])->name('softDelete');
});
Route::resource('admin/roles', RoleController::class)->middleware(['auth', 'role:admin,staff']);

// ===== PAYMENT METHODS =====
Route::prefix('admin/payment_methods')->middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::get('/', [PaymentMethodController::class, 'index'])->name('payment_methods.index');
    Route::get('/{id}', [PaymentMethodController::class, 'show'])->name('payment_methods.show');
    Route::get('/{paymentMethod}/edit-status', [PaymentMethodController::class, 'editStatus'])->name('payment_methods.editStatus');
    Route::put('/{paymentMethod}/update-status', [PaymentMethodController::class, 'updateStatus'])->name('payment_methods.updateStatus');
});

// ===== CUSTOMER RANK PROMOTIONS =====
Route::prefix('admin/customer_rank_promotions')->middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::get('/', [CustomerRankPromotionController::class, 'index'])->name('customer_rank_promotions.index');
    Route::get('/create', [CustomerRankPromotionController::class, 'create'])->name('customer_rank_promotions.create');
    Route::post('/', [CustomerRankPromotionController::class, 'store'])->name('customer_rank_promotions.store');
    Route::get('/{customer_rank_id}/{promotion_id}', [CustomerRankPromotionController::class, 'show'])->name('customer_rank_promotions.show');
    Route::get('/{customer_rank_id}/{promotion_id}/edit', [CustomerRankPromotionController::class, 'edit'])->name('customer_rank_promotions.edit');
    Route::put('/{customer_rank_id}/{promotion_id}', [CustomerRankPromotionController::class, 'update'])->name('customer_rank_promotions.update');
    Route::delete('/{customer_rank_id}/{promotion_id}', [CustomerRankPromotionController::class, 'destroy'])->name('customer_rank_promotions.destroy');
});

// ===== COUNTRIES ===== 
Route::prefix('admin/countries')->name('admin.countries.')->middleware(['auth', 'role:admin,staff'])->group(function () {
    // Main CRUD routes
    Route::get('/', [CountryController::class, 'index'])->name('index');
    Route::get('/create', [CountryController::class, 'create'])->name('create');
    Route::post('/', [CountryController::class, 'store'])->name('store');
    Route::get('/{id}', [CountryController::class, 'show'])->name('show')->where('id', '[0-9]+');
    Route::get('/{id}/edit', [CountryController::class, 'edit'])->name('edit')->where('id', '[0-9]+');
    Route::put('/{id}', [CountryController::class, 'update'])->name('update')->where('id', '[0-9]+');
    Route::delete('/{id}', [CountryController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
    
    // Bulk actions
    Route::delete('/bulk-delete', [CountryController::class, 'bulkDelete'])->name('bulkDelete');
    Route::post('/bulk-restore', [CountryController::class, 'bulkRestore'])->name('bulkRestore');
    Route::delete('/bulk-force-delete', [CountryController::class, 'bulkForceDelete'])->name('bulkForceDelete');
    
    // Trash routes
    Route::get('/trash', [CountryController::class, 'trash'])->name('trash');
    Route::post('/trash/{id}/restore', [CountryController::class, 'restore'])->name('restore')->where('id', '[0-9]+');
    Route::delete('/trash/{id}/force-delete', [CountryController::class, 'forceDelete'])->name('forceDelete')->where('id', '[0-9]+');
});

// ===== TEST ROUTES =====
Route::get('/test-barcode-api', function () {
    $barcodeService = new \App\Services\BarcodeService();
    $barcode = $barcodeService->generateBarcode('BK1754063915');
    
    return response()->json([
        'barcode' => $barcode,
        'booking_code' => 'BK1754063915'
    ]);
});

// ===== API ROUTES =====
Route::get('/api/genres', function() {
    try {
        if (!Schema::hasTable('genres')) {
            return response()->json([
                ['id' => 1, 'name' => 'Hành động'],
                ['id' => 2, 'name' => 'Tình cảm'],
                ['id' => 3, 'name' => 'Hài hước'],
                ['id' => 4, 'name' => 'Kinh dị'],
                ['id' => 5, 'name' => 'Khoa học viễn tưởng'],
                ['id' => 6, 'name' => 'Phiêu lưu'],
            ]);
        }
        
        $genres = DB::table('genres')->select('id', 'name')->get();
        return response()->json($genres);
    } catch (\Exception $e) {
        \Log::error('Genres API error: ' . $e->getMessage());
        return response()->json([
            ['id' => 1, 'name' => 'Hành động'],
            ['id' => 2, 'name' => 'Tình cảm'],
            ['id' => 3, 'name' => 'Hài hước'],
            ['id' => 4, 'name' => 'Kinh dị'],
            ['id' => 5, 'name' => 'Khoa học viễn tưởng'],
            ['id' => 6, 'name' => 'Phiêu lưu'],
        ]);
    }
});

Route::get('/api/search-movies', function(Illuminate\Http\Request $request) {
    try {
        $query = $request->input('search', '');
        $genreId = $request->input('genre', '');
        
        \Log::info('Search request', ['query' => $query, 'genreId' => $genreId]);
        
        if (!Schema::hasTable('movies')) {
            return response()->json(['movies' => []]);
        }
        
        $moviesQuery = DB::table('movies')
            ->select('id', 'name', 'poster_url', 'image_path', 'duration_minutes', 'status');
        
        if (!empty($query)) {
            $moviesQuery->where(function($q) use ($query) {
                $q->where('name', 'LIKE', '%' . $query . '%')
                  ->orWhere('name', 'LIKE', '%' . strtolower($query) . '%')
                  ->orWhere('name', 'LIKE', '%' . strtoupper($query) . '%')
                  ->orWhere('name', 'LIKE', '%' . ucwords(strtolower($query)) . '%');
            });
        }
        
        if (!empty($genreId) && Schema::hasTable('movie_genres')) {
            $movieIds = DB::table('movie_genres')
                ->where('genre_id', $genreId)
                ->pluck('movie_id')
                ->toArray();
            
            if (!empty($movieIds)) {
                $moviesQuery->whereIn('id', $movieIds);
            }
        }
        
        $movies = $moviesQuery
            ->where('status', '!=', 'ended')
            ->orderBy('name', 'asc')
            ->limit(15)
            ->get();
        
        $moviesWithGenres = $movies->map(function($movie) {
            $movie->image = $movie->poster_url ?: $movie->image_path;
            $movie->genres = [];
            
            if (Schema::hasTable('movie_genres') && Schema::hasTable('genres')) {
                try {
                    $genreIds = DB::table('movie_genres')
                        ->where('movie_id', $movie->id)
                        ->pluck('genre_id')
                        ->toArray();
                    
                    if (!empty($genreIds)) {
                        $movie->genres = DB::table('genres')
                            ->whereIn('id', $genreIds)
                            ->select('id', 'name')
                            ->get()
                            ->toArray();
                    }
                } catch (\Exception $e) {
                    \Log::warning('Error loading genres for movie ' . $movie->id . ': ' . $e->getMessage());
                }
            }
            
            unset($movie->poster_url, $movie->image_path);
            return $movie;
        });
        
        \Log::info('Search results', ['count' => $moviesWithGenres->count()]);
        return response()->json(['movies' => $moviesWithGenres]);
        
    } catch (\Exception $e) {
        \Log::error('Search API error: ' . $e->getMessage());
        \Log::error('Search API stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'error' => true,
            'message' => 'Lỗi tìm kiếm: ' . $e->getMessage(),
            'movies' => []
        ], 200);
    }
});

Route::get('/api/showing-movies', function() {
    try {
        if (!Schema::hasTable('movies')) {
            return response()->json(['movies' => []]);
        }
        
        $movies = DB::table('movies')
            ->select('id', 'name', 'poster_url', 'image_path', 'duration_minutes', 'status', 'release_date')
            ->where('status', 'showing')
            ->orderBy('release_date', 'desc')
            ->limit(8)
            ->get();
        
        $moviesWithGenres = $movies->map(function($movie) {
            $movie->image = $movie->poster_url ?: $movie->image_path;
            $movie->genres = [];
            
            if (Schema::hasTable('movie_genres') && Schema::hasTable('genres')) {
                try {
                    $genreIds = DB::table('movie_genres')
                        ->where('movie_id', $movie->id)
                        ->pluck('genre_id')
                        ->toArray();
                    
                    if (!empty($genreIds)) {
                        $movie->genres = DB::table('genres')
                            ->whereIn('id', $genreIds)
                            ->select('id', 'name')
                            ->get()
                            ->toArray();
                    }
                } catch (\Exception $e) {
                    \Log::warning('Error loading genres for movie ' . $movie->id . ': ' . $e->getMessage());
                }
            }
            
            unset($movie->poster_url, $movie->image_path);
            return $movie;
        });
        
        return response()->json(['movies' => $moviesWithGenres]);
        
    } catch (\Exception $e) {
        \Log::error('Showing movies API error: ' . $e->getMessage());
        return response()->json(['movies' => []], 200);
    }
});

Route::get('admin/movies/count-duplicate-name', [\App\Http\Controllers\Admin\MovieController::class, 'countDuplicateName'])
    ->name('admin.movies.count-duplicate-name');

require __DIR__.'/auth.php';