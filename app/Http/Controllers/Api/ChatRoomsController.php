<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Events\ChatRoomCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Chats\CreateChatRoomRequest;
use App\Repositories\Interfaces\IChatRoomRepository;
use App\Repositories\Interfaces\IMessageRepository;
use App\Repositories\Interfaces\IPermissionRepository;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatRoomsController extends Controller
{
    private readonly IMessageRepository $messageRepository;
    private readonly IChatRoomRepository $chatRoomRepository;
    private readonly IUserRepository $userRepository;
    private readonly IPermissionRepository $permissionRepository;

    public function __construct(IMessageRepository $messageRepository, IChatRoomRepository $chatRoomRepository, IUserRepository $userRepository, IPermissionRepository $permissionRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->chatRoomRepository = $chatRoomRepository;
        $this->userRepository = $userRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Return the users in the chat room
     *
     * @param integer $chatRoomId
     * @return JsonResponse
     */
    final public function getChatRoomUsers(int $chatRoomId): JsonResponse
    {
        // Check the chat room is existed
        if (is_null($this->chatRoomRepository->findById($chatRoomId))) {
            return response()->json("Chat Room Not Found", 404);
        }
        // Get the first message by chat roo
        $message = $this->messageRepository->getTheFirstMessage($chatRoomId); // Get the first message to get users in chat room
        // Check the message is existed
        if (is_null($message)) {
            return response()->json("Message Not found", 404);
        }

        $users = ['source_id' => $message->source_id, 'target_id' => $message->target_id]; // Get the users by the first message

        return response()->json(['chat_room_users' => $users]);  // Return json array chat room users
    }

    /**
     * Return the chat rooms by the user
     *
     * @param Request $request
     * @return JsonResponse
     */
    final public function getRoomChats(Request $request): JsonResponse
    {
        return response()->json($this->chatRoomRepository->getChatRooms($request->user()->id));
    }

    final public function createChatRoom(CreateChatRoomRequest $request): JsonResponse
    {
        $sourceId = (int)($request->input('sourceId'));  // The source user send message
        $targetId = (int)($request->input('targetId'));  // The target user retrieve message

        // Create chat room if room not existed
        if (is_null($this->chatRoomRepository->getRoomByUserId(sourceId: $sourceId, targetId: $targetId))) {
            $newChatRoom = $this->chatRoomRepository->createNewRoom(); // Create new chat room for both of users
            // Store new message to the database
            $this->messageRepository->createNewMessage([
                'message' => 'Hello',
                'chat_room_id' => $newChatRoom->id,
                'source_id' => $sourceId,
                'target_id' => $targetId
            ]);

            $permissionName = 'chat-room.' . $newChatRoom->id; // Initial permission name
            $this->permissionRepository->create(['name' => $permissionName, 'guard_name' => 'web']); // Create new permission with permission name

            $this->userRepository->setDirectPermission($request->user()->id, $permissionName); // Give permission access this room to source user
            $this->userRepository->setDirectPermission($targetId, $permissionName); // Giver permission access this room to target user

            broadcast(new ChatRoomCreated(chatRoom: $newChatRoom, targetUserIdId: $targetId))->toOthers();  // Send broadcast notification to VueJS client side

            return response()->json(['ChatRoom' => $newChatRoom], 201); // Return HTTP 201 created success
        }

        return response()->json("", 204); // Return HTTP 204 if the chat room was existed
    }
}