<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Repositories\Interfaces\IChatRoomRepository;
use App\Repositories\Interfaces\IMessageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Broadcasting\InteractsWithSockets;

class ChatsController extends Controller
{
    private readonly IChatRoomRepository $chatRoomRepository;
    private readonly IMessageRepository $messageRepository;

    public function __construct(IChatRoomRepository $chatRoomRepository, IMessageRepository $messageRepository)
    {
        $this->chatRoomRepository = $chatRoomRepository;
        $this->messageRepository = $messageRepository;
    }

    /**
     * Fetch all messages
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchMessages(int $chatRoomId): JsonResponse
    {
        if (is_null($this->chatRoomRepository->findById($chatRoomId))) {
            return response()->json("Chat Room Not Found", 404);
        }

        $messages = Message::with(['user', 'chatRoom'])->where('chat_room_id', $chatRoomId)->get();

        return response()->json($messages);
    }

    /**
     * Persist message to database
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendMessage(Request $request, int $chatRoomId): JsonResponse
    {

        if (is_null($this->chatRoomRepository->findById($chatRoomId))) return response()->json("Chat room not found", 404);

        $permissionName = 'chat-room.' . $chatRoomId;

        if (!$request->user()->can("$permissionName")) return response()->json("Bad request", 400); // Check is user has permission to send message in this chat room

        $user = $request->user();
        $targetId = intval($request->input('targetId'));


        $message = $this->messageRepository->createNewMessage(['chat_room_id' => $chatRoomId, 'source_id' => $user->id, 'target_id' => $targetId, 'message' => $request->input('message')]);

        broadcast(new MessageSent($user, $message))->toOthers();

        return response()->json(['status' => 'Message Sent!'], 201);
    }
}
