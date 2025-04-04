<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArrivalController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
    //     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // });

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('users', UserController::class)->except(['create', 'index', 'show', 'edit']);
    Route::resource('inventories', InventoryController::class)->except(['show', 'edit', 'update', 'destroy']);
    Route::resource('books', BookController::class)->only(['store'])->except(['index', 'show', 'edit', 'update', 'destroy']);
    Route::resource('arrivals', ArrivalController::class)->except(['show', 'edit', 'update']);

    // 非同期で店舗情報取得用
    Route::get('/store-info/{store}', [InventoryController::class, 'getStoreInfo']);

    // 無限スクロール用
    Route::get('/inventory/load/{pageNum}/{store}', [InventoryController::class, 'loadInventories']);
    Route::get('/arrivals/load/{pageNum}/{store}', [ArrivalController::class, 'loadArrivals']);

    // 在庫、入荷情報取得用
    Route::get('/inventory/data/{store}', [InventoryController::class, 'getInventoryData'])->name('inventory.data');
    Route::get('/arrival/data/{store}', [ArrivalController::class, 'getArrivalData'])->name('arrival.data');

    // 入荷完了用
    Route::post('/arrivals/update_flag', [ArrivalController::class, 'updateSingleArrivalFlag'])->name('arrivals.update_flag');
});

require __DIR__.'/auth.php';
