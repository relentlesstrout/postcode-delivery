<?php

use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShopController::class, 'index']);
Route::post('/', [ShopController::class, 'canDeliver'])->name('shop.can-deliver');
Route::get('/shops/create', [ShopController::class, 'create'])->name('shop.create');
Route::post('/shops/create', [ShopController::class, 'store']);
