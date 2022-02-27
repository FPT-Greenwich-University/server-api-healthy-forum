<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', [\App\Http\Controllers\Api\Auth\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\Api\Auth\AuthController::class, 'register']);
Route::post('/logout', [\App\Http\Controllers\Api\Auth\AuthController::class, 'logout'])->middleware(['auth:sanctum']);

Route::post('/login/google', [\App\Http\Controllers\Api\Auth\GoogleAuthController::class, 'login']);

// Reset password
Route::post('/forgot-password', [\App\Http\Controllers\Api\Auth\ResetPasswordController::class, 'forgotPassword']);
Route::put('/reset-password', [\App\Http\Controllers\Api\Auth\ResetPasswordController::class, 'resetPassword']);
