<?php

use App\Http\Controllers\Api\Public\Categories\CategoryController;
use App\Http\Controllers\Api\Public\Comments\PublicCommentController;
use App\Http\Controllers\Api\Public\PostLikes\PublicPostLikeController;
use App\Http\Controllers\Api\Public\Posts\PublicPostController;
use App\Http\Controllers\Api\Public\PostTags\PublicPostTagController;
use App\Http\Controllers\Api\Public\PublicLocationController;
use App\Http\Controllers\Api\Search\SearchController;
use App\Http\Controllers\Api\Users\Doctors\DoctorController;
use Illuminate\Support\Facades\Route;

/**
 * Categories
 */
Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'getAllCategories'); // Get all the categories
});

/**
 * Post routes
 */
Route::controller(PublicPostController::class)->group(function () {
    Route::get('/posts', 'index');
    Route::get('/posts/{postId}', 'show');
    Route::get("/related-posts/{categoryId}", "getRelatedPosts");
});

/**
 * Get all published post of doctor
 */
Route::get('/users/{userId}/published-posts', [DoctorController::class, 'getPublishedPostsByUser']); // get doctor post

/**
 * Post tag routes
 */
Route::controller(PublicPostTagController::class)->group(function () {
    Route::get('/tags', 'index');
    Route::get('/posts/{postId}/tags', 'getPostTags'); // get the tags of the post
});

/**
 * Comment routes
 */
Route::controller(PublicCommentController::class)->group(function () {
    Route::get('/posts/{postId}/comments', 'index');
    Route::get('/posts/{postId}/comments/{commentId}/reply', 'getReplyComments');
});


/**
 * Post like routes
 */
Route::controller(PublicPostLikeController::class)->group(function () {
    Route::get('/posts/{postId}/total-likes', 'getTotalLike');
});

/**
 * Location routes
 */
Route::prefix('/public')->controller(PublicLocationController::class)->group(function () {
    Route::get('/cities', 'getCities');
    Route::get('/cities/{cityId}/districts', 'getDistricts');
    Route::get('/districts/{districtsId}/wards', 'getWards');
});

/**
 * Search resources
 */
Route::controller(SearchController::class)
    ->group(function () {
        Route::get('/search', 'searchPosts')->withoutMiddleware(['api']);
        Route::get('/search/users', 'searchUsers')->withoutMiddleware(['api']);
    });

