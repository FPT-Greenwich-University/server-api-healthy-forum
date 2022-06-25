<?php

use App\Http\Controllers\Api\Users\Profiles\ProfileController;
use Illuminate\Support\Facades\Route;

Route::controller(ProfileController::class)->group(function () {
    Route::get('users/{userID}/profiles', 'show'); // show current user's profile
    Route::put('users/{userID}/profiles', 'update')->middleware(['auth:sanctum']);
});

