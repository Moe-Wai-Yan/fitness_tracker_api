<?php
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\MustBeAdminMiddleware;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify', [VerificationController::class, 'verifyCode']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware([AuthMiddleware::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware([MustBeAdminMiddleware::class,AuthMiddleware::class])->group(function(){
    Route::resource('admin/categories',CategoryController::class);
});

