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
        Route::get('/posts/{postID}', 'show');
        Route::put('/posts/{postID}/publish', 'acceptPublishPost'); // Published post by update status => true for the post
    });

/*
 * Admin handle posts
 */
Route::prefix('/admins')
    ->controller(CategoryController::class)
    ->middleware(['role:admin', 'auth:sanctum'])
    ->group(function () {
        Route::post('/categories', 'store');
        Route::put('/categories/{categoryID}', 'update');
        Route::delete('/categories/{categoryID}', 'destroy');
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
        Route::get('/users/{userID}/roles', 'getUserRoles');
        Route::put('/users/{userID}/permissions', 'updatePermission'); // Update permission of the user
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
