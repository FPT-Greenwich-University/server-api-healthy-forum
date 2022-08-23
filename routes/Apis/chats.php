<?php

use App\Http\Controllers\Api\ChatRoomsController;
use App\Http\Controllers\Api\ChatsController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::get('/chat-rooms', [ChatRoomsController::class, 'getRoomChats'])->middleware('auth:sanctum');
Route::post('/chat-rooms', [ChatRoomsController::class, 'createChatRoom'])->middleware('auth:sanctum');
Route::get('/chat-rooms/{chatRoomId}/users', [ChatRoomsController::class, 'getChatRoomUsers'])->middleware('auth:sanctum');

Route::get('/chat-rooms/{chatRoomId}/messages', [ChatsController::class, 'fetchMessages'])->middleware('auth:sanctum');
Route::post('/chat-rooms/{chatRoomId}/messages', [ChatsController::class, 'sendMessage'])->middleware(['auth:sanctum', 'chat.room.permission']);
Route::get('/chat-rooms/{chatRoomId}/messages/{messageId}/files', [ChatsController::class, 'downloadZip'])->middleware(['auth:sanctum', 'chat.room.permission']);
Route::get('/chat-rooms/{chatRoomId}/messages/{messageId}/files/{fileId}', [ChatsController::class, 'downloadFile'])->middleware(['auth:sanctum', 'chat.room.permission']);
