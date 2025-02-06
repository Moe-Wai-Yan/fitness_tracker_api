<?php
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify', [VerificationController::class, 'verifyCode']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware([AuthMiddleware::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

