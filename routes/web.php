<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\CustomerRankPromotionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('test-customer', [CustomerController::class, 'index'])->name('index');
Route::get('test-customer-detail/{id}', [CustomerController::class, 'show'])->name('show');

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