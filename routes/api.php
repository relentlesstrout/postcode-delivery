<?php

use App\Http\Controllers\Api\ShopController;
use Illuminate\Support\Facades\Route;

Route::post('/shops/nearby', [ShopController::class, 'nearby']);
Route::post('/shops/can-deliver', [ShopController::class, 'canDeliver']);
