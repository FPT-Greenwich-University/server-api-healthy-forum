<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    /**
     * Fetch all messages
     *
     * @return JsonResponse
     */
    public function fetchMessages(): JsonResponse
    {
        return response()->json(Message::with('user')->get());
    }


    /**
     * Persist message to database
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $user = $request->user();

        $message = $user->messages()->create(['message' => $request->input('message')]);

        broadcast(new MessageSent($user, $message))->toOthers();

        return response()->json(['status' => 'Message Sent!'], 201);
    }
}
