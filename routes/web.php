<?php

use App\Http\Controllers\Admin\AdminRoomController;
use App\Http\Controllers\Admin\AdminSeatTypeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\ShowtimeController;
use App\Http\Controllers\Admin\AdminSeatController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Seat routes from HEAD
    Route::get('seats/edit-bulk', [AdminSeatController::class, 'editBulk'])->name('seats.editBulk');
    Route::put('seats/update-bulk', [AdminSeatController::class, 'updateBulk'])->name('seats.bulkUpdate');

    Route::resource('seats', AdminSeatController::class);

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

    Route::resource('rooms', AdminRoomController::class);
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
