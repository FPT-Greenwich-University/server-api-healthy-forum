<?php

use App\Http\Controllers\Api\Authentication\AuthController;
use App\Http\Controllers\Api\Authentication\GoogleAuthController;
use App\Http\Controllers\Api\Authentication\RegisterController;
use App\Http\Controllers\Api\Authentication\ResetPasswordController;
use App\Http\Controllers\Api\Authentication\VerifyAccountController;
use Illuminate\Support\Facades\Route;

/**
 * Authentication to system
 */
Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->middleware(['auth:sanctum']);
    Route::post('/refresh-token')->middleware(['auth:sanctum']);
});

/**
 * Login with Google account
 */
Route::post('/login/google', [GoogleAuthController::class, 'login']);


/**
 * Change password
 */
Route::controller(\App\Http\Controllers\Api\Authentication\ChangePasswordController::class)
    ->group(function () {
        Route::put("/users/{userId}/passwords", "updatePassword");
    });

/**
 * Reset password
 */
Route::controller(ResetPasswordController::class)->group(function () {
    Route::post('/forgot-password', 'forgotPassword');
    Route::put('/reset-password', 'resetPassword');
});


/**
 * Verify account
 */
Route::controller(VerifyAccountController::class)->group(function () {
    Route::post('/email/verification-notification', 'resendVerifyEmail');
    Route::put('/verify-account', 'verifyEmail');
});

/**
 * Register doctor role
 */
Route::controller(RegisterController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::get('/register/doctor-role', 'getListRegisterDoctorRoles')->middleware('role:admin');
    Route::post('/register/doctor-role', 'registerWithRoleDoctor');
    Route::put('/register/doctor-role/{registeruserId}', 'acceptRegisterDoctorRole')->middleware('role:admin');
});
