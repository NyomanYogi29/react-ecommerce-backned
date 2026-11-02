<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        // Product's endpoints
        Route::controller(ProductController::class)->group(function () {
            Route::get('/product', 'getAll');
            Route::get('/product/{product}', 'get');
            Route::middleware('seller')->group(function () {
                Route::post('/product', 'create');
                Route::match(['patch', 'put', 'post'], '/product/{product}', 'update');
                Route::delete('/product/{product}', 'delete');
            });
        });

        Route::controller(ReviewsController::class)->group(function () {
            Route::post('/product/{product}/review', 'create');
        });
    });
});
