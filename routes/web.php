<?php

use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['api'])->prefix('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware([VerifyCsrfToken::class]);
    Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);
});
