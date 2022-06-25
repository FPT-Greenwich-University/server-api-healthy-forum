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
        // Check user like is exits
        Route::get('/posts/{postID}/likes/is-exist', 'checkUserLikePost');
        // The user like the post
        Route::post('/posts/{postID}/likes', 'likeThePost');
        // The user unlike the post
        Route::delete('/posts/{postID}/likes', 'unlikeThePost');
    });

/**
 * Rating the post
 */
Route::controller(PostRatingController::class)
    ->middleware(['auth:sanctum'])
    ->group(function () {
        // The user rating the post
        Route::post('/posts/{postID}/ratings', 'ratingThePost');
        // The user update rating the post
        Route::put('/posts/{postID}/ratings/', 'updateRatingThePost');
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
        Route::get('/{userID}/favorites/posts', 'index')->withoutMiddleware(['auth:sanctum', 'api']);
        // Check is exits the post in favorite list
        Route::get('/{userID}/favorites/posts/{postID}', 'checkUserFollow')->withoutMiddleware('auth:sanctum');
        Route::post('/favorites/posts', 'store'); // store new post to favorite post list
        Route::delete('{userID}/favorites/posts/{postID}', 'destroy');
    });

/**
 * User's favorite doctor
 */
Route::prefix('/users')
    ->controller(DoctorFavoriteController::class)
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/{userID}/favorites/doctors', 'index')->withoutMiddleware(['auth:sanctum', 'api']);
        // Check is exits doctor in favorite list
        Route::get('/{userID}/favorites/doctors/{doctorID}', 'checkUserFollow')->withoutMiddleware('auth:sanctum');
        // add new doctor to favorite list
        Route::post('/favorites/doctors', 'addFavoriteItem');
        // Remove doctor from favorite list
        Route::delete('{userID}/favorites/doctors/{doctorID}', 'removeFavoriteItem');
    });
