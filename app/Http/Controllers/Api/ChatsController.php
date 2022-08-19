<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Chats\StoreMessageRequest;
use App\Models\File;
use App\Models\Message;
use App\Repositories\Interfaces\IChatRoomRepository;
use App\Repositories\Interfaces\IMessageRepository;
use App\Services\FileServices\FileServicesContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Broadcasting\InteractsWithSockets;

class ChatsController extends Controller
{
    private readonly IChatRoomRepository $chatRoomRepository;
    private readonly IMessageRepository $messageRepository;
    private readonly FileServicesContract $fileServices;

    public function __construct(IChatRoomRepository $chatRoomRepository, IMessageRepository $messageRepository, FileServicesContract $fileServicesContract)
    {
        $this->chatRoomRepository = $chatRoomRepository;
        $this->messageRepository = $messageRepository;
        $this->fileServices = $fileServicesContract;
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
    public function sendMessage(StoreMessageRequest $request, int $chatRoomId): JsonResponse
    {
        if (is_null($this->chatRoomRepository->findById($chatRoomId))) return response()->json("Chat room not found", 404);

        $permissionName = 'chat-room.' . $chatRoomId;

        if (!$request->user()->can("$permissionName")) return response()->json("Bad request", 400); // Check is user has permission to send message in this chat room

        $user = $request->user();
        $targetId = intval($request->input('targetId'));

        $message = $this->messageRepository->createNewMessage(['chat_room_id' => $chatRoomId, 'source_id' => $user->id, 'target_id' => $targetId, 'message' => $request->input('message')]);

        if ($request->hasFile("files")) {
            foreach ($request->file("files") as $key => $file) {
                $fileName = $file->hashName(); // Hash file's name
                $targetDir = 'messages/files/'; // Set default target directory
                $this->fileServices->storeFile($file, $targetDir, $fileName);

                $array[$key]['path'] = $targetDir . $fileName;
            }
            // Insert data to File table with relationship
            $message->files()->createMany($array);
        }

        broadcast(new MessageSent($user, $message))->toOthers();

        return response()->json(['status' => 'Message Sent!'], 201);
    }
}
