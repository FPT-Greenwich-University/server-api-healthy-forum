<?php

use App\Http\Controllers\Api\Users\PostLikes\PostLikeController;
use Illuminate\Support\Facades\Route;

Route::controller(PostLikeController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::post('/posts/{postID}/likes', 'likeThePost'); // The user like the post
    Route::delete('/posts/{postID}/likes', 'unlikeThePost'); // The user unlike the post
});
