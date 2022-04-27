<?php

use App\Http\Controllers\Api\Admins\Categories\CategoryController;
use App\Http\Controllers\Api\Admins\Posts\PostController;
use App\Http\Controllers\Api\Admins\Users\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json(User::with('roles')->findOrFail($request->user()->id));
});

/*
 * Admin handle posts
 */
Route::prefix('/admins')
    ->controller(PostController::class)
    ->middleware(['role:admin', 'auth:sanctum'])
    ->group(function () {
        Route::get('/posts/not-published', 'getPostsIsNotPublished'); // Get posts where are not published
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
    });
