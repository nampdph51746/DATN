<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    return view('welcome');
});



Route::prefix('payment_methods')->group(function () {
    Route::get('/', [PaymentMethodController::class, 'index'])->name('payment_methods.index');    // danh sách + tìm kiếm lọc
    Route::get('/{id}', [PaymentMethodController::class, 'show'])->name('payment_methods.show');   // xem chi tiết
    Route::get('/{paymentMethod}/edit-status', [PaymentMethodController::class, 'editStatus'])->name('payment_methods.editStatus');
    Route::put('/{paymentMethod}/update-status', [PaymentMethodController::class, 'updateStatus'])->name('payment_methods.updateStatus');
});

Route::get('bookings', [BookingController::class, 'index'])->name('admin.bookings.index');
Route::get('bookingShow/{id}', [BookingController::class, 'show'])->name('admin.bookings.show');
Route::get('admin/bookings/{booking}/edit-status', [BookingController::class, 'editStatus'])->name('admin.bookings.editStatus');
Route::put('admin/bookings/{booking}/update-status', [BookingController::class, 'updateStatus'])->name('admin.bookings.updateStatus');



Route::get('admin/payments', [PaymentController::class, 'index'])->name('admin.payments.index');
Route::get('admin/payments/{payment}', [PaymentController::class, 'show'])->name('admin.payments.show');
Route::get('admin/payments/{payment}/edit-status', [PaymentController::class, 'editStatus'])->name('admin.payments.editStatus');
Route::put('admin/payments/{payment}/update-status', [PaymentController::class, 'updateStatus'])->name('admin.payments.updateStatus');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::get('test-customer', [CustomerController::class, 'index'])->name('index');
Route::get('test-customer-detail/{id}', [CustomerController::class, 'show'])->name('show');
