<?php

use App\Http\Controllers\Api\Admins\Categories\CategoryController;
use App\Http\Controllers\Api\Admins\Posts\PostController;
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
    return $request->user();
});

/*
 * Admin handle posts
 */
Route::prefix('/admins')->controller(PostController::class)->middleware(['role:admin', 'auth:sanctum'])->group(function () {
    Route::get('/posts/not-published', 'getPostsIsNotPublished'); // Get posts where are not published
    Route::put('/posts/{postID}/publish', 'acceptPublishPost'); // Published post by update status => true for the post
});

/*
 * Admin handle posts
 */
Route::prefix('/admins')->controller(CategoryController::class)->middleware(['role:admin', 'auth:sanctum'])->group(function () {
    Route::post('/categories', 'store');
    Route::put('/categories/{categoryID}', 'update');
    Route::delete('/categories/{categoryID}', 'destroy');
});
