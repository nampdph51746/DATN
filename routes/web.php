<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SeatController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\ShowtimeController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('seats/edit-bulk', [SeatController::class, 'editBulk'])->name('seats.editBulk');
    Route::put('seats/update-bulk', [SeatController::class, 'updateBulk'])->name('seats.bulkUpdate');

    Route::resource('seats', SeatController::class);

    //room-types
    Route::delete('room-types/{id}/deactivate', [RoomTypeController::class, 'deactivate'])->name('room-types.deactivate');
    Route::resource('room-types', RoomTypeController::class)->except(['destroy']);

    //showtimes
    Route::delete('showtimes/{id}/deactivate', [ShowtimeController::class, 'deactivate'])->name('showtimes.deactivate');
    Route::resource('showtimes', ShowtimeController::class)->except(['destroy']);

    //movies
    Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
    Route::get('/movies/create', [MovieController::class, 'create'])->name('movies.create');
    Route::post('/movies', [MovieController::class, 'store'])->name('movies.store');
    Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');
    Route::get('/movies/{id}/edit', [MovieController::class, 'edit'])->name('movies.edit');
    Route::put('/movies/{id}', [MovieController::class, 'update'])->name('movies.update');
    Route::delete('/movies/{id}', [MovieController::class, 'destroy'])->name('movies.destroy');

    //tạo suất chiếu tự động
    Route::post('/showtimes', [ShowtimeController::class, 'storeAuto'])->name('showtimes.storeAuto');
});



