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
// 無限スクロール
Route::get('/inventory/load/{pageNum}/{storeId}', [InventoryController::class, 'loadInventories']);
// 統合エンドポイント
Route::get('/inventory/data/{storeId}', [InventoryController::class, 'getInventoryData'])->name('inventory.data');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
