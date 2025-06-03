<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SeatController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('seats', SeatController::class);
});

Route::get('test-customer', [CustomerController::class, 'index'])->name('index'); 
Route::get('test-customer-detail/{id}', [CustomerController::class, 'show'])->name('show'); 
