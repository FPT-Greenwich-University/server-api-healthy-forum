<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Repositories\Interfaces\IChatRoomRepository;
use App\Repositories\Interfaces\IMessageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    public function fetchMessages(Request $request): JsonResponse
    {
        $user = $request->user();
        $targetId = intval($request->input('targetId'));
        $roomId = $this->checkExistedRoom(sourceId: intval($user->id), targetId: $targetId);

        $messages = Message::with(['user', 'chatRoom'])->where('chat_room_id', $roomId)->get();

        return response()->json($messages);
    }

    /**
     * <p>Check the room is exist</p>
     * @param int $sourceId
     * @param int $targetId
     * @return int
     */
    private function checkExistedRoom(int $sourceId, int $targetId): int
    {
        $message = $this->messageRepository->getDetailMessage(sourceId: $sourceId, targetId: $targetId);

        // If the message is null, the room is not exist
        if (is_null($message)) {
            $room = $this->chatRoomRepository->createNewRoom();

            $this->messageRepository->createNewMessage([
                'message' => 'Hello',
                'chat_room_id' => $room->id,
                'source_id' => $sourceId,
                'target_id' => $targetId,
            ]);

            return intval($room->id);
        }

        return intval($message->chat_room_id);  // Return chat room id if message is not null
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
        $targetId = intval($request->input('targetId'));

        $roomId = $this->checkExistedRoom(sourceId: intval($user->id), targetId: $targetId);

        $message = $this->messageRepository->createNewMessage([
            'chat_room_id' => $roomId,
            'source_id' => $user->id,
            'target_id' => $targetId,
            'message' => $request->input('message')
        ]);

        broadcast(new MessageSent($user, $message))->toOthers();

        return response()->json(['status' => 'Message Sent!'], 201);
    }
}
