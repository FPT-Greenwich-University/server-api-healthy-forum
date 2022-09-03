<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Events\ChatRoomCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Chats\CreateChatRoomRequest;
use App\Repositories\Interfaces\IChatRoomDetailRepository;
use App\Repositories\Interfaces\IChatRoomRepository;
use App\Repositories\Interfaces\IMessageRepository;
use App\Repositories\Interfaces\IPermissionRepository;
use App\Repositories\Interfaces\IUserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatRoomsController extends Controller
{
    private readonly IMessageRepository $messageRepository;
    private readonly IChatRoomRepository $chatRoomRepository;
    private readonly IUserRepository $userRepository;
    private readonly IPermissionRepository $permissionRepository;
    private readonly IChatRoomDetailRepository $chatRoomDetailRepository;

    public function __construct(
        IMessageRepository        $messageRepository,
        IChatRoomRepository       $chatRoomRepository,
        IUserRepository           $userRepository,
        IPermissionRepository     $permissionRepository,
        IChatRoomDetailRepository $chatRoomDetailRepository
    )
    {
        $this->messageRepository = $messageRepository;
        $this->chatRoomRepository = $chatRoomRepository;
        $this->userRepository = $userRepository;
        $this->permissionRepository = $permissionRepository;
        $this->chatRoomDetailRepository = $chatRoomDetailRepository;
    }

    /**
     * Return the chat rooms by the user
     *
     * @param Request $request
     * @return JsonResponse
     */
    final public function getRoomChats(Request $request): JsonResponse
    {
        return response()->json($this->chatRoomDetailRepository->getChatRooms($request->user()->id));
    }

    final public function createChatRoom(CreateChatRoomRequest $request): JsonResponse
    {

        $currentUser = $this->userRepository->findById((int)$request->user()->id); // The current user send message
        $targetUser = $this->userRepository->findById((int)$request->input('targetUserId')); // The target user retrieve message

        // Create chat room if room not existed
        if ($this->chatRoomDetailRepository->checkExistedRoom(currentUserId: $currentUser->id, targetUserId: $targetUser->id) === false) {
            $newChatRoom = $this->chatRoomRepository->createNewRoom(); // Create new chat room for both of two users

            // Create new chat room details
            $this->chatRoomDetailRepository->createChatRoomDetails(chatRoom: $newChatRoom, currentUser: $currentUser, targetUser: $targetUser);

            // Store new message to the database
            $this->messageRepository->createNewMessage([
                'message' => 'Hello',
                'chat_room_id' => $newChatRoom->id,
                'source_id' => $currentUser->id,
                'target_id' => $targetUser->id
            ]);

            $permissionName = 'chat-room.' . $newChatRoom->id; // Initial permission name
            $this->permissionRepository->create(['name' => $permissionName, 'guard_name' => 'web']); // Create new permission with permission name

            $this->userRepository->setDirectPermission($currentUser->id, $permissionName); // Give permission access this room to source user
            $this->userRepository->setDirectPermission($targetUser->id, $permissionName); // Giver permission access this room to target user

            broadcast(new ChatRoomCreated($targetUser->id)); // Send broadcast notification to VueJS client side

            return response()->json(['ChatRoom' => $newChatRoom], 200); // Return HTTP 201 created success
        }

        return response()->json("", 204); // Return HTTP 204 if the chat room was existed
    }
}
