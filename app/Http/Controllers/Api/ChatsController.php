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

    public function __construct(IChatRoomRepository $chatRoomRepository, IMessageRepository $messageRepository, FileServicesContract $fileServicesContract, IFileManagerRepository $fileManagerRepository)
    {
        $this->chatRoomRepository = $chatRoomRepository;
        $this->messageRepository = $messageRepository;
        $this->fileServices = $fileServicesContract;
        $this->fileManagerRepository = $fileManagerRepository;
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

        $user = $request->user(); // Get the current auth user

        $targetUserId = (int)($request->input('targetUserId')); // Get the target user retrieve message

        // Store new message in database
        $message = $this->messageRepository->createNewMessage([
            'chat_room_id' => $chatRoomId,
            'source_id' => $user->id,
            'target_id' => $targetUserId,
            'message' => trim($request->input('message'))
        ]);

        // Send message include the files if request has files
        if ($request->hasFile("files")) {
            foreach ($request->file("files") as $key => $file) {
                $originFileName = $file->getClientOriginalName();
                $hashFileName = $file->hashName(); // Hash file's name
                $targetDir = 'messages/files/'; // Set default target directory
                $this->fileServices->storeFile($file, $targetDir, $hashFileName);

                $array[$key]['name'] = $originFileName;
                $array[$key]['path'] = $targetDir . $hashFileName;
            }

            // Insert data to File table with relationship
            $message->files()->createMany($array);
        }

        broadcast(new MessageSent($user, $message, $chatRoomId))->toOthers();

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
    final public function downloadFile(int $chatRoomId, int $messageId, int $fileId)
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

        $zip = new ZipArchive(); // create object of zip archive

        $zipFile = 'messageZipFile.zip'; // Set the default name of file zip

        // Open the file zip
        $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFile(public_path() . '/' . $file->path, $file->name);
        $zip->close(); // Close file zip

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

        $zip = new ZipArchive(); // Create new object of ZipArchive class

        $zipFile = 'messageZipFile.zip'; // Set the default name of file zip

        $files = $message->files->toArray(); // Get array files information from the message

        // Open the file zip
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($files as $key => $file) {
                $zip->addFile(public_path() . '/' . $file['path'], $file['name']); // Add each the file to the zip file
            }

            $zip->close(); // Close file zip
        }

        return response()->download($zipFile); // Return file zip
    }
}
