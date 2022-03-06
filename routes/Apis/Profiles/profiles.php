<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\Api\Users\Profiles\ProfileController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::get('users/profiles', 'show'); // show current user's profile
    Route::put('users/profiles', 'update');
});
