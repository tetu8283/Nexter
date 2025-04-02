<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArrivalController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::resource('users', UserController::class)->except(['create', 'index', 'show', 'edit']);
Route::resource('inventories', InventoryController::class)->except(['show']);
Route::resource('books', BookController::class)->only(['store']);
Route::resource('arrivals', ArrivalController::class);

// 非同期で店舗情報取得用
Route::get('/store-info/{storeId}', [InventoryController::class, 'getStoreInfo']);

Route::get('/inventory/load/{pageNum}/{storeId}', [InventoryController::class, 'loadInventories']);
Route::get('/arrivals/load/{pageNum}/{storeId}', [ArrivalController::class, 'loadArrivals']);

Route::get('/inventory/data/{storeId}', [InventoryController::class, 'getInventoryData'])->name('inventory.data');
Route::get('/arrival/data/{storeId}', [ArrivalController::class, 'getArrivalData'])->name('arrival.data');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
