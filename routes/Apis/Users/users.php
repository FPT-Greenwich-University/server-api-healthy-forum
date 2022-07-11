<?php

use App\Http\Controllers\Api\Users\Doctors\DoctorController;
use App\Http\Controllers\Api\Users\Favorites\DoctorFavoriteController;
use App\Http\Controllers\Api\Users\Favorites\PostFavoriteController;
use App\Http\Controllers\Api\Users\Post\PostController;
use App\Http\Controllers\Api\Users\PostComments\PostCommentController;
use App\Http\Controllers\Api\Users\PostLikes\PostLikeController;
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
        Route::get('/users/{userId}/posts', 'doctorGetOwnPosts');
        Route::get('/users/{userId}/posts/{postId}', 'getDetailPost');
        Route::post('/users/{userId}/posts/{postId}', 'update');
    });

/**
 * Admin or doctor delete post
 */
Route::delete('/users/{userId}/posts/{postId}', [PostController::class, 'deletePost'])
    ->middleware(['auth:sanctum', 'has.any.roles:admin,doctor']);

/**
 * Like or unlike post
 */
Route::controller(PostLikeController::class)
    ->middleware(['auth:sanctum'])
    ->group(function () {
        // Check user like is exits
        Route::get('/posts/{postId}/likes/is-exist', 'checkUserLikePost');
        // The user like the post
        Route::post('/posts/{postId}/likes', 'likeThePost');
        // The user unlike the post
        Route::delete('/posts/{postId}/likes', 'unlikeThePost');
    });

/**
 * Comment the post
 */
Route::controller(PostCommentController::class)
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::post('/posts/{postId}/comments', 'storePostComment');
        Route::post('/posts/{postId}/comments/{commentId}/reply', 'replyPostComment');
    });

/**
 * User's favorite post
 */
Route::prefix('/users')
    ->controller(PostFavoriteController::class)
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/{userId}/favorites/posts', 'index')->withoutMiddleware(['auth:sanctum', 'api']);
        // Check is exits the post in favorite list
        Route::get('/{userId}/favorites/posts/{postId}', 'checkUserFollow')->withoutMiddleware('auth:sanctum');
        Route::post('/favorites/posts', 'store'); // store new post to favorite post list
        Route::delete('{userId}/favorites/posts/{postId}', 'destroy');
    });

/**
 * User's favorite doctor
 */
Route::prefix('/users')
    ->controller(DoctorFavoriteController::class)
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/{userId}/favorites/doctors', 'index')->withoutMiddleware(['auth:sanctum', 'api']);
        // Check is exits doctor in favorite list
        Route::get('/{userId}/favorites/doctors/{doctorId}', 'checkUserFollow')->withoutMiddleware('auth:sanctum');
        // add new doctor to favorite list
        Route::post('/favorites/doctors', 'addFavoriteItem');
        // Remove doctor from favorite list
        Route::delete('{userId}/favorites/doctors/{doctorId}', 'removeFavoriteItem');
    });
