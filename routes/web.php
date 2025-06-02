<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('test-customer', [CustomerController::class, 'index'])->name('index'); 
Route::get('test-customer-detail/{id}', [CustomerController::class, 'show'])->name('show'); 
