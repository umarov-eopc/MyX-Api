<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['api'])->prefix('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware([VerifyCsrfToken::class]);
    Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware([VerifyCsrfToken::class]);
    Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);


    Route::middleware('auth:api')->group(function () {
        Route::post('/user', [AuthController::class, 'user']);

        Route::get('/posts', [PostController::class, 'index']);
        Route::post('/posts', [PostController::class, 'store']);
        Route::get('/posts/{post}', [PostController::class, 'show']);
    });
});
