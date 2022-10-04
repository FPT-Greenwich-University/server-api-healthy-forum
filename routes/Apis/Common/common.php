<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\PostLikeController;
use App\Http\Controllers\Api\PostTagController;
use App\Http\Controllers\Api\SearchController;
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
Route::controller(PostController::class)->group(function () {
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
Route::controller(PostTagController::class)->group(function () {
    Route::get('/tags', 'index');
    Route::get('/posts/{postId}/tags', 'getPostTags'); // get the tags of the post
});

/**
 * Comment routes
 */
Route::controller(CommentController::class)->group(function () {
    Route::get('/posts/{postId}/comments', 'index');
    Route::get('/posts/{postId}/comments/{commentId}/reply', 'getReplyComments');
});


/**
 * Post like routes
 */
Route::controller(PostLikeController::class)->group(function () {
    Route::get('/posts/{postId}/total-likes', 'getTotalLike');
});

/**
 * Location routes
 */
Route::controller(LocationController::class)->group(function () {
    Route::get('/public/cities', 'getCities');
    Route::get('/public/cities/{cityId}/districts', 'getDistricts');
    Route::get('/public/districts/{districtsId}/wards', 'getWards');
});

/**
 * Search resources
 */
Route::controller(SearchController::class)
    ->group(function () {
        Route::get('/search', 'searchPosts')->withoutMiddleware(['api']);
        Route::get('/search/users', 'searchUsers')->withoutMiddleware(['api']);
    });