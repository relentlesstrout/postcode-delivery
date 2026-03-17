<?php

use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShopController::class, 'index']);
Route::get('/shops/create', [ShopController::class, 'create']);
Route::post('/shops/create', [ShopController::class, 'store']);
