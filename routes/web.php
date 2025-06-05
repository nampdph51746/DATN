<?php

use App\Http\Controllers\Admin\CustomerRankController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('test-customer', [CustomerController::class, 'index'])->name('index'); 
Route::get('test-customer-detail/{id}', [CustomerController::class, 'show'])->name('show'); 

Route::resource('users', UserController::class);


// Customer Ranks Routes
Route::prefix('customers-rank')->name('customers-rank.')->group(function () {
    Route::get('deleted', [CustomerRankController::class, 'deleted'])->name('deleted');
    Route::get('deleted/{id}', [CustomerRankController::class, 'deletedShow'])->name('deleted.show');
    Route::delete('{customerRank}/soft-delete', [CustomerRankController::class, 'softDelete'])->name('softDelete');
    Route::get('deleted/detail/{id}', [CustomerRankController::class, 'deletedShow'])->name('deleted-detail');
    Route::post('deleted/{id}/restore', [CustomerRankController::class, 'restore'])->name('restore');
    Route::delete('deleted/{id}/force-delete', [CustomerRankController::class, 'forceDelete'])->name('forceDelete');    
});
Route::resource('customers-rank', CustomerRankController::class);


// Roles Routes
Route::prefix('roles')->name('roles.')->group(function () {
    Route::get('deleted', [RoleController::class, 'deleted'])->name('deleted');
    Route::get('deleted/{id}', [RoleController::class, 'deletedShow'])->name('deleted.show');
    Route::get('deleted/detail/{id}', [RoleController::class, 'deletedShow'])->name('deleted-detail');
    Route::post('deleted/{id}/restore', [RoleController::class, 'restore'])->name('restore');
    Route::delete('deleted/{id}/force-delete', [RoleController::class, 'forceDelete'])->name('forceDelete');
    Route::delete('{role}/soft-delete', [RoleController::class, 'softDelete'])->name('softDelete');
});
Route::resource('roles', RoleController::class);
