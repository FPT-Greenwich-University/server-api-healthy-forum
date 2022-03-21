<?php

use App\Http\Controllers\Api\Users\PostComments\PostCommentController;
use App\Http\Controllers\Api\Users\PostLikes\PostLikeController;
use App\Http\Controllers\Api\Users\PostRatings\PostRatingController;
use Illuminate\Support\Facades\Route;

/**
 * Like or unlike post
 */
Route::controller(PostLikeController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::post('/posts/{postID}/likes', 'likeThePost'); // The user like the post
    Route::delete('/posts/{postID}/likes', 'unlikeThePost'); // The user unlike the post
});

/**
 * Rating the post
 */
Route::controller(PostRatingController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::post('/posts/{postID}/ratings', 'ratingThePost'); // The user rating the post
    Route::put('/posts/{postID}/ratings/', 'updateRatingThePost'); // The user update rating the post
});

/**
 * Comment the post
 */
Route::controller(PostCommentController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::post('/posts/{postID}/comments', 'storePostComment');
    Route::post('/posts/{postID}/child-comments/', 'storeChildPostComment');
});
