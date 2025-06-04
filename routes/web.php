<?php

use App\Http\Controllers\Admin\AdminRoomController;
use App\Http\Controllers\Admin\AdminSeatTypeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\AdminSeatController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('seats', AdminSeatController::class);
    Route::resource('rooms', AdminRoomController::class);
});

Route::get('test-customer', [CustomerController::class, 'index'])->name('index'); 
Route::get('test-customer-detail/{id}', [CustomerController::class, 'show'])->name('show'); 
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