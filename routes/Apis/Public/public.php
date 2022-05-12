<?php

use App\Http\Controllers\Api\Public\Categories\CategoryController;
use App\Http\Controllers\Api\Public\Comments\PublicCommentController;
use App\Http\Controllers\Api\Public\PostLikes\PublicPostLikeController;
use App\Http\Controllers\Api\Public\PostRatings\PublicPostRatingController;
use App\Http\Controllers\Api\Public\Posts\PublicPostController;
use App\Http\Controllers\Api\Public\PostTags\PublicPostTagController;
use App\Http\Controllers\Api\Public\PublicLocationController;
use App\Http\Controllers\Api\Users\Doctors\DoctorController;
use Illuminate\Support\Facades\Route;

/**
 * Categories
 */
Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'index'); // Get all the categories
});

/**
 * Post routes
 */
Route::controller(PublicPostController::class)->group(function () {
    Route::get('/posts', 'index');
    Route::get('/posts/tags/{tagID}', 'getPostsByTag'); // get the posts by tag
    Route::get('/posts/{postID}', 'show');
});

/**
 * Doctor get all post
 */
Route::get('/users/{userID}/posts', [DoctorController::class, 'getPosts']); // get doctor post

/**
 * Post tag routes
 */
Route::controller(PublicPostTagController::class)->group(function () {
    Route::get('/tags', 'index');
    Route::get('/posts/{postID}/tags', 'getPostTags'); // get the tags of the post
});

/**
 * Comment routes
 */
Route::controller(PublicCommentController::class)->group(function () {
    Route::get('/posts/{postID}/comments', 'index');
    Route::get('/posts/{postID}/comments/{commentID}/reply', 'getReplyComments');
});


/**
 * Post like routes
 */
Route::controller(PublicPostLikeController::class)->group(function () {
    Route::get('/posts/{postID}/total-likes', 'getTotalLike');
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
Route::prefix('/public')->controller(PublicLocationController::class)->group(function () {
    Route::get('/cities', 'getCities');
    Route::get('/cities/{cityID}/districts', 'getDistricts');
    Route::get('/districts/{districtsID}/wards', 'getWards');
});

