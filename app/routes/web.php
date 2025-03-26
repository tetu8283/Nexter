<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ArrivalController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::resource('users', UserController::class)->except(['create', 'index', 'show', 'edit']);
Route::resource('inventories', InventoryController::class)->except(['show']);
Route::resource('books', BookController::class)->only(['store']);
Route::resource('arrivals', ArrivalController::class);



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
