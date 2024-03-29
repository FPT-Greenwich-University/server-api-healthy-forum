<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Chats\StoreMessageRequest;
use App\Models\File;
use App\Models\Message;
use App\Repositories\Interfaces\IChatRoomRepository;
use App\Repositories\Interfaces\IFileManagerRepository;
use App\Repositories\Interfaces\IMessageRepository;
use App\Services\Chat\ChatServiceContracts;
use App\Services\FileServices\FileServicesContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Broadcasting\InteractsWithSockets;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class ChatsController extends Controller
{
    private readonly IChatRoomRepository $chatRoomRepository;
    private readonly IMessageRepository $messageRepository;
    private readonly FileServicesContract $fileServices;
    private readonly IFileManagerRepository $fileManagerRepository;
    private readonly ChatServiceContracts $chatService;

    public function __construct(IChatRoomRepository $chatRoomRepository, IMessageRepository $messageRepository, FileServicesContract $fileServicesContract, IFileManagerRepository $fileManagerRepository, ChatServiceContracts $chatServiceContracts)
    {
        $this->chatRoomRepository = $chatRoomRepository;
        $this->messageRepository = $messageRepository;
        $this->fileServices = $fileServicesContract;
        $this->fileManagerRepository = $fileManagerRepository;
        $this->chatService = $chatServiceContracts;
    }

    /**
     * Fetch all messages
     *
     * @param Request $request
     * @return JsonResponse
     */
    final public function fetchMessages(int $chatRoomId): JsonResponse
    {
        // Check the chat room is existed?
        if (is_null($this->chatRoomRepository->findById($chatRoomId))) {
            return response()->json("Chat Room Not Found", 404);
        }

        // Return the messages by chat room
        return response()->json($this->messageRepository->getMessagesByChatRoom($chatRoomId));
    }

    /**
     * Persist message to database
     *
     * @param Request $request
     * @return JsonResponse
     */
    final public function sendMessage(StoreMessageRequest $request, int $chatRoomId): JsonResponse
    {
        $existedChatRoom = $this->chatRoomRepository->findById($chatRoomId);

        // Check the chat room is existed?
        if (is_null($existedChatRoom)) {
            return response()->json("Chat room not found", 404);
        }

        // Hanle send message
        $result = $this->chatService->sendMessage(chatRoomId: $chatRoomId, request: $request);

        if ($result === false) {
            return response()->json(['status' => 'Failed Sent Message'], 400); //TODO: ask thay http status transaction failed
        }

        return response()->json(['status' => 'Message Sent!'], 201);

    }

    /**
     * <p>Download the <b>Single File</b> in message chat<p>
     *
     * @param int $chatRoomId
     * @param int $messageId
     * @param int $fileId
     * @return JsonResponse|BinaryFileResponse
     */
    final public function downloadFile(int $chatRoomId, int $messageId, int $fileId): BinaryFileResponse
    {
        // Check the message is existed?
        if (is_null($this->messageRepository->findById($messageId))) {
            return response()->json("Message not found", 404);
        }

        $file = $this->fileManagerRepository->findById($fileId);

        // Check the file is existed
        if (is_null($file)) {
            return response()->json("File not found", 404);
        }

        $zipFile = $this->chatService->downloadFileAsAZip($file);

        return response()->download($zipFile);  // Return zip file
    }

    /**
     * <p>Download all <b>The Files</b> of message<p>
     *
     * @param int $chatRoomId
     * @param int $messageId
     * @return JsonResponse|BinaryFileResponse
     */
    final public function downloadZip(int $chatRoomId, int $messageId)
    {
        $message = $this->messageRepository->findById($messageId);

        // Check the message is existed?
        if (is_null($message)) {
            return response()->json("Message not found", 404);
        }

        $files = $message->files->toArray(); // Get array files information from the message

        $zipFile = $this->chatService->downloadAllFileAsAZip($files);

        return response()->download($zipFile); // Return file zip
    }
}
