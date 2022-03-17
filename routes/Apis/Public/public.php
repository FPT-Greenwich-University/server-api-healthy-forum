<?php

use App\Http\Controllers\Api\Public\Comments\PublicCommentController;
use App\Http\Controllers\Api\Public\PostLikes\PublicPostLikeController;
use App\Http\Controllers\Api\Public\PostRatings\PublicPostRatingController;
use App\Http\Controllers\Api\Public\Posts\PublicPostController;
use App\Http\Controllers\Api\Public\PublicLocationController;
use Illuminate\Support\Facades\Route;

/**
 * Post routes
 */
Route::controller(PublicPostController::class)->group(function () {
    Route::get('/posts', 'index');
    Route::get('/posts/{postID}', 'show');
});

/**
 * Comment routes
 */
Route::controller(PublicCommentController::class)->group(function () {
    Route::get('/posts/{postID}/comments', 'index');
});


/**
 * Post like routes
 */
Route::controller(PublicPostLikeController::class)->group(function () {
    Route::get('/posts/{postID}/likes', 'index');
});

/**
 * Post rating routes
 */
Route::controller(PublicPostRatingController::class)->group(function () {
    Route::get('/posts/{postID}/ratings', 'getAveragePostRating');
});

/**
 * Location routes
 */
Route::controller(PublicLocationController::class)->group(function () {
    Route::get('/public/cities', 'getCities');
    Route::get('/public/cities/{cityID}/districts', 'getDistricts');
    Route::get('/public/districts/{districtsID}/wards', 'getWards');
});
