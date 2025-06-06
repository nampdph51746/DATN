<?php

use App\Http\Controllers\Admin\AdminMovieController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Admin\AgeLimitController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [App\Http\Controllers\Admin\AdminMovieController::class, 'index']);
Route::delete('admin/movies/bulk-delete', [AdminMovieController::class, 'bulkDelete'])->name('admin.movies.bulkDelete');
Route::delete('admin/genres/bulk-delete', [\App\Http\Controllers\Admin\GenreController::class, 'bulkDelete'])->name('admin.genres.bulkDelete');

Route::prefix('admin')->name('admin.')->group(function () {
   Route::resource('movies', App\Http\Controllers\Admin\AdminMovieController::class);
   Route::resource('genres', App\Http\Controllers\Admin\GenreController::class);
});

Route::get('test-customer', [CustomerController::class, 'index'])->name('customer.index'); 
Route::get('test-customer-detail/{id}', [CustomerController::class, 'show'])->name('customer.show');

Route::prefix('admin/age-limits')->name('admin.age_limits.')->group(function () {
    Route::get('/', [AgeLimitController::class, 'index'])->name('index');
    Route::get('/create', [AgeLimitController::class, 'create'])->name('create');
    Route::post('/', [AgeLimitController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [AgeLimitController::class, 'edit'])->name('edit');
    Route::put('/{id}', [AgeLimitController::class, 'update'])->name('update');
    Route::delete('/{id}', [AgeLimitController::class, 'destroy'])->name('destroy');
});
Route::get('admin/age-limits', [\App\Http\Controllers\Admin\AgeLimitController::class, 'index'])->name('admin.age_limits.index');
Route::delete('admin/age-limits/bulk-delete', [\App\Http\Controllers\Admin\AgeLimitController::class, 'bulkDelete'])->name('admin.age_limits.bulkDelete');
