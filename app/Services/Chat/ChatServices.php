<?php
declare(strict_types=1);

namespace App\Services\Chat;

use App\Events\MessageSent;
use App\Http\Requests\Api\Chats\StoreMessageRequest;
use App\Models\User;
use App\Repositories\Interfaces\IMessageRepository;
use App\Services\FileServices\FileServicesContract;
use ZipArchive;

class ChatServices implements ChatServiceContracts
{
    private readonly IMessageRepository $messageRepository;
    private readonly FileServicesContract $fileServices;

    public function __construct(IMessageRepository $messageRepository, FileServicesContract $fileServicesContract)
    {
        $this->messageRepository = $messageRepository;
        $this->fileServices = $fileServicesContract;
    }

    public function sendMessage(int $chatRoomId, StoreMessageRequest $request): void
    {
        $user = $request->user(); // Get the current auth user
        $targetUserId = (int)($request->input('targetUserId')); // Get the target user retrieve message
        $array = []; // Array for collect file input from user

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
    }

    /**
     * Download single file message chat
     *
     * @param $file
     * @return string
     */
    public function downloadFileAsAZip($file): string
    {
        $zip = new ZipArchive(); // create object of zip archive

        $zipFile = 'messageZipFile.zip'; // Set the default name of file zip

        // Open the file zip
        $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFile(public_path() . '/' . $file->path, $file->name);
        $zip->close(); // Close file zip

        return $zipFile;
    }

    public function downloadAllFileAsAZip($files): string
    {
        $zip = new ZipArchive(); // Create new object of ZipArchive class

        $zipFile = 'messageZipFile.zip'; // Set the default name of file zip


        // Open the file zip
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($files as $key => $file) {
                $zip->addFile(public_path() . '/' . $file['path'], $file['name']); // Add each the file to the zip file
            }

            $zip->close(); // Close file zip
        }

        return $zipFile;
    }
}
