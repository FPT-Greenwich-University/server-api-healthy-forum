<?php

use App\Http\Controllers\Api\Admins\Categories\CategoryController;
use App\Http\Controllers\Api\Admins\Posts\PostController;
use App\Http\Controllers\Api\Admins\Statistic\StatisticController;
use App\Http\Controllers\Api\Admins\Users\UserController;
use Illuminate\Support\Facades\Route;

/*
 * Admin handle posts
 */

Route::prefix('/admins')
    ->controller(PostController::class)
    ->middleware(['role:admin', 'auth:sanctum'])
    ->group(function () {
        Route::get('/posts/not-published', 'getPostsIsNotPublished'); // Get posts where are not published
        Route::get('/posts/{postId}', 'show');
        Route::put('/posts/{postId}/publish', 'acceptPublishPost')->where('postId', '[0-9]+'); // Published post by update status => true for the post
    });

/*
 * Admin handle posts
 */
Route::prefix('/admins')
    ->controller(CategoryController::class)
    ->middleware(['role:admin', 'auth:sanctum'])
    ->group(function () {
        Route::post('/categories', 'store');
        Route::put('/categories/{categoryId}', 'update');
        Route::delete('/categories/{categoryId}', 'destroy');
    });

/**
 * Admin manager user
 */
Route::prefix('/admins')
    ->middleware(['role:admin', 'auth:sanctum'])
    ->controller(UserController::class)
    ->group(function () {
        Route::get('/users', 'index'); // List all users
        Route::get('/roles', 'getRoles');
        Route::post('/permissions', 'getPermissionsByRole'); // get all the permissions by the roles
        Route::get('/users/{userId}/roles', 'getUserRoles');
        Route::put('/users/{userId}/permissions', 'updatePermission'); // Update permission of the user
    });

/**
 * Admin statistic
 */
Route::prefix('/admins/statistic')
    ->middleware(['role:admin', 'auth:sanctum'])
    ->controller(StatisticController::class)
    ->group(function () {
        Route::get('/posts', 'getPostsMostLiked');
    });
