<?php

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

Route::get('/public/cities', [\App\Http\Controllers\Api\Public\LocationController::class, 'getCities']);
Route::get('/public/cities/{cityID}/districts', [\App\Http\Controllers\Api\Public\LocationController::class, 'getDistricts']);
Route::get('/public/districts/{districtsID}/wards', [\App\Http\Controllers\Api\Public\LocationController::class, 'getWards']);
