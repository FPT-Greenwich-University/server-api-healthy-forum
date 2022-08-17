<?php


use App\Http\Controllers\Api\ChatRoomsController;
use App\Http\Controllers\Api\ChatsController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
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

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::get('/chat-rooms/{chatRoomId}/messages', [ChatsController::class, 'fetchMessages'])->middleware('auth:sanctum');
Route::post('/chat-rooms/{chatRoomId}/messages', [ChatsController::class, 'sendMessage'])->middleware('auth:sanctum');
Route::get('/chat-rooms', [ChatRoomsController::class, 'getRoomChats'])->middleware('auth:sanctum');
Route::get('/chat-rooms/{chatRoomId}/users', [ChatRoomsController::class, 'getChatRoomUsers'])->middleware('auth:sanctum');
Route::post('/chat-rooms', [ChatRoomsController::class, 'createChatRoom'])->middleware('auth:sanctum');
