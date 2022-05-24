<?php

use App\Http\Controllers\Api\Users\Doctors\DoctorController;
use App\Http\Controllers\Api\Users\Favorites\DoctorFavoriteController;
use App\Http\Controllers\Api\Users\Favorites\PostFavoriteController;
use App\Http\Controllers\Api\Users\Post\PostController;
use App\Http\Controllers\Api\Users\PostComments\PostCommentController;
use App\Http\Controllers\Api\Users\PostLikes\PostLikeController;
use App\Http\Controllers\Api\Users\PostRatings\PostRatingController;
use Illuminate\Support\Facades\Route;

/**
 *  Doctor CRUD post
 */
Route::controller(PostController::class)
    ->middleware(['auth:sanctum', 'role:doctor', 'has.permission:create a post'])
    ->group(function () {
        Route::post('/posts', 'createPost');
    });

Route::controller(DoctorController::class)
    ->middleware(['auth:sanctum', 'role:doctor'])
    ->group(function () {
        Route::get('/users/{userID}/posts/{postID}', 'getDetailPost');
        Route::post('/users/{userID}/posts/{postID}', 'update');
    });

/**
 * Admin or doctor delete post
 */
Route::delete('/posts/{postID}', [PostController::class, 'deletePost'])
    ->middleware(['auth:sanctum', 'has.any.roles:admin,doctor']);

/**
 * Like or unlike post
 */
Route::controller(PostLikeController::class)
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/posts/{postID}/likes/is-exist', 'checkUserLikePost'); // Check user like is exits
        Route::post('/posts/{postID}/likes', 'likeThePost'); // The user like the post
        Route::delete('/posts/{postID}/likes', 'unlikeThePost'); // The user unlike the post
    });

/**
 * Rating the post
 */
Route::controller(PostRatingController::class)
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::post('/posts/{postID}/ratings', 'ratingThePost'); // The user rating the post
        Route::put('/posts/{postID}/ratings/', 'updateRatingThePost'); // The user update rating the post
    });

/**
 * Comment the post
 */
Route::controller(PostCommentController::class)
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::post('/posts/{postID}/comments', 'storePostComment');
        Route::post('/posts/{postID}/comments/{commentID}/reply', 'replyPostComment');
    });

/**
 * User's favorite post
 */
Route::prefix('/users')
    ->controller(PostFavoriteController::class)
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/favorites/posts', 'index');
        Route::post('/favorites/posts', 'store'); // store new post to favorite post list
        Route::delete('/favorites/posts/{favoriteID}', 'destroy');
    });

/**
 * User's favorite doctor
 */
Route::prefix('/users')
    ->controller(DoctorFavoriteController::class)
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/favorites/doctors', 'index');
        Route::post('/favorites/doctors', 'store'); // store new post to favorite post list
        Route::delete('/favorites/doctors/{favoriteID}', 'destroy');
    });
