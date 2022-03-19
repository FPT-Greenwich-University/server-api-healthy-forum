<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\GoogleAuthController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\VerifyAccountController;
use Illuminate\Support\Facades\Route;

/**
 * Authentication to system
 */
Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->middleware(['auth:sanctum']);
});

/**
 * Login with Google account
 */
Route::post('/login/google', [GoogleAuthController::class, 'login']);

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
    Route::get('/register/doctor-role', 'getListRegisterDoctorRoles')->middleware('role:admin'); //TODO Middle is admin here
    Route::post('/register/doctor-role', 'registerWithRoleDoctor');
    Route::put('/register/doctor-role/{registerUserID}', 'acceptRegisterDoctorRole')->middleware('role:admin'); //TODO:: add middle admin here
});
