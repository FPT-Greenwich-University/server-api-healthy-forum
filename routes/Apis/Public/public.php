<?php

use App\Http\Controllers\Api\Public\PostController;
use Illuminate\Support\Facades\Route;

Route::controller(PostController::class)->group(function () {
    Route::get('/posts', 'index');
});
