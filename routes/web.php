<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('test-customer', [CustomerController::class, 'index'])->name('index'); 
Route::get('test-customer-detail/{id}', [CustomerController::class, 'show'])->name('show'); 
Route::get('countries', [CountryController::class, 'index'])->name('index');
Route::get('countries-add', [CountryController::class, 'create'])->name('create');
Route::get('countries-edit', [CountryController::class, 'edit'])->name('edit');
Route::get('countries/trash', [CountryController::class, 'trash'])->name('countries.trash');
Route::post('countries/{id}/restore', [CountryController::class, 'restore'])->name('countries.restore');
Route::delete('countries/{id}/force-delete', [CountryController::class, 'forceDelete'])->name('countries.forceDelete');
Route::resource('countries', CountryController::class);
Route::get('cities', [CityController::class, 'index'])->name('index');
Route::get('cities-add', [CityController::class, 'create'])->name('create');
Route::get('cities-edit', [CityController::class, 'edit'])->name('edit');
Route::get('cities/trash', [CityController::class, 'trash'])->name('cities.trash');
Route::PATCH('cities/{id}/restore', [CityController::class, 'restore'])->name('cities.restore');
Route::delete('cities/{id}/force-delete', [CityController::class, 'forceDelete'])->name('cities.forceDelete');
Route::resource('cities', CityController::class);
Route::get('cinemas', [CinemaController::class, 'index'])->name('index');
Route::get('cinemas-add', [CinemaController::class, 'create'])->name('create');
Route::get('cinemas-edit', [CinemaController::class, 'edit'])->name('edit');
Route::get('cinemas-detail', [CinemaController::class, 'show'])->name('cinemas.show');
Route::get('cinemas/trash', [CinemaController::class, 'trash'])->name('cinemas.trash');
Route::patch('cinemas/{id}/restore', [CinemaController::class, 'restore'])->name('cinemas.restore');
Route::delete('cinemas/{id}/force-delete', [CinemaController::class, 'forceDelete'])->name('cinemas.forceDelete');
Route::resource('cinemas', CinemaController::class);