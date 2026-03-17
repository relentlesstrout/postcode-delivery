<?php


use App\Http\Controllers\Api\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/shops/nearby', [ShopController::class, 'nearby']);
Route::get('/shops/can-deliver', [ShopController::class, 'canDeliver']);
