<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Chats\CreateChatRoomRequest;
use App\Models\User;
use App\Repositories\Interfaces\IChatRoomRepository;
use App\Repositories\Interfaces\IMessageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class ChatRoomsController extends Controller
{
    private readonly IMessageRepository $messageRepository;
    private readonly IChatRoomRepository $chatRoomRepository;

    public function __construct(IMessageRepository $messageRepository, IChatRoomRepository $chatRoomRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->chatRoomRepository = $chatRoomRepository;
    }

    public function getChatRoomUsers(int $chatRoomId): JsonResponse
    {
        if (is_null($this->chatRoomRepository->findById($chatRoomId))) {
            return response()->json("Chat Room Not Found", 404);
        }

        $message = $this->messageRepository->getTheFirstMessage($chatRoomId); // Get the first message to get users in chat room

        if (is_null($message)) return response()->json("Message Not found", 404);

        $users = ['source_id' => $message->source_id, 'target_id' => $message->target_id];

        return response()->json(['chat_room_users' => $users]);
    }

    public function getRoomChats(Request $request): JsonResponse
    {
        return response()->json($this->chatRoomRepository->getChatRooms($request->user()->id));
    }

    public function createChatRoom(CreateChatRoomRequest $request): JsonResponse
    {
        $sourceId = intval($request->input('sourceId'));
        $targetId = intval($request->input('targetId'));

        $existedChatRoom = $this->chatRoomRepository->getRoomByUserId(sourceId: $sourceId, targetId: $targetId);

        if (is_null($existedChatRoom)) {
            $newChatRoom = $this->chatRoomRepository->createNewRoom();

            $this->messageRepository->createNewMessage(['message' => 'Hello', 'chat_room_id' => $newChatRoom->id, 'source_id' => $sourceId, 'target_id' => $targetId]);

            $permissionName = 'chat-room.' . $newChatRoom->id;
            Permission::create(['name' => $permissionName, 'guard_name' => 'web']);

            $request->user()->givePermissionTo($permissionName); // Give permission access this room to current user

            $targetUser = User::find($targetId);
            $targetUser->givePermissionTo($permissionName); // Giver permission access this room to target user

            return response()->json(['ChatRoom' => $newChatRoom], 201);
        }

        return response()->json("", 204);
    }
}
