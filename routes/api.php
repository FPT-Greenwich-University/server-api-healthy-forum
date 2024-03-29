<?php


use App\Http\Controllers\Api\NotificationController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*b
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
    return response()->json(User::with(['roles', 'permissions'])->findOrFail($request->user()->id));
});

Route::middleware('auth:sanctum')->get('/notifications', [NotificationController::class, 'index']);
