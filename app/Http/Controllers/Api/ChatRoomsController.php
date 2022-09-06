<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Chats\CreateChatRoomRequest;
use App\Repositories\Interfaces\IChatRoomDetailRepository;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\Chat\ChatServiceContracts;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatRoomsController extends Controller
{
    private readonly IUserRepository $userRepository;
    private readonly IChatRoomDetailRepository $chatRoomDetailRepository;
    private readonly ChatServiceContracts $chatService;

    public function __construct(
        IUserRepository           $userRepository,
        IChatRoomDetailRepository $chatRoomDetailRepository,
        ChatServiceContracts      $chatServiceContracts,
    )
    {
        $this->userRepository = $userRepository;
        $this->chatRoomDetailRepository = $chatRoomDetailRepository;
        $this->chatService = $chatServiceContracts;
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
            $result = $this->chatService->createChatRoom(currentUser: $currentUser, targetUser: $targetUser);

            if ($result === false) {
                return response()->json("Failed to create new chat room", 400); //TODO: ask thay http status transaction failed
            }

            return response()->json("Create new chat room success!", 201);
        }

        return response()->json("", 204); // Return HTTP 204 if the chat room was existed
    }
}
